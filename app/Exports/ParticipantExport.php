<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithStyles, ShouldAutoSize, WithProperties
{
    use Exportable;

    private $rowNumber = 0, $gender;

    public function __construct($gender)
    {
        $this->gender = $gender;
    }

    public function properties(): array
    {
        return [
            'creator' => 'Tim 9',
            'lastModifiedBy' => 'Tim 9',
            'title' => 'Data Peserta',
            'description' => 'Data Peserta',
            'subject' => 'Data Peserta',
            'keywords' => 'peserta',
            'category' => 'peserta',
            'manager' => 'Tim 9',
        ];
    }

    public function query()
    {
        $query = Participant::select('participants.*');

        if ($this->gender != '' && $this->gender != 'All') {
            $query->where('gender', $this->gender);
        }

        $query->orderBy('name');

        return $query;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($participant): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $participant->user->email,
            $participant->name,
            $participant->gender,
            "'" . $participant->phone,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Email',
            'Nama',
            'JK',
            'No Telpon',
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
        $sheet->getStyle('A2:E' . $this->rowNumber + 1)->applyFromArray($styleArray);
        $sheet->getStyle('A1:E1')->applyFromArray([
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
