<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GdprDataRequestResource\Pages;
use App\Models\GdprDataRequest;
use App\Models\Member;
use App\Services\GdprService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class GdprDataRequestResource extends Resource
{
    protected static ?string $model = GdprDataRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'GDPR Compliance';

    protected static ?string $navigationLabel = 'Data Requests';

    protected static ?string $modelLabel = 'GDPR Data Request';

    protected static ?string $pluralModelLabel = 'GDPR Data Requests';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Information')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->relationship('member', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('request_type')
                            ->label('Request Type')
                            ->options(GdprDataRequest::getRequestTypes())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(GdprDataRequest::getStatusOptions())
                            ->default('pending')
                            ->required(),

                        Forms\Components\DateTimePicker::make('requested_at')
                            ->label('Requested At')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Request Details')
                    ->schema([
                        Forms\Components\Textarea::make('request_details')
                            ->label('Request Details')
                            ->rows(3)
                            ->placeholder('Additional details about the request...'),

                        Forms\Components\CheckboxList::make('requested_data_types')
                            ->label('Requested Data Types')
                            ->options(GdprDataRequest::getDataTypes())
                            ->columns(2)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Processing Information')
                    ->schema([
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->visible(fn (callable $get) => in_array($get('status'), ['completed', 'rejected'])),

                        Forms\Components\TextInput::make('processed_by')
                            ->label('Processed By')
                            ->placeholder('Will be auto-populated'),

                        Forms\Components\Textarea::make('completion_notes')
                            ->label('Completion Notes')
                            ->rows(4)
                            ->visible(fn (callable $get) => in_array($get('status'), ['completed', 'rejected'])),

                        Forms\Components\Repeater::make('exported_files')
                            ->label('Exported Files')
                            ->schema([
                                Forms\Components\TextInput::make('filename')
                                    ->required(),
                                Forms\Components\TextInput::make('filepath')
                                    ->required(),
                                Forms\Components\TextInput::make('size')
                                    ->suffix('bytes'),
                            ])
                            ->visible(fn (callable $get) => $get('status') === 'completed')
                            ->collapsible(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('Member')
                    ->formatStateUsing(fn ($record) => $record->member->first_name . ' ' . $record->member->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\SelectColumn::make('request_type')
                    ->label('Request Type')
                    ->options(GdprDataRequest::getRequestTypes())
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options(GdprDataRequest::getStatusOptions())
                    ->sortable(),

                Tables\Columns\TextColumn::make('requested_at')
                    ->label('Requested')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('days_remaining')
                    ->label('Days Remaining')
                    ->formatStateUsing(fn (GdprDataRequest $record) => $record->getDaysRemaining())
                    ->badge()
                    ->color(fn (GdprDataRequest $record) => match(true) {
                        $record->getDaysRemaining() <= 0 => 'danger',
                        $record->getDaysRemaining() <= 7 => 'warning',
                        default => 'success'
                    }),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->placeholder('Not completed'),

                Tables\Columns\TextColumn::make('processed_by')
                    ->label('Processed By')
                    ->sortable()
                    ->placeholder('Unassigned'),

                Tables\Columns\IconColumn::make('is_overdue')
                    ->label('Overdue')
                    ->boolean()
                    ->getStateUsing(fn (GdprDataRequest $record) => $record->isOverdue())
                    ->trueColor('danger')
                    ->falseColor('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('request_type')
                    ->label('Request Type')
                    ->options(GdprDataRequest::getRequestTypes()),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(GdprDataRequest::getStatusOptions()),

                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue Requests')
                    ->query(fn (Builder $query): Builder =>
                        $query->where('requested_at', '<', now()->subDays(30))
                              ->whereIn('status', ['pending', 'processing'])
                    ),

                Tables\Filters\Filter::make('recent')
                    ->label('Recent Requests (Last 7 days)')
                    ->query(fn (Builder $query): Builder =>
                        $query->where('requested_at', '>=', now()->subDays(7))
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('process_export')
                        ->label('Process Export')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->visible(fn (GdprDataRequest $record) =>
                            $record->request_type === 'export' && $record->status === 'pending'
                        )
                        ->requiresConfirmation()
                        ->action(function (GdprDataRequest $record) {
                            $record->markAsProcessing();

                            try {
                                $gdprService = app(GdprService::class);
                                $result = $gdprService->exportMemberData(
                                    $record->member,
                                    $record->requested_data_types ?? []
                                );

                                $record->markAsCompleted(
                                    'Data export completed successfully',
                                    $result['files']
                                );

                                Notification::make()
                                    ->title('Export completed successfully')
                                    ->body('Member data has been exported and is ready for download')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                $record->markAsRejected('Export failed: ' . $e->getMessage());

                                Notification::make()
                                    ->title('Export failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('process_deletion')
                        ->label('Process Deletion')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->visible(fn (GdprDataRequest $record) =>
                            $record->request_type === 'deletion' && $record->status === 'pending'
                        )
                        ->requiresConfirmation()
                        ->modalHeading('Confirm Data Deletion')
                        ->modalDescription('This action cannot be undone. Are you sure you want to delete the requested data?')
                        ->action(function (GdprDataRequest $record) {
                            $record->markAsProcessing();

                            try {
                                $gdprService = app(GdprService::class);
                                $result = $gdprService->deleteMemberData(
                                    $record->member,
                                    $record->requested_data_types ?? []
                                );

                                $record->markAsCompleted('Data deletion completed successfully');

                                Notification::make()
                                    ->title('Deletion completed successfully')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                $record->markAsRejected('Deletion failed: ' . $e->getMessage());

                                Notification::make()
                                    ->title('Deletion failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('mark_completed')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (GdprDataRequest $record) => $record->status === 'processing')
                        ->form([
                            Forms\Components\Textarea::make('completion_notes')
                                ->label('Completion Notes')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (GdprDataRequest $record, array $data) {
                            $record->markAsCompleted($data['completion_notes']);

                            Notification::make()
                                ->title('Request marked as completed')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('reject_request')
                        ->label('Reject Request')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (GdprDataRequest $record) => in_array($record->status, ['pending', 'processing']))
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (GdprDataRequest $record, array $data) {
                            $record->markAsRejected($data['rejection_reason']);

                            Notification::make()
                                ->title('Request rejected')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_process')
                        ->label('Mark as Processing')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->color('info')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $record->markAsProcessing();
                                }
                            }

                            Notification::make()
                                ->title('Requests marked as processing')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('requested_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGdprDataRequests::route('/'),
            'create' => Pages\CreateGdprDataRequest::route('/create'),
            'edit' => Pages\EditGdprDataRequest::route('/{record}/edit'),
            'view' => Pages\ViewGdprDataRequest::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::pending()->count();
        $overdueCount = static::getModel()::where('requested_at', '<', now()->subDays(30))
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        return $pendingCount + $overdueCount > 0 ? $pendingCount + $overdueCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = static::getModel()::where('requested_at', '<', now()->subDays(30))
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        return $overdueCount > 0 ? 'danger' : 'warning';
    }
}
