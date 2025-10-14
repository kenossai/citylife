<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BabyDedicationResource\Pages;
use App\Models\BabyDedication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class BabyDedicationResource extends Resource
{
    protected static ?string $model = BabyDedication::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Baby Dedications';

    protected static ?string $navigationGroup = 'Pastoral Care';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Baby Information')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('baby_first_name')
                                    ->label('First Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('baby_middle_name')
                                    ->label('Middle Name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('baby_last_name')
                                    ->label('Last Name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('baby_date_of_birth')
                                    ->label('Date of Birth')
                                    ->required(),
                                Forms\Components\Select::make('baby_gender')
                                    ->label('Gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('baby_place_of_birth')
                                    ->label('Place of Birth')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('baby_special_notes')
                            ->label('Special Notes')
                            ->rows(2),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Parents Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make('Father')
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('father_first_name')
                                                            ->label('First Name')
                                                            ->required(),
                                                        Forms\Components\TextInput::make('father_last_name')
                                                            ->label('Last Name')
                                                            ->required(),
                                                    ]),
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('father_email')
                                                            ->label('Email')
                                                            ->email()
                                                            ->required(),
                                                        Forms\Components\TextInput::make('father_phone')
                                                            ->label('Phone')
                                                            ->required(),
                                                    ]),
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\Toggle::make('father_is_member')
                                                            ->label('Is Member'),
                                                        Forms\Components\TextInput::make('father_membership_number')
                                                            ->label('Membership Number')
                                                            ->visible(fn (Forms\Get $get) => $get('father_is_member')),
                                                    ]),
                                            ])
                                    ]),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make('Mother')
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('mother_first_name')
                                                            ->label('First Name')
                                                            ->required(),
                                                        Forms\Components\TextInput::make('mother_last_name')
                                                            ->label('Last Name')
                                                            ->required(),
                                                    ]),
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('mother_email')
                                                            ->label('Email')
                                                            ->email()
                                                            ->required(),
                                                        Forms\Components\TextInput::make('mother_phone')
                                                            ->label('Phone')
                                                            ->required(),
                                                    ]),
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\Toggle::make('mother_is_member')
                                                            ->label('Is Member'),
                                                        Forms\Components\TextInput::make('mother_membership_number')
                                                            ->label('Membership Number')
                                                            ->visible(fn (Forms\Get $get) => $get('mother_is_member')),
                                                    ]),
                                            ])
                                    ]),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Address & Contact')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->rows(2),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->required(),
                                Forms\Components\TextInput::make('postal_code')
                                    ->required(),
                                Forms\Components\TextInput::make('country')
                                    ->required()
                                    ->default('United Kingdom'),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('emergency_contact_name')
                                    ->label('Emergency Contact Name')
                                    ->required(),
                                Forms\Components\TextInput::make('emergency_contact_relationship')
                                    ->label('Relationship')
                                    ->required(),
                                Forms\Components\TextInput::make('emergency_contact_phone')
                                    ->label('Emergency Phone')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Dedication Preferences')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('preferred_dedication_date')
                                    ->label('Preferred Date'),
                                Forms\Components\Select::make('preferred_service')
                                    ->label('Preferred Service')
                                    ->options([
                                        'morning' => 'Morning Service',
                                        'evening' => 'Evening Service',
                                        'either' => 'Either Service',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\Textarea::make('special_requests')
                            ->label('Special Requests')
                            ->rows(2),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('photography_consent')
                                    ->label('Photography Consent')
                                    ->default(true),
                                Forms\Components\Toggle::make('video_consent')
                                    ->label('Video Consent')
                                    ->default(true),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Church Information')
                    ->schema([
                        Forms\Components\Toggle::make('regular_attendees')
                            ->label('Regular Attendees'),
                        Forms\Components\TextInput::make('how_long_attending')
                            ->label('How Long Attending')
                            ->visible(fn (Forms\Get $get) => $get('regular_attendees')),
                        Forms\Components\TextInput::make('previous_church')
                            ->label('Previous Church'),
                        Forms\Components\Toggle::make('baptized_parents')
                            ->label('Both Parents Baptized'),
                        Forms\Components\Textarea::make('faith_commitment')
                            ->label('Faith Commitment Statement')
                            ->rows(3),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Administration')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options(BabyDedication::getStatuses())
                                    ->required()
                                    ->default('pending'),
                                Forms\Components\DatePicker::make('scheduled_date')
                                    ->label('Scheduled Date')
                                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['scheduled', 'completed'])),
                                Forms\Components\Select::make('scheduled_service')
                                    ->label('Scheduled Service')
                                    ->options([
                                        'morning' => 'Morning Service',
                                        'evening' => 'Evening Service',
                                    ])
                                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['scheduled', 'completed'])),
                            ]),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('baby_full_name')
                    ->label('Baby Name')
                    ->searchable(['baby_first_name', 'baby_last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('father_full_name')
                    ->label('Father')
                    ->searchable(['father_first_name', 'father_last_name']),
                Tables\Columns\TextColumn::make('mother_full_name')
                    ->label('Mother')
                    ->searchable(['mother_first_name', 'mother_last_name']),
                Tables\Columns\TextColumn::make('baby_date_of_birth')
                    ->label('Baby DOB')
                    ->date('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('baby_age')
                    ->label('Age'),
                Tables\Columns\TextColumn::make('preferred_service')
                    ->label('Service Preference')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'morning' => 'Morning',
                        'evening' => 'Evening',
                        'either' => 'Either',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'primary' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Scheduled')
                    ->date('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->date('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(BabyDedication::getStatuses()),
                SelectFilter::make('preferred_service')
                    ->options([
                        'morning' => 'Morning Service',
                        'evening' => 'Evening Service',
                        'either' => 'Either Service',
                    ]),
                Tables\Filters\Filter::make('regular_attendees')
                    ->query(fn (Builder $query): Builder => $query->where('regular_attendees', true))
                    ->label('Regular Attendees Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (BabyDedication $record) => $record->status === 'pending')
                    ->action(function (BabyDedication $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Baby dedication approved')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('schedule')
                    ->icon('heroicon-o-calendar')
                    ->color('primary')
                    ->visible(fn (BabyDedication $record) => $record->status === 'approved')
                    ->form([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required()
                            ->label('Scheduled Date'),
                        Forms\Components\Select::make('scheduled_service')
                            ->options([
                                'morning' => 'Morning Service',
                                'evening' => 'Evening Service',
                            ])
                            ->required()
                            ->label('Service'),
                    ])
                    ->action(function (BabyDedication $record, array $data) {
                        $record->update([
                            'status' => 'scheduled',
                            'scheduled_date' => $data['scheduled_date'],
                            'scheduled_service' => $data['scheduled_service'],
                        ]);
                        Notification::make()
                            ->title('Baby dedication scheduled')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBabyDedications::route('/'),
            'create' => Pages\CreateBabyDedication::route('/create'),
            'view' => Pages\ViewBabyDedication::route('/{record}'),
            'edit' => Pages\EditBabyDedication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
