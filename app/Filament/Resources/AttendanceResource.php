<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\LessonAttendance;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\Member;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = LessonAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Education & Training';

    protected static ?string $navigationLabel = 'Attendance';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_enrollment_id')
                    ->label('Student Enrollment')
                    ->options(function () {
                        return CourseEnrollment::with(['course', 'user'])
                            ->get()
                            ->mapWithKeys(function ($enrollment) {
                                $studentName = $enrollment->user->first_name . ' ' . $enrollment->user->last_name;
                                $courseName = $enrollment->course->title;
                                return [$enrollment->id => "{$studentName} - {$courseName}"];
                            });
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->disabled(fn ($livewire) =>
                        $livewire instanceof \App\Filament\Resources\AttendanceResource\Pages\CreateAttendance &&
                        request()->has('course_enrollment_id')
                    )
                    ->afterStateUpdated(fn ($state, callable $set) => $set('course_lesson_id', null)),

                Forms\Components\Select::make('course_lesson_id')
                    ->label('Lesson')
                    ->options(function (callable $get) {
                        $enrollmentId = $get('course_enrollment_id');
                        if (!$enrollmentId) return [];

                        $enrollment = CourseEnrollment::find($enrollmentId);
                        if (!$enrollment) return [];

                        return CourseLesson::where('course_id', $enrollment->course_id)
                            ->orderBy('lesson_number')
                            ->pluck('title', 'id');
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\Toggle::make('attended')
                    ->label('Attended')
                    ->default(true),

                Forms\Components\DatePicker::make('attendance_date')
                    ->label('Attendance Date')
                    ->default(now())
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('marked_by')
                    ->default(function () {
                        return filament()->auth()->user()?->id;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enrollment.user.full_name')
                    ->label('Student')
                    ->getStateUsing(function ($record) {
                        $user = $record->enrollment->user;
                        return $user ? $user->first_name . ' ' . $user->last_name : 'N/A';
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollment.course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lesson.title')
                    ->label('Lesson')
                    ->searchable()
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
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Marked At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attended')
                    ->label('Attendance Status')
                    ->options([
                        1 => 'Attended',
                        0 => 'Not Attended',
                    ]),

                Tables\Filters\SelectFilter::make('course_enrollment_id')
                    ->label('Student')
                    ->options(function () {
                        return CourseEnrollment::with(['user'])
                            ->get()
                            ->mapWithKeys(function ($enrollment) {
                                $studentName = $enrollment->user->first_name . ' ' . $enrollment->user->last_name;
                                return [$enrollment->id => $studentName];
                            });
                    })
                    ->searchable(),

                Tables\Filters\SelectFilter::make('course')
                    ->label('Course')
                    ->relationship('enrollment.course', 'title')
                    ->searchable(),

                Tables\Filters\Filter::make('attendance_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('attendance_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('attendance_date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_present')
                        ->label('Mark as Present')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['attended' => true]);
                                $record->updateEnrollmentProgress();
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Attendance Updated')
                                ->body("Marked {$records->count()} students as present.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('mark_absent')
                        ->label('Mark as Absent')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['attended' => false]);
                                $record->updateEnrollmentProgress();
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Attendance Updated')
                                ->body("Marked {$records->count()} students as absent.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('attendance_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
            'view' => Pages\ViewAttendance::route('/{record}'),
        ];
    }
}
