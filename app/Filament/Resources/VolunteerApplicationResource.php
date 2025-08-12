<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerApplicationResource\Pages;
use App\Filament\Resources\VolunteerApplicationResource\RelationManagers;
use App\Models\VolunteerApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class VolunteerApplicationResource extends Resource
{
    protected static ?string $model = VolunteerApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Volunteer Applications';

    protected static ?string $modelLabel = 'Volunteer Application';

    protected static ?string $navigationGroup = 'Volunteer Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Application Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Application Info')
                            ->schema([
                                Forms\Components\Select::make('application_type')
                                    ->label('Application Type')
                                    ->options([
                                        'event_only' => 'For specific events only',
                                        'ongoing' => 'To join the team on an ongoing basis',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('team')
                                    ->label('Team')
                                    ->options([
                                        'stewarding' => 'Stewarding Team',
                                        'worship' => 'Worship Team',
                                        'technical' => 'Technical Team',
                                        'children' => 'Children\'s Ministry',
                                        'hospitality' => 'Hospitality Team',
                                        'prayer' => 'Prayer Team',
                                        'media' => 'Media Team',
                                        'facilities' => 'Facilities Team',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label('Application Status')
                                    ->options([
                                        'pending' => 'Pending Review',
                                        'under_review' => 'Under Review',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Admin Notes')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Personal Details')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->required(),

                                Forms\Components\Select::make('sex')
                                    ->label('Sex')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'prefer_not_to_say' => 'Prefer not to say',
                                    ]),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('mobile')
                                    ->label('Mobile')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('address')
                                    ->label('Address')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Background & Medical')
                            ->schema([
                                Forms\Components\Toggle::make('medical_professional')
                                    ->label('Qualified Medical Professional')
                                    ->helperText('Are you a qualified medical professional (e.g., doctor, nurse, paramedic)?'),

                                Forms\Components\Toggle::make('first_aid_certificate')
                                    ->label('Valid UK First Aid Certificate')
                                    ->helperText('Do you hold a valid UK first aid certificate?'),

                                Forms\Components\Toggle::make('eligible_to_work')
                                    ->label('Eligible to Work in UK')
                                    ->required(),

                                Forms\Components\Textarea::make('church_background')
                                    ->label('Church Background')
                                    ->helperText('Which church do you attend? How long have you been there and what ministry are you involved in?')
                                    ->required()
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('employment_details')
                                    ->label('Employment Details')
                                    ->helperText('Brief details of your current employment or other involvements/charity work')
                                    ->required()
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('support_mission')
                                    ->label('Mission Support')
                                    ->helperText('How would you be able to support our mission and spiritual platform?')
                                    ->required()
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Emergency Contact')
                            ->schema([
                                Forms\Components\TextInput::make('emergency_contact_name')
                                    ->label('Emergency Contact Name')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('emergency_contact_relationship')
                                    ->label('Relationship')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('emergency_contact_phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Tabs\Tab::make('Consents')
                            ->schema([
                                Forms\Components\Toggle::make('data_processing_consent')
                                    ->label('Data Processing Consent')
                                    ->helperText('Consent to data processing')
                                    ->required(),

                                Forms\Components\Toggle::make('data_protection_consent')
                                    ->label('Data Protection Consent')
                                    ->helperText('Consent to data protection policy')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('team')
                    ->label('Team')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'stewarding' => 'Stewarding',
                        'worship' => 'Worship',
                        'technical' => 'Technical',
                        'children' => 'Children\'s Ministry',
                        'hospitality' => 'Hospitality',
                        'prayer' => 'Prayer',
                        'media' => 'Media',
                        'facilities' => 'Facilities',
                        default => $state,
                    })
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('application_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'event_only' => 'Events Only',
                        'ongoing' => 'Ongoing',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'event_only' => 'warning',
                        'ongoing' => 'success',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'Pending',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'under_review' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->getStateUsing(fn (VolunteerApplication $record): ?int => $record->age)
                    ->sortable(),

                Tables\Columns\IconColumn::make('medical_professional')
                    ->label('Medical Pro')
                    ->boolean()
                    ->tooltip('Qualified Medical Professional'),

                Tables\Columns\IconColumn::make('first_aid_certificate')
                    ->label('First Aid')
                    ->boolean()
                    ->tooltip('Valid UK First Aid Certificate'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('team')
                    ->options([
                        'stewarding' => 'Stewarding Team',
                        'worship' => 'Worship Team',
                        'technical' => 'Technical Team',
                        'children' => 'Children\'s Ministry',
                        'hospitality' => 'Hospitality Team',
                        'prayer' => 'Prayer Team',
                        'media' => 'Media Team',
                        'facilities' => 'Facilities Team',
                    ]),

                SelectFilter::make('application_type')
                    ->options([
                        'event_only' => 'For specific events only',
                        'ongoing' => 'To join the team on an ongoing basis',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (VolunteerApplication $record) => $record->update(['status' => 'approved']))
                    ->requiresConfirmation()
                    ->visible(fn (VolunteerApplication $record): bool => $record->status !== 'approved'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (VolunteerApplication $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation()
                    ->visible(fn (VolunteerApplication $record): bool => $record->status !== 'rejected'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make('Application Details')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Overview')
                            ->schema([
                                Infolists\Components\Section::make('Application Summary')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->label('Full Name'),
                                        Infolists\Components\TextEntry::make('email')
                                            ->label('Email')
                                            ->copyable(),
                                        Infolists\Components\TextEntry::make('mobile')
                                            ->label('Mobile')
                                            ->copyable(),
                                        Infolists\Components\TextEntry::make('formatted_team')
                                            ->label('Team Applied For'),
                                        Infolists\Components\TextEntry::make('formatted_application_type')
                                            ->label('Application Type'),
                                        Infolists\Components\TextEntry::make('formatted_status')
                                            ->label('Status')
                                            ->badge(),
                                        Infolists\Components\TextEntry::make('age')
                                            ->label('Age'),
                                    ])
                                    ->columns(2),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Personal Details')
                            ->schema([
                                Infolists\Components\Section::make('Personal Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('date_of_birth')
                                            ->label('Date of Birth')
                                            ->date(),
                                        Infolists\Components\TextEntry::make('sex')
                                            ->label('Sex'),
                                        Infolists\Components\TextEntry::make('address')
                                            ->label('Address')
                                            ->columnSpanFull(),
                                    ]),

                                Infolists\Components\Section::make('Medical & First Aid')
                                    ->schema([
                                        Infolists\Components\IconEntry::make('medical_professional')
                                            ->label('Qualified Medical Professional')
                                            ->boolean(),
                                        Infolists\Components\IconEntry::make('first_aid_certificate')
                                            ->label('Valid UK First Aid Certificate')
                                            ->boolean(),
                                        Infolists\Components\IconEntry::make('eligible_to_work')
                                            ->label('Eligible to Work in UK')
                                            ->boolean(),
                                    ])
                                    ->columns(3),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Background')
                            ->schema([
                                Infolists\Components\Section::make('Background Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('church_background')
                                            ->label('Church Background')
                                            ->columnSpanFull(),
                                        Infolists\Components\TextEntry::make('employment_details')
                                            ->label('Employment Details')
                                            ->columnSpanFull(),
                                        Infolists\Components\TextEntry::make('support_mission')
                                            ->label('Mission Support')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Emergency Contact')
                            ->schema([
                                Infolists\Components\Section::make('Emergency Contact Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('emergency_contact_name')
                                            ->label('Name'),
                                        Infolists\Components\TextEntry::make('emergency_contact_relationship')
                                            ->label('Relationship'),
                                        Infolists\Components\TextEntry::make('emergency_contact_phone')
                                            ->label('Phone Number')
                                            ->copyable(),
                                    ])
                                    ->columns(2),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Admin')
                            ->schema([
                                Infolists\Components\Section::make('Application Tracking')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('created_at')
                                            ->label('Applied On')
                                            ->dateTime(),
                                        Infolists\Components\TextEntry::make('updated_at')
                                            ->label('Last Updated')
                                            ->dateTime(),
                                        Infolists\Components\TextEntry::make('notes')
                                            ->label('Admin Notes')
                                            ->columnSpanFull(),
                                    ]),

                                Infolists\Components\Section::make('Consent Information')
                                    ->schema([
                                        Infolists\Components\IconEntry::make('data_processing_consent')
                                            ->label('Data Processing Consent')
                                            ->boolean(),
                                        Infolists\Components\IconEntry::make('data_protection_consent')
                                            ->label('Data Protection Consent')
                                            ->boolean(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
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
            'index' => Pages\ListVolunteerApplications::route('/'),
            'create' => Pages\CreateVolunteerApplication::route('/create'),
            'edit' => Pages\EditVolunteerApplication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0 ? 'warning' : 'primary';
    }
}
