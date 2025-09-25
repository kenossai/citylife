<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $members;

    public function __construct($members = null)
    {
        $this->members = $members;
    }

    public function collection()
    {
        if ($this->members) {
            return $this->members;
        }

        return Member::with(['spouse'])
            ->orderBy('membership_status')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Member #',
            'Title',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Age',
            'Gender',
            'Marital Status',
            'Spouse Name',
            'Occupation',
            'Address',
            'City',
            'Postal Code',
            'Country',
            'Membership Status',
            'First Visit Date',
            'Membership Date',
            'Baptism Status',
            'Baptism Date',
            'Emergency Contact Name',
            'Emergency Contact Phone',
            'Emergency Contact Relationship',
            'Active',
            'Notes',
            'Created Date'
        ];
    }

    public function map($member): array
    {
        // Calculate age if date of birth is available
        $age = null;
        if ($member->date_of_birth) {
            $age = $member->date_of_birth->age;
        }

        // Get spouse name
        $spouseName = '';
        if ($member->spouse_is_member && $member->spouse) {
            $spouseName = trim($member->spouse->title . ' ' . $member->spouse->first_name . ' ' . $member->spouse->last_name);
        } elseif ($member->marital_status === 'Married' && !$member->spouse_is_member) {
            $spouseName = 'Non-member spouse';
        }

        return [
            $member->membership_number,
            $member->title,
            $member->first_name,
            $member->last_name,
            $member->email,
            $member->phone,
            $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
            $age,
            $member->gender ? ucfirst($member->gender) : '',
            $member->marital_status,
            $spouseName,
            $member->occupation,
            $member->address,
            $member->city,
            $member->postal_code,
            $member->country,
            ucwords(str_replace('_', ' ', $member->membership_status)),
            $member->first_visit_date ? $member->first_visit_date->format('Y-m-d') : '',
            $member->membership_date ? $member->membership_date->format('Y-m-d') : '',
            $member->baptism_status,
            $member->baptism_date ? $member->baptism_date->format('Y-m-d') : '',
            $member->emergency_contact_name,
            $member->emergency_contact_phone,
            $member->emergency_contact_relationship,
            $member->is_active ? 'Yes' : 'No',
            $member->notes,
            $member->created_at ? $member->created_at->format('Y-m-d H:i:s') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, // Member #
            'B' => 8,  // Title
            'C' => 15, // First Name
            'D' => 15, // Last Name
            'E' => 25, // Email
            'F' => 15, // Phone
            'G' => 12, // Date of Birth
            'H' => 8,  // Age
            'I' => 10, // Gender
            'J' => 15, // Marital Status
            'K' => 20, // Spouse Name
            'L' => 20, // Occupation
            'M' => 30, // Address
            'N' => 15, // City
            'O' => 12, // Postal Code
            'P' => 15, // Country
            'Q' => 18, // Membership Status
            'R' => 15, // First Visit Date
            'S' => 15, // Membership Date
            'T' => 18, // Baptism Status
            'U' => 15, // Baptism Date
            'V' => 20, // Emergency Contact Name
            'W' => 18, // Emergency Contact Phone
            'X' => 20, // Emergency Contact Relationship
            'Y' => 8,  // Active
            'Z' => 30, // Notes
            'AA' => 18, // Created Date
        ];
    }

    public function title(): string
    {
        return 'Church Members';
    }
}
