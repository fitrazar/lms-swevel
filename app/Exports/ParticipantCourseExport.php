<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantCourseExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithStyles, ShouldAutoSize, WithProperties
{
    use Exportable;

    private $rowNumber = 0;

    public function properties(): array
    {
        return [
            'creator' => 'Tim 9',
            'lastModifiedBy' => 'Tim 9',
            'title' => 'Laporan Kursus',
            'description' => 'Laporan Kursus',
            'subject' => 'Laporan Kursus',
            'keywords' => 'kursus',
            'category' => 'kursus',
            'manager' => 'Tim 9',
        ];
    }

    public function query()
    {
        return Course::whereHas('instructors', function ($query) {
            $query->where('instructor_id', Auth::user()->instructor->id);
        })
            ->with([
                'enrolls.participant'
            ])
            ->withCount(['enrolls'])
            ->orderBy('created_at', 'desc');
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($course): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $course->title,
            $course->enrolls->map(fn($enroll) => $enroll->participant->name)->implode(', '),
            $course->enrolls_count,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kursus',
            'Nama Peserta',
            'Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'size' => 12,
                'name' => 'Times New Roman'
            ],
        ];
        $sheet->getStyle('A2:D' . $this->rowNumber + 1)->applyFromArray($styleArray);
        $sheet->getStyle('A1:D1')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
                'size' => 13,
                'name' => 'Times New Roman'
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
    }
}
