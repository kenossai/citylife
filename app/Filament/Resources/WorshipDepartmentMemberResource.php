<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorshipDepartmentMemberResource\Pages;
use App\Filament\Resources\WorshipDepartmentMemberResource\RelationManagers;
use App\Models\WorshipDepartmentMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorshipDepartmentMemberResource extends Resource
{
    protected static ?string $model = WorshipDepartmentMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Unit Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Worship Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('worship_department_id')
                    ->relationship('worshipDepartment', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('member_id')
                    ->label('Church Member')
                    ->relationship('member', 'name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                    ->required()
                    ->helperText('Select a church member to add to this worship department'),

                Forms\Components\Select::make('role')
                    ->options(function () {
                        return \App\Models\DepRole::active()
                            ->forDepartment('worship')
                            ->pluck('name', 'name');
                    })
                    ->searchable()
                    ->placeholder('Select a worship role')
                    ->helperText('Choose a role for this worship team member'),

                Forms\Components\Textarea::make('skills')
                    ->label('Skills & Abilities')
                    ->placeholder('List musical skills, instruments, or abilities')
                    ->helperText('Describe specific skills, instruments played, or abilities this member brings'),

                Forms\Components\DatePicker::make('joined_date'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),

                Forms\Components\Toggle::make('is_head')
                    ->label('Department Head'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('member.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('worshipDepartment.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('joined_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_head')
                    ->label('Head')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('worship_department_id')
                    ->relationship('worshipDepartment', 'name')
                    ->label('Department'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_head')
                    ->label('Department Head')
                    ->boolean()
                    ->trueLabel('Heads only')
                    ->falseLabel('Non-heads only')
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
            'index' => Pages\ListWorshipDepartmentMembers::route('/'),
            'create' => Pages\CreateWorshipDepartmentMember::route('/create'),
            'edit' => Pages\EditWorshipDepartmentMember::route('/{record}/edit'),
        ];
    }
}
