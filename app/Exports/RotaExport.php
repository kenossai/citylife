<?php

namespace App\Exports;

use App\Models\Rota;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RotaExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $rota;

    public function __construct(Rota $rota)
    {
        $this->rota = $rota;
    }

    public function title(): string
    {
        return $this->rota->title;
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

        // Define role categories for better organization
        $roleCategories = [
            'Leadership' => ['Preaching', 'Leading'],
            'Worship Team' => ['Worship Leader', 'Lead/Second Guitar', 'Bass Guitar', 'Acoustic Guitar', 'Piano 1', 'Piano 2', 'Drums', 'Singers Team'],
            'Technical Team' => ['TL For The Day', 'Media(Kelham)', 'PA(Kelham)', 'Visual(Kelham)', 'Training/Shadow'],
        ];

        // Add each category
        foreach ($roleCategories as $category => $roles) {
            // Add category header row
            $categoryRow = [$category];
            for ($i = 1; $i < count($sundays) + 1; $i++) {
                $categoryRow[] = '';
            }
            $data[] = $categoryRow;

            // Add roles in this category
            foreach ($roles as $role) {
                if (isset($scheduleData[$role])) {
                    $row = [$role]; // Start with role name

                    foreach ($sundays as $sunday) {
                        $assignment = $scheduleData[$role][$sunday] ?? '';
                        $row[] = $assignment;
                    }

                    $data[] = $row;
                }
            }

            // Add empty row between categories
            $emptyRow = array_fill(0, count($sundays) + 1, '');
            $data[] = $emptyRow;
        }

        return $data;
    }

    public function headings(): array
    {
        $sundays = $this->getSundaysBetweenDates($this->rota->start_date, $this->rota->end_date);
        $headings = [$this->rota->title];

        foreach ($sundays as $sunday) {
            $headings[] = \Carbon\Carbon::parse($sunday)->format('M jS');
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $this->getLastColumn();
        $lastRow = $this->getLastRow();

        // Apply styles after the sheet is created
        $sheet->getStyle('1:1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFE135']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Style role column
        $sheet->getStyle('A:A')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6FA']
            ]
        ]);

        // Add borders to the entire table
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Center align all cells except role column
        $sheet->getStyle('B1:' . $lastColumn . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Find and style category rows (Leadership, Worship Team, Technical Team)
        $data = $this->array();
        $categoryRows = [];
        foreach ($data as $rowIndex => $row) {
            if (in_array($row[0], ['Leadership', 'Worship Team', 'Technical Team'])) {
                $categoryRows[] = $rowIndex + 1; // +1 because Excel rows are 1-indexed
            }
        }

        // Style category rows
        foreach ($categoryRows as $rowNum) {
            $sheet->getStyle('A' . $rowNum . ':' . $lastColumn . $rowNum)->applyFromArray([
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D3D3D3']
                ]
            ]);
        }

        return [];
    }

    public function columnWidths(): array
    {
        $sundays = $this->getSundaysBetweenDates($this->rota->start_date, $this->rota->end_date);
        $widths = ['A' => 25]; // Role column wider

        // Set width for date columns
        $columns = range('B', 'Z');
        for ($i = 0; $i < count($sundays); $i++) {
            if (isset($columns[$i])) {
                $widths[$columns[$i]] = 15;
            }
        }

        return $widths;
    }

    private function getLastColumn(): string
    {
        $sundays = $this->getSundaysBetweenDates($this->rota->start_date, $this->rota->end_date);
        $columns = range('A', 'Z');
        return $columns[min(count($sundays), 25)]; // Support up to 26 columns
    }

    private function getLastRow(): int
    {
        // Calculate based on number of roles and categories
        return 25; // Approximate - you can make this more precise
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
