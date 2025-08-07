<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseEnrollmentResource\Pages;
use App\Filament\Resources\CourseEnrollmentResource\RelationManagers;
use App\Models\CourseEnrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseEnrollmentResource extends Resource
{
    protected static ?string $model = CourseEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('course_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('enrollment_date')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('active'),
                Forms\Components\TextInput::make('progress_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('completed_lessons')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('overall_grade')
                    ->numeric(),
                Forms\Components\DatePicker::make('completion_date'),
                Forms\Components\Toggle::make('certificate_issued')
                    ->required(),
                Forms\Components\TextInput::make('certificate_number')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('payment_info'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_lessons')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_grade')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('certificate_issued')
                    ->boolean(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCourseEnrollments::route('/'),
            'create' => Pages\CreateCourseEnrollment::route('/create'),
            'edit' => Pages\EditCourseEnrollment::route('/{record}/edit'),
        ];
    }
}
