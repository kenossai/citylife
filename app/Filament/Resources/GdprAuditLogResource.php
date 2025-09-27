<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GdprAuditLogResource\Pages;
use App\Models\GdprAuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GdprAuditLogResource extends Resource
{
    protected static ?string $model = GdprAuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'GDPR Compliance';

    protected static ?string $navigationLabel = 'Audit Logs';

    protected static ?string $modelLabel = 'GDPR Audit Log';

    protected static ?string $pluralModelLabel = 'GDPR Audit Logs';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Audit Information')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->relationship('member', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record ? "{$record->first_name} {$record->last_name}" : 'System Action')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('action')
                            ->label('Action')
                            ->options(GdprAuditLog::getActionTypes())
                            ->required(),

                        Forms\Components\TextInput::make('data_type')
                            ->label('Data Type')
                            ->placeholder('e.g., personal_info, consents, etc.'),

                        Forms\Components\TextInput::make('performed_by')
                            ->label('Performed By')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Action Details')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3),

                        Forms\Components\KeyValue::make('old_values')
                            ->label('Old Values')
                            ->keyLabel('Field')
                            ->valueLabel('Old Value')
                            ->reorderable(false),

                        Forms\Components\KeyValue::make('new_values')
                            ->label('New Values')
                            ->keyLabel('Field')
                            ->valueLabel('New Value')
                            ->reorderable(false),
                    ]),

                Forms\Components\Section::make('Technical Details')
                    ->schema([
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->placeholder('Will be auto-populated'),

                        Forms\Components\Textarea::make('user_agent')
                            ->label('User Agent')
                            ->rows(2)
                            ->placeholder('Will be auto-populated'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('M j, Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('Member')
                    ->formatStateUsing(fn ($record) => $record->member ?
                        $record->member->first_name . ' ' . $record->member->last_name :
                        'System Action'
                    )
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\SelectColumn::make('action')
                    ->label('Action')
                    ->options(GdprAuditLog::getActionTypes())
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_type')
                    ->label('Data Type')
                    ->sortable()
                    ->placeholder('N/A'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->description;
                    }),

                Tables\Columns\TextColumn::make('performed_by')
                    ->label('Performed By')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('Action')
                    ->options(GdprAuditLog::getActionTypes()),

                Tables\Filters\Filter::make('member')
                    ->form([
                        Forms\Components\Select::make('member_id')
                            ->label('Member')
                            ->relationship('member', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['member_id'], fn ($query, $memberId) =>
                            $query->where('member_id', $memberId)
                        );
                    }),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('to_date')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from_date'], fn ($query, $date) =>
                                $query->whereDate('created_at', '>=', $date)
                            )
                            ->when($data['to_date'], fn ($query, $date) =>
                                $query->whereDate('created_at', '<=', $date)
                            );
                    }),

                Tables\Filters\Filter::make('recent')
                    ->label('Recent Activity (Last 7 days)')
                    ->query(fn (Builder $query): Builder =>
                        $query->where('created_at', '>=', now()->subDays(7))
                    ),

                Tables\Filters\Filter::make('system_actions')
                    ->label('System Actions Only')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereNull('member_id')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth('6xl'),
            ])
            ->bulkActions([
                // Audit logs should generally not be bulk deleted for compliance reasons
                // Only allow if user has specific permission
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false; // Audit logs should only be created programmatically
    }

    public static function canEdit($record): bool
    {
        return false; // Audit logs should not be editable for integrity
    }

    public static function canDelete($record): bool
    {
        return false; // Audit logs should not be deletable for compliance
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGdprAuditLogs::route('/'),
            'view' => Pages\ViewGdprAuditLog::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $recentCount = static::getModel()::where('created_at', '>=', now()->subDays(1))
            ->count();

        return $recentCount > 0 ? $recentCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
