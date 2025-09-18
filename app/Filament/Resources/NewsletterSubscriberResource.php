<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Filament\Resources\NewsletterSubscriberResource\RelationManagers;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Communication';

    protected static ?string $navigationLabel = 'Newsletter Subscribers';

    protected static ?string $modelLabel = 'Newsletter Subscriber';

    protected static ?string $pluralModelLabel = 'Newsletter Subscribers';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Subscription Details')
                    ->schema([
                        Forms\Components\Select::make('source')
                            ->label('Subscription Source')
                            ->options([
                                'website' => 'Website Form',
                                'member_registration' => 'Member Registration',
                                'manual' => 'Manual Entry',
                                'import' => 'Data Import',
                                'event' => 'Event Registration',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->default('website'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Subscription')
                            ->helperText('Whether this subscriber is currently receiving newsletters')
                            ->default(true),

                        Forms\Components\DateTimePicker::make('subscribed_at')
                            ->label('Subscribed Date')
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('unsubscribed_at')
                            ->label('Unsubscribed Date')
                            ->helperText('Date when subscriber unsubscribed (if applicable)'),
                    ])->columns(2),

                Forms\Components\Section::make('GDPR Compliance')
                    ->schema([
                        Forms\Components\Toggle::make('gdpr_consent')
                            ->label('GDPR Consent Given')
                            ->helperText('Whether the subscriber has given explicit GDPR consent')
                            ->required(),

                        Forms\Components\DateTimePicker::make('gdpr_consent_date')
                            ->label('GDPR Consent Date')
                            ->helperText('When GDPR consent was given'),

                        Forms\Components\TextInput::make('gdpr_consent_ip')
                            ->label('GDPR Consent IP Address')
                            ->helperText('IP address when consent was given')
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn($record) => trim($record->first_name . ' ' . $record->last_name))
                    ->searchable(['first_name', 'last_name'])
                    ->placeholder('No name provided'),

                Tables\Columns\SelectColumn::make('source')
                    ->label('Source')
                    ->options([
                        'website' => 'Website',
                        'member_registration' => 'Member Registration',
                        'manual' => 'Manual',
                        'import' => 'Import',
                        'event' => 'Event',
                        'other' => 'Other',
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('gdpr_consent')
                    ->label('GDPR Consent')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('Subscribed')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('unsubscribed_at')
                    ->label('Unsubscribed')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Still subscribed'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source')
                    ->label('Subscription Source')
                    ->options([
                        'website' => 'Website Form',
                        'member_registration' => 'Member Registration',
                        'manual' => 'Manual Entry',
                        'import' => 'Data Import',
                        'event' => 'Event Registration',
                        'other' => 'Other',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active subscribers only')
                    ->falseLabel('Inactive subscribers only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('gdpr_consent')
                    ->label('GDPR Consent')
                    ->boolean()
                    ->trueLabel('GDPR consent given')
                    ->falseLabel('No GDPR consent')
                    ->native(false),

                Tables\Filters\Filter::make('subscribed_recently')
                    ->label('Subscribed in last 30 days')
                    ->query(fn (Builder $query): Builder => $query->where('subscribed_at', '>=', now()->subDays(30))),

                Tables\Filters\Filter::make('unsubscribed')
                    ->label('Unsubscribed')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('unsubscribed_at')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('unsubscribe')
                        ->label('Unsubscribe')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn($record) => $record->is_active)
                        ->requiresConfirmation()
                        ->modalHeading('Unsubscribe Newsletter Subscriber')
                        ->modalDescription('Are you sure you want to unsubscribe this person from the newsletter?')
                        ->action(function ($record) {
                            $record->unsubscribe();
                        })
                        ->successNotificationTitle('Subscriber has been unsubscribed'),

                    Tables\Actions\Action::make('resubscribe')
                        ->label('Resubscribe')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn($record) => !$record->is_active)
                        ->requiresConfirmation()
                        ->modalHeading('Resubscribe Newsletter Subscriber')
                        ->modalDescription('Are you sure you want to resubscribe this person to the newsletter?')
                        ->action(function ($record) {
                            $record->resubscribe();
                        })
                        ->successNotificationTitle('Subscriber has been resubscribed'),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_unsubscribe')
                        ->label('Unsubscribe Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Bulk Unsubscribe Newsletter Subscribers')
                        ->modalDescription('Are you sure you want to unsubscribe all selected subscribers from the newsletter?')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->is_active) {
                                    $record->unsubscribe();
                                }
                            }
                        })
                        ->successNotificationTitle('Selected subscribers have been unsubscribed'),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('subscribed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNewsletterSubscribers::route('/'),
        ];
    }
}
