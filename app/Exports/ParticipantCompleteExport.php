<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantCompleteExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithProperties
{
    use Exportable;

    private $rowNumber = 0, $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


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

    /**
     * Mendapatkan data yang akan diekspor
     *
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Nama Bulan',
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
        $sheet->getStyle('A2:C' . $this->rowNumber + 1)->applyFromArray($styleArray);
        $sheet->getStyle('A1:C1')->applyFromArray([
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
