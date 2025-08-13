<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\LessonAttendance;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Mark Attendance'),
            Actions\Action::make('bulk_attendance')
                ->label('Bulk Attendance by Lesson')
                ->icon('heroicon-o-users')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\Select::make('course_id')
                        ->label('Course')
                        ->options(\App\Models\Course::pluck('title', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('lesson_id', null)),

                    \Filament\Forms\Components\Select::make('lesson_id')
                        ->label('Lesson')
                        ->options(function (callable $get) {
                            $courseId = $get('course_id');
                            if (!$courseId) return [];
                            return \App\Models\CourseLesson::where('course_id', $courseId)
                                ->orderBy('lesson_number')
                                ->pluck('title', 'id');
                        })
                        ->required(),

                    \Filament\Forms\Components\DatePicker::make('attendance_date')
                        ->label('Attendance Date')
                        ->default(now())
                        ->required(),

                    \Filament\Forms\Components\Toggle::make('mark_all_present')
                        ->label('Mark all students as present by default')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $enrollments = \App\Models\CourseEnrollment::where('course_id', $data['course_id'])
                        ->where('status', 'active')
                        ->get();

                    $created = 0;
                    foreach ($enrollments as $enrollment) {
                        // Check if attendance already exists for this student and lesson
                        $exists = \App\Models\LessonAttendance::where('course_enrollment_id', $enrollment->id)
                            ->where('course_lesson_id', $data['lesson_id'])
                            ->where('attendance_date', $data['attendance_date'])
                            ->exists();

                        if (!$exists) {
                            \App\Models\LessonAttendance::create([
                                'course_enrollment_id' => $enrollment->id,
                                'course_lesson_id' => $data['lesson_id'],
                                'attended' => $data['mark_all_present'],
                                'attendance_date' => $data['attendance_date'],
                                'marked_by' => filament()->auth()->user()?->id,
                                'notes' => 'Bulk created',
                            ]);
                            $created++;
                        }
                    }

                    \Filament\Notifications\Notification::make()
                        ->title('Bulk Attendance Created')
                        ->body("Created attendance records for {$created} students.")
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Attendance')
                ->badge(LessonAttendance::count()),
            'present' => Tab::make('Present')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('attended', true))
                ->badge(LessonAttendance::where('attended', true)->count())
                ->badgeColor('success'),
            'absent' => Tab::make('Absent')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('attended', false))
                ->badge(LessonAttendance::where('attended', false)->count())
                ->badgeColor('danger'),
            'today' => Tab::make('Today')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('attendance_date', today()))
                ->badge(LessonAttendance::whereDate('attendance_date', today())->count())
                ->badgeColor('primary'),
        ];
    }
}
