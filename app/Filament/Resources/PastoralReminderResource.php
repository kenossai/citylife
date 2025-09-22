<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PastoralReminderResource\Pages;
use App\Models\PastoralReminder;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class PastoralReminderResource extends Resource
{
    protected static ?string $model = PastoralReminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Pastoral Care';

    protected static ?string $navigationLabel = 'Reminders';

    protected static ?string $modelLabel = 'Pastoral Reminder';

    protected static ?string $pluralModelLabel = 'Pastoral Reminders';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Member & Type')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->relationship('member', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $member = Member::find($state);
                                    if ($member) {
                                        // Auto-populate based on member data
                                        $set('notification_recipients', [
                                            'admin@citylifecc.com',
                                            'pastor@citylifecc.com'
                                        ]);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('reminder_type')
                            ->label('Reminder Type')
                            ->options(PastoralReminder::getReminderTypes())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $member = Member::find($get('member_id'));
                                if ($member && $state) {
                                    // Auto-populate dates based on type and member data
                                    match($state) {
                                        'birthday' => $set('reminder_date', $member->date_of_birth),
                                        'membership_anniversary' => $set('reminder_date', $member->membership_date),
                                        'baptism_anniversary' => $set('reminder_date', $member->baptism_date),
                                        default => null
                                    };

                                    // Set year created for anniversaries
                                    if (in_array($state, ['membership_anniversary', 'baptism_anniversary', 'wedding_anniversary'])) {
                                        $date = match($state) {
                                            'membership_anniversary' => $member->membership_date,
                                            'baptism_anniversary' => $member->baptism_date,
                                            default => null
                                        };
                                        if ($date) {
                                            $set('year_created', $date->year);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('title')
                            ->label('Custom Title')
                            ->maxLength(255)
                            ->visible(fn (callable $get) => $get('reminder_type') === 'custom')
                            ->required(fn (callable $get) => $get('reminder_type') === 'custom'),
                    ])->columns(2),

                Forms\Components\Section::make('Reminder Details')
                    ->schema([
                        Forms\Components\DatePicker::make('reminder_date')
                            ->label('Anniversary/Birthday Date')
                            ->required()
                            ->helperText('The actual date of the birthday or anniversary'),

                        Forms\Components\TextInput::make('days_before_reminder')
                            ->label('Days Before Reminder')
                            ->numeric()
                            ->default(7)
                            ->required()
                            ->minValue(0)
                            ->maxValue(365)
                            ->helperText('How many days before the date to send the reminder'),

                        Forms\Components\TextInput::make('year_created')
                            ->label('Anniversary Start Year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(now()->year)
                            ->visible(fn (callable $get) => in_array($get('reminder_type'), ['wedding_anniversary', 'baptism_anniversary', 'membership_anniversary', 'salvation_anniversary']))
                            ->helperText('The year this anniversary started (for calculating years)'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Optional description or notes about this reminder'),
                    ])->columns(2),

                Forms\Components\Section::make('Staff Notification Settings')
                    ->schema([
                        Forms\Components\TagsInput::make('notification_recipients')
                            ->label('Staff Notification Recipients')
                            ->placeholder('Enter email addresses...')
                            ->helperText('Email addresses of staff members who should receive these reminders')
                            ->default(['admin@citylifecc.com', 'pastor@citylifecc.com']),

                        Forms\Components\Textarea::make('custom_message.message')
                            ->label('Custom Staff Message Template')
                            ->rows(3)
                            ->helperText('Use placeholders: {first_name}, {last_name}, {full_name}, {date}, {years}, {years_text}')
                            ->placeholder('Happy {years_text}anniversary to {full_name} on {date}!'),
                    ])->columns(1),

                Forms\Components\Section::make('Member Notification Settings')
                    ->schema([
                        Forms\Components\Toggle::make('send_to_member')
                            ->label('Send Notification to Member')
                            ->helperText('Whether to also send a direct message to the member')
                            ->live()
                            ->default(false),

                        Forms\Components\Select::make('member_notification_type')
                            ->label('Member Notification Method')
                            ->options([
                                'email' => 'Email Only',
                                'sms' => 'SMS Only',
                                'both' => 'Email and SMS',
                            ])
                            ->default('email')
                            ->visible(fn (callable $get) => $get('send_to_member'))
                            ->required(fn (callable $get) => $get('send_to_member')),

                        Forms\Components\TextInput::make('days_before_member_notification')
                            ->label('Days Before Member Notification')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(30)
                            ->visible(fn (callable $get) => $get('send_to_member'))
                            ->helperText('How many days before the date to send to member (0 = on the day)'),

                        Forms\Components\Textarea::make('member_message_template.message')
                            ->label('Custom Member Message Template')
                            ->rows(4)
                            ->visible(fn (callable $get) => $get('send_to_member'))
                            ->helperText('Personal message for the member. Use placeholders: {first_name}, {last_name}, {full_name}, {date}, {years}, {years_text}')
                            ->placeholder('🎉 Happy Birthday, {first_name}! Wishing you a wonderful day filled with God\'s blessings. From all of us at City Life Christian Centre! 🎂'),
                    ])->columns(2),

                Forms\Components\Section::make('General Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_annual')
                            ->label('Annual Reminder')
                            ->default(true)
                            ->helperText('Whether this reminder repeats every year'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Whether this reminder is currently active'),
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

                Tables\Columns\SelectColumn::make('reminder_type')
                    ->label('Type')
                    ->options(PastoralReminder::getReminderTypes())
                    ->sortable(),

                Tables\Columns\TextColumn::make('reminder_date')
                    ->label('Date')
                    ->date('M j')
                    ->sortable(),

                Tables\Columns\TextColumn::make('notification_date')
                    ->label('Notification Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->tooltip('When the reminder will be sent'),

                Tables\Columns\TextColumn::make('years_count')
                    ->label('Years')
                    ->sortable()
                    ->placeholder('N/A'),

                Tables\Columns\IconColumn::make('is_annual')
                    ->label('Annual')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_sent_at')
                    ->label('Last Sent')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->placeholder('Never sent'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('reminder_type')
                    ->label('Reminder Type')
                    ->options(PastoralReminder::getReminderTypes()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active reminders only')
                    ->falseLabel('Inactive reminders only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_annual')
                    ->label('Annual Reminders')
                    ->boolean()
                    ->trueLabel('Annual reminders only')
                    ->falseLabel('One-time reminders only')
                    ->native(false),

                Tables\Filters\Filter::make('due_this_week')
                    ->label('Due This Week')
                    ->query(fn (Builder $query): Builder => $query->dueThisWeek()),

                Tables\Filters\Filter::make('due_today')
                    ->label('Due Today')
                    ->query(fn (Builder $query): Builder => $query->dueToday()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('send_now')
                        ->label('Send Reminder Now')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Send Reminder Now')
                        ->modalDescription('Are you sure you want to send this reminder immediately?')
                        ->action(function (PastoralReminder $record) {
                            // Create notification for immediate sending
                            \App\Models\PastoralNotification::createForReminder($record, 'email');

                            $record->update(['last_sent_at' => now()]);

                            Notification::make()
                                ->title('Reminder sent successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('view_message')
                        ->label('Preview Message')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->modalHeading('Message Preview')
                        ->modalContent(fn (PastoralReminder $record) => view('filament.modals.pastoral-reminder-preview', ['reminder' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close'),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => true]);
                            }

                            Notification::make()
                                ->title('Reminders activated successfully')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('bulk_deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }

                            Notification::make()
                                ->title('Reminders deactivated successfully')
                                ->warning()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('reminder_date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPastoralReminders::route('/'),
            'create' => Pages\CreatePastoralReminder::route('/create'),
            'edit' => Pages\EditPastoralReminder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::dueThisWeek()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getNavigationBadge();
        return $count > 0 ? 'warning' : null;
    }
}
