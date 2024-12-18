<?php

namespace App\Exports;

use App\Models\Enrollment;
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

class EnrollmentExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithStyles, ShouldAutoSize, WithProperties
{
    use Exportable;

    private $rowNumber = 0, $kursus;

    public function __construct($kursus)
    {
        $this->kursus = $kursus;
    }

    public function properties(): array
    {
        return [
            'creator' => 'Tim 9',
            'lastModifiedBy' => 'Tim 9',
            'title' => 'Data Pendaftar',
            'description' => 'Data Pendaftar',
            'subject' => 'Data Pendaftar',
            'keywords' => 'pendaftar',
            'category' => 'pendaftar',
            'manager' => 'Tim 9',
        ];
    }

    public function query()
    {
        $query = Enrollment::select('enrollments.*');

        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            if ($this->kursus && $this->kursus != 'All' && $this->kursus != NULL) {
                $course = $this->kursus;
                $query->whereHas('course', function ($query) use ($course) {
                    $query->where('id', $course);
                })->with(['course'])->latest()->get();
            } else {
                $query->whereHas('course')->with(['course'])->latest()->get();
            }
        } else {
            if ($this->kursus && $this->kursus != 'All' && $this->kursus != NULL) {
                $course = $this->kursus;
                $query->whereHas('course.instructors', function ($query) use ($course) {
                    $query->where('id', $course);
                })->with(['course'])->latest()->get();
            } else {
                $query->whereHas('course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })->with(['course'])->latest()->get();
            }
        }

        return $query;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($enrollment): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $enrollment->participant->name,
            $enrollment->course->title,
            $enrollment->status == 'active' ? 'Aktif' : 'Belum Aktif',
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peserta',
            'Nama Kursus',
            'Status',
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
