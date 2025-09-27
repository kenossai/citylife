<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GdprConsentResource\Pages;
use App\Models\GdprConsent;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class GdprConsentResource extends Resource
{
    protected static ?string $model = GdprConsent::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'GDPR Compliance';

    protected static ?string $navigationLabel = 'Consent Management';

    protected static ?string $modelLabel = 'GDPR Consent';

    protected static ?string $pluralModelLabel = 'GDPR Consents';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Consent Information')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->relationship('member', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('consent_type')
                            ->label('Consent Type')
                            ->options(GdprConsent::getConsentTypes())
                            ->required(),

                        Forms\Components\Toggle::make('consent_given')
                            ->label('Consent Given')
                            ->default(true)
                            ->live(),

                        Forms\Components\Select::make('consent_method')
                            ->label('Consent Method')
                            ->options(GdprConsent::getConsentMethods())
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Consent Details')
                    ->schema([
                        Forms\Components\DateTimePicker::make('consent_date')
                            ->label('Consent Date')
                            ->default(now())
                            ->required()
                            ->visible(fn (callable $get) => $get('consent_given')),

                        Forms\Components\DateTimePicker::make('consent_withdrawn_date')
                            ->label('Consent Withdrawn Date')
                            ->visible(fn (callable $get) => !$get('consent_given')),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->placeholder('Will be auto-populated from request'),

                        Forms\Components\Textarea::make('withdrawal_reason')
                            ->label('Withdrawal Reason')
                            ->rows(3)
                            ->visible(fn (callable $get) => !$get('consent_given')),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Details')
                    ->schema([
                        Forms\Components\KeyValue::make('consent_details')
                            ->label('Consent Details')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->reorderable(false)
                            ->addActionLabel('Add Detail'),
                    ]),
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

                Tables\Columns\SelectColumn::make('consent_type')
                    ->label('Consent Type')
                    ->options(GdprConsent::getConsentTypes())
                    ->sortable(),

                Tables\Columns\IconColumn::make('consent_given')
                    ->label('Consent Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('consent_date')
                    ->label('Consent Date')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('consent_withdrawn_date')
                    ->label('Withdrawn Date')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->placeholder('Not withdrawn'),

                Tables\Columns\SelectColumn::make('consent_method')
                    ->label('Method')
                    ->options(GdprConsent::getConsentMethods())
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('consent_type')
                    ->label('Consent Type')
                    ->options(GdprConsent::getConsentTypes()),

                Tables\Filters\TernaryFilter::make('consent_given')
                    ->label('Consent Status')
                    ->boolean()
                    ->trueLabel('Consent Given')
                    ->falseLabel('Consent Withdrawn')
                    ->native(false),

                Tables\Filters\SelectFilter::make('consent_method')
                    ->label('Consent Method')
                    ->options(GdprConsent::getConsentMethods()),

                Tables\Filters\Filter::make('recent_consents')
                    ->label('Recent Consents (Last 30 days)')
                    ->query(fn (Builder $query): Builder =>
                        $query->where('created_at', '>=', now()->subDays(30))
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('withdraw_consent')
                        ->label('Withdraw Consent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (GdprConsent $record) => $record->consent_given)
                        ->form([
                            Forms\Components\Textarea::make('withdrawal_reason')
                                ->label('Reason for Withdrawal')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (GdprConsent $record, array $data) {
                            $record->withdraw($data['withdrawal_reason']);

                            Notification::make()
                                ->title('Consent withdrawn successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('grant_consent')
                        ->label('Grant Consent')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (GdprConsent $record) => !$record->consent_given)
                        ->requiresConfirmation()
                        ->action(function (GdprConsent $record) {
                            $record->grant();

                            Notification::make()
                                ->title('Consent granted successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_withdraw')
                        ->label('Withdraw Selected Consents')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('withdrawal_reason')
                                ->label('Reason for Withdrawal')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                if ($record->consent_given) {
                                    $record->withdraw($data['withdrawal_reason']);
                                }
                            }

                            Notification::make()
                                ->title('Consents withdrawn successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGdprConsents::route('/'),
            'create' => Pages\CreateGdprConsent::route('/create'),
            'edit' => Pages\EditGdprConsent::route('/{record}/edit'),
            'view' => Pages\ViewGdprConsent::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $recentWithdrawals = static::getModel()::withdrawn()
            ->where('consent_withdrawn_date', '>=', now()->subDays(7))
            ->count();

        return $recentWithdrawals > 0 ? $recentWithdrawals : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
