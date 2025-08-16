<?php

namespace App\Exports;

use App\Models\Rota;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class RotaExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rota;

    public function __construct(Rota $rota)
    {
        $this->rota = $rota;
    }

    public function array(): array
    {
        $scheduleData = $this->rota->schedule_data;
        $data = [];

        if (empty($scheduleData)) {
            return [['No schedule data available']];
        }

        // Get all Sundays in the date range
        $sundays = $this->getSundaysBetweenDates($this->rota->start_date, $this->rota->end_date);

        // Add each role as a row with assignments for each Sunday
        foreach ($scheduleData as $role => $dateAssignments) {
            $row = [$role]; // Start with role name

            foreach ($sundays as $sunday) {
                $assignment = $dateAssignments[$sunday] ?? '';
                $row[] = $assignment;
            }

            $data[] = $row;
        }

        return $data;
    }

    public function headings(): array
    {
        $sundays = $this->getSundaysBetweenDates($this->rota->start_date, $this->rota->end_date);
        $headings = ['Role'];

        foreach ($sundays as $sunday) {
            $headings[] = \Carbon\Carbon::parse($sunday)->format('M j');
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('1:1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('FFFF00')); // Yellow background

        // Style the role column
        $sheet->getStyle('A:A')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('E6E6FA')); // Light purple background

        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Role column wider
        ];
    }

    private function getSundaysBetweenDates($startDate, $endDate): array
    {
        $sundays = [];
        $current = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        // Move to the first Sunday
        while ($current->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
            $current->addDay();
        }

        // Collect all Sundays
        while ($current->lte($end)) {
            $sundays[] = $current->toDateString();
            $current->addWeek(); // Move to next Sunday
        }

        return $sundays;
    }
}
