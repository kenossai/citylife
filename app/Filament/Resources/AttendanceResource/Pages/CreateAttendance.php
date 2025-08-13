<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pre-fill the enrollment if coming from CourseEnrollment action
        if (request()->has('course_enrollment_id')) {
            $data['course_enrollment_id'] = request('course_enrollment_id');
        }

        return $data;
    }

    public function mount(): void
    {
        parent::mount();

        // Pre-fill form if enrollment ID is provided
        if (request()->has('course_enrollment_id')) {
            $this->form->fill([
                'course_enrollment_id' => request('course_enrollment_id'),
                'attendance_date' => now()->format('Y-m-d'),
                'attended' => true,
            ]);
        }
    }
}
