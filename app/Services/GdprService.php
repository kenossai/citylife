<?php

namespace App\Services;

use App\Models\Member;
use App\Models\GdprDataRequest;
use App\Models\GdprAuditLog;
use App\Models\GdprConsent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use Carbon\Carbon;

class GdprService
{
    public function exportMemberData(Member $member, array $dataTypes = []): array
    {
        if (empty($dataTypes)) {
            $dataTypes = array_keys(GdprDataRequest::getDataTypes());
        }

        $exportedData = [];
        $exportedFiles = [];

        // Log the data access
        GdprAuditLog::logAction([
            'member_id' => $member->id,
            'action' => 'data_export',
            'description' => 'Member data exported for GDPR request',
            'new_values' => ['exported_data_types' => $dataTypes],
        ]);

        foreach ($dataTypes as $dataType) {
            $exportedData[$dataType] = $this->getDataByType($member, $dataType);
        }

        // Create JSON export
        $filename = "member_data_{$member->id}_" . now()->format('Y_m_d_H_i_s') . '.json';
        $filepath = "gdpr_exports/{$filename}";

        Storage::disk('local')->put($filepath, json_encode($exportedData, JSON_PRETTY_PRINT));
        $exportedFiles[] = $filepath;

        // Create CSV exports for structured data
        foreach (['personal_info', 'attendance_records', 'giving_records'] as $csvType) {
            if (in_array($csvType, $dataTypes) && !empty($exportedData[$csvType])) {
                $csvFile = $this->createCsvExport($member, $csvType, $exportedData[$csvType]);
                if ($csvFile) {
                    $exportedFiles[] = $csvFile;
                }
            }
        }

        // Create ZIP archive
        $zipFilename = "member_data_export_{$member->id}_" . now()->format('Y_m_d_H_i_s') . '.zip';
        $zipPath = storage_path("app/gdpr_exports/{$zipFilename}");

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($exportedFiles as $file) {
                $zip->addFile(storage_path("app/{$file}"), basename($file));
            }
            $zip->close();
        }

