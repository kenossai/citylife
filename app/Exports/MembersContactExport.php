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

class MembersContactExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
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

        return Member::where('is_active', true)
            ->whereNotNull('email')
            ->orWhereNotNull('phone')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Email',
            'Phone',
            'Membership Status',
            'Address',
            'City',
            'Postal Code',
            'Birthday',
            'Anniversary Date',
            'Emergency Contact',
            'Emergency Phone'
        ];
    }

    public function map($member): array
    {
        // Get anniversary date (membership or wedding)
        $anniversaryDate = '';
        if ($member->membership_date) {
            $anniversaryDate = $member->membership_date->format('M d');
        }

        // Get birthday
        $birthday = '';
        if ($member->date_of_birth) {
            $birthday = $member->date_of_birth->format('M d');
        }

        // Full name
        $fullName = trim($member->title . ' ' . $member->first_name . ' ' . $member->last_name);

        return [
            $fullName,
            $member->email,
            $member->phone,
            ucwords(str_replace('_', ' ', $member->membership_status)),
            $member->address,
            $member->city,
            $member->postal_code,
            $birthday,
            $anniversaryDate,
            $member->emergency_contact_name,
            $member->emergency_contact_phone,
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
                    'startColor' => ['rgb' => '28a745'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // Full Name
            'B' => 30, // Email
            'C' => 15, // Phone
            'D' => 18, // Membership Status
            'E' => 30, // Address
            'F' => 15, // City
            'G' => 12, // Postal Code
            'H' => 12, // Birthday
            'I' => 15, // Anniversary Date
            'J' => 20, // Emergency Contact
            'K' => 15, // Emergency Phone
        ];
    }

    public function title(): string
    {
        return 'Contact Directory';
    }
}
