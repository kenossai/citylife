<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YouthCampingRegistrationResource\Pages;
use App\Models\YouthCamping;
use App\Models\YouthCampingRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class YouthCampingRegistrationResource extends Resource
{
    protected static ?string $model = YouthCampingRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Youth Ministry';

    protected static ?string $navigationLabel = 'Camping Registrations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Camping Information')
                    ->schema([
                        Forms\Components\Select::make('youth_camping_id')
                            ->label('Youth Camping')
                            ->relationship('youthCamping', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Forms\Components\Section::make('Child Information')
                    ->schema([
                        Forms\Components\TextInput::make('child_first_name')
                            ->label('Child First Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('child_last_name')
                            ->label('Child Last Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('child_date_of_birth')
                            ->label('Child Date of Birth')
                            ->required()
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    $age = now()->diffInYears($state);
                                    $set('child_age', $age);
                                }
                            }),

                        Forms\Components\TextInput::make('child_age')
                            ->label('Child Age')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated(),

                        Forms\Components\Select::make('child_gender')
                            ->label('Child Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ]),

                        Forms\Components\TextInput::make('child_grade_school')
                            ->label('Grade/School')
                            ->maxLength(255),

                        Forms\Components\Select::make('child_t_shirt_size')
                            ->label('T-Shirt Size')
                            ->options([
                                'XS' => 'Extra Small',
                                'S' => 'Small',
                                'M' => 'Medium',
                                'L' => 'Large',
                                'XL' => 'Extra Large',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Parent/Guardian Information')
                    ->schema([
                        Forms\Components\TextInput::make('parent_first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('parent_last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('parent_email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('parent_phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('parent_relationship')
                            ->label('Relationship to Child')
                            ->options([
                                'mother' => 'Mother',
                                'father' => 'Father',
                                'guardian' => 'Guardian',
                                'other' => 'Other',
                            ])
                            ->default('mother')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Contact & Address Information')
                    ->schema([
                        Forms\Components\TextInput::make('home_address')
                            ->label('Home Address')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('home_phone')
                            ->label('Home Phone')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('work_phone')
                            ->label('Work Phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Emergency Contact')
                    ->schema([
                        Forms\Components\TextInput::make('emergency_contact_name')
                            ->label('Emergency Contact Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('emergency_contact_relationship')
                            ->label('Relationship to Child')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Medical Information')
                    ->schema([
                        Forms\Components\TagsInput::make('medical_conditions')
                            ->label('Medical Conditions')
                            ->placeholder('Add medical conditions'),

                        Forms\Components\TagsInput::make('medications')
                            ->label('Current Medications')
                            ->placeholder('Add medications'),

                        Forms\Components\TagsInput::make('allergies')
                            ->label('Allergies')
                            ->placeholder('Add allergies'),

                        Forms\Components\TagsInput::make('dietary_requirements')
                            ->label('Dietary Requirements')
                            ->placeholder('Add dietary requirements'),

                        Forms\Components\Select::make('swimming_ability')
                            ->label('Swimming Ability')
                            ->options([
                                'non_swimmer' => 'Non-swimmer',
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ]),

                        Forms\Components\TextInput::make('doctor_name')
                            ->label('Doctor Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('doctor_phone')
                            ->label('Doctor Phone')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('health_card_number')
                            ->label('Health Card Number')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Consent & Permissions')
                    ->schema([
                        Forms\Components\Checkbox::make('consent_photo_video')
                            ->label('Photo/Video Consent')
                            ->helperText('I consent to my child being photographed/videoed during camp activities'),

                        Forms\Components\Checkbox::make('consent_medical_treatment')
                            ->label('Medical Treatment Consent')
                            ->helperText('I consent to emergency medical treatment if required'),

                        Forms\Components\Checkbox::make('consent_activities')
                            ->label('Activities Consent')
                            ->helperText('I consent to my child participating in all camp activities'),

                        Forms\Components\Checkbox::make('consent_pickup_authorized_persons')
                            ->label('Pickup Authorization')
                            ->helperText('I authorize only the persons listed below to pick up my child')
                            ->live(),

                        Forms\Components\TagsInput::make('pickup_authorized_persons')
                            ->label('Authorized Pickup Persons')
                            ->placeholder('Add authorized person names')
                            ->visible(fn (Forms\Get $get) => $get('consent_pickup_authorized_persons')),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('special_needs')
                            ->label('Special Needs')
                            ->rows(3),

                        Forms\Components\Textarea::make('additional_notes')
                            ->label('Additional Notes')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Registration Management')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                'waitlist' => 'Waitlist',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\TextInput::make('payment_amount')
                            ->label('Payment Amount')
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\TextInput::make('payment_method')
                            ->label('Payment Method')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('payment_reference')
                            ->label('Payment Reference')
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('registration_date')
                            ->label('Registration Date')
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('confirmation_sent_at')
                            ->label('Confirmation Sent At'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('youthCamping.name')
                    ->label('Camping')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('child_full_name')
                    ->label('Child Name')
                    ->searchable(['child_first_name', 'child_last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('child_calculated_age')
                    ->label('Age')
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent_full_name')
                    ->label('Parent/Guardian')
                    ->searchable(['parent_first_name', 'parent_last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('parent_phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                        'info' => 'waitlist',
                    ]),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'partial',
                        'success' => 'paid',
                        'danger' => 'refunded',
                    ]),

                Tables\Columns\TextColumn::make('registration_date')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('has_all_consents')
                    ->label('Consents')
                    ->boolean()
                    ->tooltip('All required consents given'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('youth_camping_id')
                    ->label('Camping')
                    ->relationship('youthCamping', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'waitlist' => 'Waitlist',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ]),

                Tables\Filters\Filter::make('has_all_consents')
                    ->label('All Consents Given')
                    ->query(fn (Builder $query): Builder => $query->where('consent_photo_video', true)
                        ->where('consent_medical_treatment', true)
                        ->where('consent_activities', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (YouthCampingRegistration $record) => $record->confirm())
                    ->visible(fn (YouthCampingRegistration $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (YouthCampingRegistration $record) => $record->markPaid())
                    ->visible(fn (YouthCampingRegistration $record) => $record->payment_status !== 'paid'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('confirm_selected')
                        ->label('Confirm Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->confirm();
                            }
                        }),
                ]),
            ])
            ->defaultSort('registration_date', 'desc');
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
            'index' => Pages\ListYouthCampingRegistrations::route('/'),
            'create' => Pages\CreateYouthCampingRegistration::route('/create'),
            'edit' => Pages\EditYouthCampingRegistration::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['youthCamping']);
    }
}
