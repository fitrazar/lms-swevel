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

class ParticipantCompleteExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithStyles, ShouldAutoSize, WithProperties
{
    use Exportable;

    private $rowNumber = 0;

    public function properties(): array
    {
        return [
            'creator' => 'Tim 9',
            'lastModifiedBy' => 'Tim 9',
            'title' => 'Laporan Kursus Selesa',
            'description' => 'Laporan Kursus Selesa',
            'subject' => 'Laporan Kursus Selesa',
            'keywords' => 'kursus',
            'category' => 'kursus',
            'manager' => 'Tim 9',
        ];
    }

    public function query()
    {
        return DB::table('progress')
            ->join('topics', 'progress.topic_id', '=', 'topics.id')
            ->join('courses', 'topics.course_id', '=', 'courses.id')
            ->join('course_instructors', 'courses.id', '=', 'course_instructors.course_id')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->selectRaw("DATE_FORMAT(progress.updated_at, '%Y-%m') as month, COUNT(DISTINCT enrollments.participant_id) as total")
            ->where('progress.is_completed', 1)
            ->whereYear('progress.updated_at', date('Y'))
            ->where('course_instructors.instructor_id', Auth::user()->instructor->id)
            ->groupBy('month')
            ->orderBy('month', 'asc');
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($course): array
    {
        $this->rowNumber++;
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = date('Y-m', mktime(0, 0, 0, $i, 1, date('Y')));
            $data[$month] = 0;
        }

        foreach ($course as $record) {
            $data[$record->month] = $record->total;
        }

        $tableData = [];
        foreach ($data as $month => $total) {
            $tableData[] = [
                'month_name' => date('F', strtotime($month . '-01')),
                'total' => $total,
            ];
        }

        return $tableData;
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Total Peserta',
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
        $sheet->getStyle('A2:B' . $this->rowNumber + 1)->applyFromArray($styleArray);
        $sheet->getStyle('A1:B1')->applyFromArray([
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
