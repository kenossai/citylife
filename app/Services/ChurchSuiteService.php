<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ChurchSuiteService
{
    protected $apiUrl;
    protected $accountName;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.churchsuite.api_url');
        $this->accountName = config('services.churchsuite.account_name');
        $this->apiKey = config('services.churchsuite.api_key');
    }

    /**
     * Transfer member data to ChurchSuite
     *
     * @param Member $member
     * @return array
     * @throws Exception
     */
    public function transferMember(Member $member): array
    {
        try {
            // Prepare member data for ChurchSuite API
            $data = $this->prepareMemberData($member);

            // Send to ChurchSuite
            $response = Http::withHeaders([
                'X-Account' => $this->accountName,
                'X-Auth' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/contacts", $data);

            if ($response->successful()) {
                $churchSuiteContact = $response->json();

                // Update member with ChurchSuite ID
                $member->update([
                    'churchsuite_id' => $churchSuiteContact['id'] ?? null,
                    'churchsuite_synced_at' => now(),
                    'churchsuite_sync_status' => 'synced',
                ]);

                Log::info('Member successfully transferred to ChurchSuite', [
                    'member_id' => $member->id,
                    'churchsuite_id' => $churchSuiteContact['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Member successfully transferred to ChurchSuite',
                    'churchsuite_id' => $churchSuiteContact['id'] ?? null,
                    'data' => $churchSuiteContact,
                ];
            }

            // Handle error response
            $errorMessage = $response->json()['message'] ?? 'Unknown error occurred';

            $member->update([
                'churchsuite_sync_status' => 'failed',
                'churchsuite_sync_error' => $errorMessage,
            ]);

            Log::error('Failed to transfer member to ChurchSuite', [
                'member_id' => $member->id,
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            return [
                'success' => false,
                'message' => "Failed to transfer to ChurchSuite: {$errorMessage}",
                'error' => $errorMessage,
            ];

        } catch (Exception $e) {
            $member->update([
                'churchsuite_sync_status' => 'failed',
                'churchsuite_sync_error' => $e->getMessage(),
            ]);

            Log::error('Exception during ChurchSuite transfer', [
                'member_id' => $member->id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Update existing ChurchSuite contact
     *
     * @param Member $member
     * @return array
     */
    public function updateMember(Member $member): array
    {
        if (!$member->churchsuite_id) {
            return $this->transferMember($member);
        }

        try {
            $data = $this->prepareMemberData($member);

            $response = Http::withHeaders([
                'X-Account' => $this->accountName,
                'X-Auth' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->put("{$this->apiUrl}/contacts/{$member->churchsuite_id}", $data);

            if ($response->successful()) {
                $member->update([
                    'churchsuite_synced_at' => now(),
                    'churchsuite_sync_status' => 'synced',
                ]);

                Log::info('Member updated in ChurchSuite', [
                    'member_id' => $member->id,
                    'churchsuite_id' => $member->churchsuite_id,
                ]);

                return [
                    'success' => true,
                    'message' => 'Member successfully updated in ChurchSuite',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to update member in ChurchSuite',
                'error' => $response->json(),
            ];

        } catch (Exception $e) {
            Log::error('Exception during ChurchSuite update', [
                'member_id' => $member->id,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Prepare member data for ChurchSuite API
     *
     * @param Member $member
     * @return array
     */
    protected function prepareMemberData(Member $member): array
    {
        return [
            'first_name' => $member->first_name,
            'last_name' => $member->last_name,
            'middle_name' => $member->middle_name,
            'email' => $member->email,
            'mobile' => $member->phone,
            'telephone' => $member->alternative_phone,
            'address' => [
                'line1' => $member->address,
                'city' => $member->city,
                'postcode' => $member->postal_code,
                'country' => $member->country,
            ],
            'sex' => $this->mapGender($member->gender),
            'marital' => $this->mapMaritalStatus($member->marital_status),
            'date_of_birth' => $member->date_of_birth?->format('Y-m-d'),
            'job' => $member->occupation,
            'employer' => $member->employer,
            'custom_fields' => [
                'membership_number' => $member->membership_number,
                'membership_status' => $member->membership_status,
                'first_visit_date' => $member->first_visit_date?->format('Y-m-d'),
                'membership_date' => $member->membership_date?->format('Y-m-d'),
                'baptism_status' => $member->baptism_status,
                'baptism_date' => $member->baptism_date?->format('Y-m-d'),
                'previous_church' => $member->previous_church,
                'emergency_contact_name' => $member->emergency_contact_name,
                'emergency_contact_phone' => $member->emergency_contact_phone,
                'emergency_contact_relationship' => $member->emergency_contact_relationship,
            ],
            'communication' => [
                'general_email' => $member->receives_newsletter ? 1 : 0,
                'general_sms' => $member->receives_sms ? 1 : 0,
            ],
        ];
    }

    /**
     * Map gender to ChurchSuite format
     */
    protected function mapGender(?string $gender): ?string
    {
        if (!$gender) return null;

        return match(strtolower($gender)) {
            'male', 'm' => 'm',
            'female', 'f' => 'f',
            default => null,
        };
    }

    /**
     * Map marital status to ChurchSuite format
     */
    protected function mapMaritalStatus(?string $status): ?string
    {
        if (!$status) return null;

        return match(strtolower($status)) {
            'single' => 'single',
            'married' => 'married',
            'divorced' => 'divorced',
            'widowed' => 'widowed',
            'separated' => 'separated',
            default => null,
        };
    }

    /**
     * Check if ChurchSuite credentials are configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiUrl) &&
               !empty($this->accountName) &&
               !empty($this->apiKey);
    }

    /**
     * Test ChurchSuite API connection
     */
    public function testConnection(): array
    {
        try {
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'ChurchSuite is not configured. Please add credentials to your .env file.',
                ];
            }

            $response = Http::withHeaders([
                'X-Account' => $this->accountName,
                'X-Auth' => $this->apiKey,
            ])->get("{$this->apiUrl}/contacts", ['per_page' => 1]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Successfully connected to ChurchSuite API',
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to connect to ChurchSuite API',
                'error' => $response->body(),
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception during connection test',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Bulk transfer members who completed CDC course but not synced
     */
    public function bulkTransferCDCGraduates(): array
    {
        // Get members who completed CDC course but haven't been synced
        $cdcCourse = \App\Models\Course::where('title', 'LIKE', '%Christian Development%')
            ->orWhere('title', 'LIKE', '%CDC%')
            ->first();

        if (!$cdcCourse) {
            return [
                'success' => false,
                'message' => 'CDC course not found',
            ];
        }

        $completedEnrollments = \App\Models\CourseEnrollment::where('course_id', $cdcCourse->id)
            ->where('status', 'completed')
            ->whereHas('user', function($query) {
                $query->where(function($q) {
                    $q->whereNull('churchsuite_sync_status')
                      ->orWhere('churchsuite_sync_status', '!=', 'synced');
                });
            })
            ->with('user')
            ->get();

        $results = [
            'total' => $completedEnrollments->count(),
            'successful' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($completedEnrollments as $enrollment) {
            try {
                $result = $this->transferMember($enrollment->user);

                if ($result['success']) {
                    $results['successful']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = [
                        'member_id' => $enrollment->user->id,
                        'name' => $enrollment->user->first_name . ' ' . $enrollment->user->last_name,
                        'error' => $result['message'],
                    ];
                }
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'member_id' => $enrollment->user->id,
                    'name' => $enrollment->user->first_name . ' ' . $enrollment->user->last_name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
