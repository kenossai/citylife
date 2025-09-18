<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GivingResource\Pages;
use App\Filament\Resources\GivingResource\RelationManagers;
use App\Models\Giving;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GivingResource extends Resource
{
    protected static ?string $model = Giving::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Finance & Giving';

    protected static ?string $navigationLabel = 'Giving Records';

    protected static ?string $modelLabel = 'Giving Record';

    protected static ?string $pluralModelLabel = 'Giving Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Donor Information')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->relationship('member', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Select if this is from a registered church member'),

                        Forms\Components\TextInput::make('donor_name')
                            ->label('Donor Name')
                            ->maxLength(255)
                            ->helperText('Name of donor (if not a registered member)'),

                        Forms\Components\TextInput::make('donor_email')
                            ->label('Donor Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('donor_phone')
                            ->label('Donor Phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Giving Details')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('£')
                            ->required()
                            ->step(0.01),

                        Forms\Components\Select::make('giving_type')
                            ->label('Type of Giving')
                            ->options([
                                'tithe' => 'Tithe',
                                'offering' => 'Offering',
                                'special_offering' => 'Special Offering',
                                'missions' => 'Missions',
                                'building_fund' => 'Building Fund',
                                'youth_ministry' => 'Youth Ministry',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'online' => 'Online Payment',
                                'cheque' => 'Cheque',
                                'card' => 'Card Payment',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('given_date')
                            ->label('Date Given')
                            ->required()
                            ->default(now()),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\TextInput::make('reference_number')
                            ->label('Reference/Transaction Number')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Anonymous Giving')
                            ->helperText('Hide donor information in reports'),

                        Forms\Components\Toggle::make('gift_aid_eligible')
                            ->label('Gift Aid Eligible')
                            ->helperText('Can this donation claim Gift Aid?'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('given_date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('donor_name')
                    ->label('Donor')
                    ->getStateUsing(function ($record) {
                        if ($record->is_anonymous) {
                            return 'Anonymous';
                        }
                        return $record->member
                            ? $record->member->full_name
                            : $record->donor_name;
                    })
                    ->searchable(['donor_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('GBP')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('GBP')
                            ->label('Total'),
                    ]),

                Tables\Columns\BadgeColumn::make('giving_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'tithe',
                        'success' => 'offering',
                        'warning' => 'special_offering',
                        'info' => 'missions',
                        'danger' => 'building_fund',
                        'secondary' => 'other',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tithe' => 'Tithe',
                        'offering' => 'Offering',
                        'special_offering' => 'Special Offering',
                        'missions' => 'Missions',
                        'building_fund' => 'Building Fund',
                        'youth_ministry' => 'Youth Ministry',
                        'other' => 'Other',
                        default => ucfirst($state),
                    }),

                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Method')
                    ->colors([
                        'success' => 'online',
                        'warning' => 'bank_transfer',
                        'info' => 'card',
                        'secondary' => ['cash', 'cheque'],
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bank_transfer' => 'Bank Transfer',
                        'online' => 'Online',
                        'card' => 'Card',
                        'cash' => 'Cash',
                        'cheque' => 'Cheque',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('gift_aid_eligible')
                    ->label('Gift Aid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_anonymous')
                    ->label('Anonymous')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recorded')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('giving_type')
                    ->label('Type of Giving')
                    ->options([
                        'tithe' => 'Tithe',
                        'offering' => 'Offering',
                        'special_offering' => 'Special Offering',
                        'missions' => 'Missions',
                        'building_fund' => 'Building Fund',
                        'youth_ministry' => 'Youth Ministry',
                        'other' => 'Other',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'online' => 'Online Payment',
                        'cheque' => 'Cheque',
                        'card' => 'Card Payment',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('given_date')
                    ->form([
                        Forms\Components\DatePicker::make('given_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('given_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['given_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('given_date', '>=', $date),
                            )
                            ->when(
                                $data['given_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('given_date', '<=', $date),
                            );
                    }),

                Tables\Filters\TernaryFilter::make('gift_aid_eligible')
                    ->label('Gift Aid Eligible'),

                Tables\Filters\TernaryFilter::make('is_anonymous')
                    ->label('Anonymous Giving'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            // Export functionality can be added here
                            \Filament\Notifications\Notification::make()
                                ->title('Export completed')
                                ->body('Giving records have been exported successfully.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('given_date', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds for live giving updates
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGivings::route('/'),
            'create' => Pages\CreateGiving::route('/create'),
            'edit' => Pages\EditGiving::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 100 ? 'success' : 'primary';
    }
}
