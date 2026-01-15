<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifiedMembersResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerifiedMembersResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Verified Members';

    protected static ?string $modelLabel = 'Verified Member';

    protected static ?string $navigationGroup = 'Member Management';

    protected static ?int $navigationSort = 2;

    // Only show members who have verified email but are pending approval
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('email_verified_at')
            ->whereNull('approved_at');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Member Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('last_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\TextInput::make('membership_status')
                            ->disabled(),
                        Forms\Components\Placeholder::make('email_verified_at')
                            ->label('Email Verified At')
                            ->content(fn ($record) => $record->email_verified_at?->format('M d, Y H:i')),
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Registration Date')
                            ->content(fn ($record) => $record->created_at?->format('M d, Y H:i')),
                    ])->columns(3),

                Forms\Components\Section::make('Approval')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('membership_number')
                    ->label('Member #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn($record) => trim($record->first_name . ' ' . $record->last_name))
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('recent')
                    ->label('Last 7 Days')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7)))
                    ->toggle(),
                Tables\Filters\Filter::make('last_30_days')
                    ->label('Last 30 Days')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Member')
                    ->modalDescription('This will approve the member account and allow them to login.')
                    ->action(function (Member $record) {
                        $record->update([
                            'approved_at' => now(),
                            'approved_by' => Auth::id(),
                        ]);

                        // Send approval notification to member
                        try {
                            $record->notify(new \App\Notifications\MemberApproved());
                        } catch (\Exception $e) {
                            Log::error('Failed to send member approval notification', [
                                'member_id' => $record->id,
                                'error' => $e->getMessage(),
                            ]);
                        }

                        Notification::make()
                            ->title('Member Approved')
                            ->success()
                            ->body($record->first_name . ' ' . $record->last_name . ' has been approved.')
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Member Application')
                    ->modalDescription('This will deactivate the member account. You can provide a reason below.')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for Rejection')
                            ->placeholder('Provide a reason for rejecting this member application...')
                            ->rows(3),
                    ])
                    ->action(function (Member $record, array $data) {
                        $record->update([
                            'is_active' => false,
                            'notes' => ($record->notes ? $record->notes . "\n\n" : '') .
                                      "Rejected on " . now()->format('M d, Y') .
                                      " by " . (Auth::user()->name ?? 'Admin') .
                                      ($data['rejection_reason'] ? ":\n" . $data['rejection_reason'] : ''),
                        ]);

                        Notification::make()
                            ->title('Member Rejected')
                            ->warning()
                            ->body($record->first_name . ' ' . $record->last_name . ' has been rejected and deactivated.')
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.members.edit', $record))
                    ->tooltip('Edit in Members'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Approve Selected Members')
                        ->modalDescription('This will approve all selected member accounts.')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'approved_at' => now(),
                                    'approved_by' => Auth::id(),
                                ]);

                                // Send approval notification to each member
                                try {
                                    $record->notify(new \App\Notifications\MemberApproved());
                                } catch (\Exception $e) {
                                    Log::error('Failed to send member approval notification', [
                                        'member_id' => $record->id,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                            }

                            Notification::make()
                                ->title('Members Approved')
                                ->success()
                                ->body(count($records) . ' member(s) have been approved.')
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Reject Selected Members')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => false,
                                    'notes' => ($record->notes ? $record->notes . "\n\n" : '') .
                                              "Rejected on " . now()->format('M d, Y') .
                                              " by " . (Auth::user()->name ?? 'Admin'),
                                ]);
                            }

                            Notification::make()
                                ->title('Members Rejected')
                                ->warning()
                                ->body(count($records) . ' member(s) have been rejected.')
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifiedMembers::route('/'),
            'view' => Pages\ViewVerifiedMember::route('/{record}'),
        ];
    }
}