        return [
            'exported_data' => $exportedData,
            'files' => $exportedFiles,
            'zip_file' => "gdpr_exports/{$zipFilename}",
        ];
    }

    private function getDataByType(Member $member, string $dataType): array
    {
        return match($dataType) {
            'personal_info' => [
                'membership_number' => $member->membership_number,
                'name' => [
                    'title' => $member->title,
                    'first_name' => $member->first_name,
                    'last_name' => $member->last_name,
                    'middle_name' => $member->middle_name,
                    'preferred_name' => $member->preferred_name,
                ],
                'date_of_birth' => $member->date_of_birth?->format('Y-m-d'),
                'gender' => $member->gender,
                'marital_status' => $member->marital_status,
                'membership_date' => $member->membership_date?->format('Y-m-d'),
                'baptism_date' => $member->baptism_date?->format('Y-m-d'),
                'membership_status' => $member->membership_status,
                'is_active' => $member->is_active,
            ],

            'contact_details' => [
                'email' => $member->email,
                'phone' => $member->phone,
                'alternative_phone' => $member->alternative_phone,
                'address' => $member->address,
                'emergency_contact' => [
                    'name' => $member->emergency_contact_name,
                    'relationship' => $member->emergency_contact_relationship,
                    'phone' => $member->emergency_contact_phone,
                ],
            ],

            'membership_info' => [
                'membership_status' => $member->membership_status,
                'membership_date' => $member->membership_date?->format('Y-m-d'),
                'baptism_date' => $member->baptism_date?->format('Y-m-d'),
                'baptism_location' => $member->baptism_location,
                'previous_church' => $member->previous_church,
                'how_heard_about_us' => $member->how_heard_about_us,
                'ministries' => $member->ministries->pluck('name')->toArray(),
            ],

            'attendance_records' => $member->attendances()
                ->get()
                ->map(fn($attendance) => [
                    'event_name' => $attendance->event?->name,
                    'event_date' => $attendance->event?->start_date,
                    'attended' => $attendance->attended,
                    'recorded_at' => $attendance->created_at->format('Y-m-d H:i:s'),
                ])->toArray(),

            'giving_records' => $member->givings()
                ->get()
                ->map(fn($giving) => [
                    'amount' => $giving->amount,
                    'currency' => $giving->currency,
                    'giving_type' => $giving->giving_type,
                    'date' => $giving->giving_date->format('Y-m-d'),
                    'method' => $giving->payment_method,
                    'notes' => $giving->notes,
                ])->toArray(),

            'course_enrollments' => $member->courseEnrollments()
                ->with('course')
                ->get()
                ->map(fn($enrollment) => [
                    'course_name' => $enrollment->course->name,
                    'enrolled_date' => $enrollment->created_at->format('Y-m-d'),
                    'completion_status' => $enrollment->completion_status,
                    'completion_date' => $enrollment->completion_date?->format('Y-m-d'),
                    'progress_percentage' => $enrollment->progress_percentage,
                ])->toArray(),

            'pastoral_care' => $member->pastoralReminders()
                ->get()
                ->map(fn($reminder) => [
                    'type' => $reminder->reminder_type,
                    'date' => $reminder->reminder_date->format('Y-m-d'),
                    'description' => $reminder->description,
                    'last_sent' => $reminder->last_sent_at?->format('Y-m-d H:i:s'),
                ])->toArray(),

            'communications' => $member->pastoralNotifications()
                ->get()
                ->map(fn($notification) => [
                    'type' => $notification->notification_type,
                    'subject' => $notification->subject,
                    'sent_at' => $notification->sent_at?->format('Y-m-d H:i:s'),
                    'status' => $notification->status,
                ])->toArray(),

            'consents' => $member->gdprConsents()
                ->get()
                ->map(fn($consent) => [
                    'consent_type' => $consent->consent_type,
                    'consent_given' => $consent->consent_given,
                    'consent_date' => $consent->consent_date?->format('Y-m-d H:i:s'),
                    'consent_method' => $consent->consent_method,
                    'withdrawn_date' => $consent->consent_withdrawn_date?->format('Y-m-d H:i:s'),
                ])->toArray(),

            'audit_logs' => $member->gdprAuditLogs()
                ->latest()
                ->limit(100) // Limit to last 100 entries
                ->get()
                ->map(fn($log) => [
                    'action' => $log->action,
                    'description' => $log->description,
                    'performed_by' => $log->performed_by,
                    'date' => $log->created_at->format('Y-m-d H:i:s'),
                ])->toArray(),

            default => [],
        };
    }

    private function createCsvExport(Member $member, string $dataType, array $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        $filename = "member_{$member->id}_{$dataType}_" . now()->format('Y_m_d_H_i_s') . '.csv';
        $filepath = "gdpr_exports/{$filename}";

        $handle = fopen(storage_path("app/{$filepath}"), 'w');

        if ($dataType === 'personal_info') {
            fputcsv($handle, ['Field', 'Value']);
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        fputcsv($handle, ["{$key}.{$subKey}", $subValue]);
                    }
                } else {
                    fputcsv($handle, [$key, $value]);
                }
            }
        } else {
            // For array data like attendance_records, giving_records
            if (!empty($data)) {
                $headers = array_keys($data[0]);
                fputcsv($handle, $headers);
                foreach ($data as $row) {
                    fputcsv($handle, array_values($row));
                }
            }
        }

        fclose($handle);
        return $filepath;
    }

    public function deleteMemberData(Member $member, array $dataTypes = []): array
    {
        $deletedData = [];

        // Log the deletion action
        GdprAuditLog::logAction([
            'member_id' => $member->id,
            'action' => 'data_deletion',
            'description' => 'Member data deletion started for GDPR request',
            'new_values' => ['data_types' => $dataTypes],
        ]);

        foreach ($dataTypes as $dataType) {
            $deletedData[$dataType] = $this->deleteDataByType($member, $dataType);
        }

        return $deletedData;
    }

    private function deleteDataByType(Member $member, string $dataType): array
    {
        $result = ['deleted' => false, 'reason' => ''];

        try {
            match($dataType) {
                'attendance_records' => [
                    'deleted' => $member->attendances()->delete() > 0,
                    'count' => $member->attendances()->count(),
                ],

                'giving_records' => [
                    'deleted' => $member->givings()->delete() > 0,
                    'count' => $member->givings()->count(),
                ],

                'course_enrollments' => [
                    'deleted' => $member->courseEnrollments()->delete() > 0,
                    'count' => $member->courseEnrollments()->count(),
                ],

                'pastoral_care' => [
                    'deleted' => $member->pastoralReminders()->delete() > 0,
                    'count' => $member->pastoralReminders()->count(),
                ],

                'communications' => [
                    'deleted' => $member->pastoralNotifications()->delete() > 0,
                    'count' => $member->pastoralNotifications()->count(),
                ],

                'personal_info' => [
                    'deleted' => false,
                    'reason' => 'Personal information cannot be automatically deleted - requires manual review',
                ],

                default => [
                    'deleted' => false,
                    'reason' => 'Data type not supported for automatic deletion',
                ],
            };

            $result['deleted'] = true;
            $result['timestamp'] = now()->toISOString();

        } catch (\Exception $e) {
            $result['deleted'] = false;
            $result['reason'] = $e->getMessage();
        }

        return $result;
    }

    public function anonymizeMemberData(Member $member): bool
    {
        try {
            // Log the anonymization
            GdprAuditLog::logAction([
                'member_id' => $member->id,
                'action' => 'data_anonymization',
                'description' => 'Member data anonymized for GDPR compliance',
                'old_values' => [
                    'name' => $member->first_name . ' ' . $member->last_name,
                    'email' => $member->email,
                ],
            ]);

            // Anonymize personal data
            $member->update([
                'first_name' => 'Anonymized',
                'last_name' => 'Member',
                'middle_name' => null,
                'preferred_name' => null,
                'email' => 'anonymized_' . $member->id . '@deleted.local',
                'phone' => null,
                'alternative_phone' => null,
                'address' => null,
                'emergency_contact_name' => null,
                'emergency_contact_phone' => null,
                'emergency_contact_relationship' => null,
                'date_of_birth' => null,
                'is_active' => false,
                'membership_status' => 'inactive',
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
