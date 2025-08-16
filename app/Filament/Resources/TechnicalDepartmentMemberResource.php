<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalDepartmentMemberResource\Pages;
use App\Filament\Resources\TechnicalDepartmentMemberResource\RelationManagers;
use App\Models\TechnicalDepartmentMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnicalDepartmentMemberResource extends Resource
{
    protected static ?string $model = TechnicalDepartmentMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Unit Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Technical Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Member Assignment')
                    ->schema([
                        Forms\Components\Select::make('technical_department_id')
                            ->label('Department')
                            ->relationship('technicalDepartment', 'name')
                            ->required()
                            ->helperText('Select the technical department'),

                        Forms\Components\Select::make('member_id')
                            ->label('Church Member')
                            ->relationship('member', 'name')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->required()
                            ->helperText('Select a church member to add to this technical department'),

                        Forms\Components\TextInput::make('role')
                            ->maxLength(255)
                            ->helperText('e.g., Sound Engineer, Camera Operator, Graphics Designer'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Settings')
                    ->schema([
                        Forms\Components\DatePicker::make('joined_date')
                            ->helperText('Date when the member joined'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Whether this member is active'),

                        Forms\Components\Toggle::make('is_head')
                            ->label('Department Head')
                            ->helperText('Is this member the head of the department?'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('member.profile_picture')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/assets/images/default-profile.jpg')),

                Tables\Columns\TextColumn::make('member.name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('technicalDepartment.name')
                    ->label('Department')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PA' => 'primary',
                        'Media' => 'success',
                        'Visual' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->searchable()
                    ->placeholder('No role specified'),


                Tables\Columns\IconColumn::make('is_head')
                    ->label('Head')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('joined_date')
                    ->date()
                    ->sortable()
                    ->placeholder('Not specified'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('technical_department_id')
                    ->label('Department')
                    ->relationship('technicalDepartment', 'name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active members')
                    ->falseLabel('Inactive members')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_head')
                    ->label('Department Head')
                    ->boolean()
                    ->trueLabel('Department heads')
                    ->falseLabel('Regular members')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTechnicalDepartmentMembers::route('/'),
            'create' => Pages\CreateTechnicalDepartmentMember::route('/create'),
            'edit' => Pages\EditTechnicalDepartmentMember::route('/{record}/edit'),
        ];
    }
}
