<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreacherDepartmentMemberResource\Pages;
use App\Filament\Resources\PreacherDepartmentMemberResource\RelationManagers;
use App\Models\PreacherDepartmentMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PreacherDepartmentMemberResource extends Resource
{
    protected static ?string $model = PreacherDepartmentMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Unit Management';

    protected static ?int $navigationSort = 6;

    protected static ?string $label = 'Preacher Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('preacher_department_id')
                    ->relationship('preacherDepartment', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('member_id')
                    ->relationship('member', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('role')
                    ->maxLength(255)
                    ->placeholder('e.g., Lead Pastor, Youth Pastor, Bible Teacher'),

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

                Tables\Columns\TextColumn::make('preacherDepartment.name')
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
                Tables\Filters\SelectFilter::make('preacher_department_id')
                    ->relationship('preacherDepartment', 'name')
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
            'index' => Pages\ListPreacherDepartmentMembers::route('/'),
            'create' => Pages\CreatePreacherDepartmentMember::route('/create'),
            'edit' => Pages\EditPreacherDepartmentMember::route('/{record}/edit'),
        ];
    }
}
