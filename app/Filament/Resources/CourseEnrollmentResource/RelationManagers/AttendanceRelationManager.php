<?php

namespace App\Filament\Resources\CourseEnrollmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\CourseLesson;

class AttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendance';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_lesson_id')
                    ->label('Lesson')
                    ->options(function () {
                        $enrollment = $this->ownerRecord;
                        return CourseLesson::where('course_id', $enrollment->course_id)
                            ->orderBy('lesson_number')
                            ->pluck('title', 'id');
                    })
                    ->required()
                    ->searchable(),
                    
                Forms\Components\Toggle::make('attended')
                    ->label('Attended')
                    ->default(false),
                    
                Forms\Components\DatePicker::make('attendance_date')
                    ->label('Attendance Date')
                    ->default(now())
                    ->required(),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(2)
                    ->columnSpanFull(),
                    
                Forms\Components\Hidden::make('marked_by')
                    ->default(function () {
                        return filament()->auth()->user()?->id;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('lesson.title')
            ->columns([
                Tables\Columns\TextColumn::make('lesson.title')
                    ->label('Lesson')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('lesson.lesson_number')
                    ->label('Lesson #')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('attended')
                    ->label('Attended')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('attendance_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attended')
                    ->options([
                        1 => 'Attended',
                        0 => 'Not Attended',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('lesson.lesson_number');
    }
}
