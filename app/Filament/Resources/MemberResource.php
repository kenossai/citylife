<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Members';

    protected static ?string $pluralModelLabel = 'Members';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('title')
                                    ->options([
                                        'Mr' => 'Mr',
                                        'Mrs' => 'Mrs',
                                        'Miss' => 'Miss',
                                        'Ms' => 'Ms',
                                        'Dr' => 'Dr',
                                        'Rev' => 'Rev',
                                        'Prof' => 'Prof',
                                    ]),
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('date_of_birth'),
                                Forms\Components\Select::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                    ]),
                                Forms\Components\Select::make('marital_status')
                                    ->options([
                                        'Single' => 'Single',
                                        'Married' => 'Married',
                                        'Divorced' => 'Divorced',
                                        'Widowed' => 'Widowed',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state !== 'Married') {
                                            $set('spouse_is_member', false);
                                            $set('spouse_member_id', null);
                                        }
                                    }),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('spouse_is_member')
                                    ->label('Is spouse a member?')
                                    ->visible(fn (Forms\Get $get): bool => $get('marital_status') === 'Married')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if (!$state) {
                                            $set('spouse_member_id', null);
                                        }
                                    }),
                                Forms\Components\Select::make('spouse_member_id')
                                    ->label('Select Spouse')
                                    ->visible(fn (Forms\Get $get): bool => $get('marital_status') === 'Married' && $get('spouse_is_member'))
                                    ->options(function (Forms\Get $get, ?Model $record) {
                                        $currentId = $record?->id;
                                        return Member::when($currentId, function ($query) use ($currentId) {
                                                return $query->where('id', '!=', $currentId);
                                            })
                                            ->whereNotNull('first_name')
                                            ->whereNotNull('last_name')
                                            ->get()
                                            ->mapWithKeys(function ($member) {
                                                $name = trim($member->title . ' ' . $member->first_name . ' ' . $member->last_name);
                                                return [$member->id => $name . ' (' . $member->membership_number . ')'];
                                            });
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select spouse from members'),
                            ]),
                        Forms\Components\TextInput::make('occupation')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Address Information')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->maxLength(500),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('postal_code')
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('country')
                                    ->maxLength(255)
                                    ->default('United Kingdom'),
                            ]),
                    ]),

                Forms\Components\Section::make('Church Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('membership_status')
                                    ->required()
                                    ->options([
                                        'visitor' => 'Visitor',
                                        'regular_attendee' => 'Regular Attendee',
                                        'member' => 'Member',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('visitor'),
                                Forms\Components\DatePicker::make('first_visit_date')
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('membership_date'),
                                Forms\Components\Select::make('baptism_status')
                                    ->options([
                                        'Not Baptized' => 'Not Baptized',
                                        'Baptized' => 'Baptized',
                                        'Baptized Elsewhere' => 'Baptized Elsewhere',
                                    ]),
                            ]),
                        Forms\Components\DatePicker::make('baptism_date'),
                    ]),

                Forms\Components\Section::make('Emergency Contact')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('emergency_contact_name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('emergency_contact_phone')
                                    ->tel()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('emergency_contact_relationship')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(1000),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
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
                    ->getStateUsing(fn($record) => trim($record->title . ' ' . $record->first_name . ' ' . $record->last_name))
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('membership_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inactive' => 'danger',
                        'visitor' => 'warning', 
                        'member' => 'success',
                        'regular_attendee' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('spouse.first_name')
                    ->label('Spouse')
                    ->getStateUsing(function ($record) {
                        if ($record->spouse_is_member && $record->spouse) {
                            return trim($record->spouse->title . ' ' . $record->spouse->first_name . ' ' . $record->spouse->last_name);
                        }
                        return $record->marital_status === 'Married' && !$record->spouse_is_member ? 'Non-member' : '-';
                    })
                    ->searchable(false)
                    ->sortable(false),
                Tables\Columns\TextColumn::make('first_visit_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('membership_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('spouse_is_member')
                    ->label('Spouse Member')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('membership_status')
                    ->options([
                        'visitor' => 'Visitor',
                        'regular_attendee' => 'Regular Attendee',
                        'member' => 'Member',
                        'inactive' => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('baptism_status')
                    ->options([
                        'Not Baptized' => 'Not Baptized',
                        'Baptized' => 'Baptized',
                        'Baptized Elsewhere' => 'Baptized Elsewhere',
                    ]),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Active Members Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
