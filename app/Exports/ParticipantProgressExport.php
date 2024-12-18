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

class ParticipantProgressExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithStyles, ShouldAutoSize, WithProperties
{
    use Exportable;

    private $rowNumber = 0;

    public function properties(): array
    {
        return [
            'creator' => 'Tim 9',
            'lastModifiedBy' => 'Tim 9',
            'title' => 'Laporan Progress Peserta',
            'description' => 'Laporan Progress Peserta',
            'subject' => 'Laporan Progress Peserta',
            'keywords' => 'progress',
            'category' => 'progress',
            'manager' => 'Tim 9',
        ];
    }

    public function query()
    {
        return Course::whereHas('instructors', function ($query) {
            $query->where('instructor_id', Auth::user()->instructor->id);
        })
            ->with([
                'enrolls.participant.progress.topic',
                'topics'
            ])
            ->orderBy('created_at', 'desc');
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($course): array
    {
        $this->rowNumber++;
        return $course->enrolls->map(function ($enroll) use ($course) {
            $progresses = $enroll->participant
                ->progress()
                ->whereHas('topic', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->get();

            if ($progresses->count() > 0) {
                $allCompleted = $progresses->every(fn($progress) => $progress->is_completed);

                if ($allCompleted) {
                    $progressValue = 100;
                } else {
                    $lastCompletedTopicOrder = $progresses
                        ->map(fn($progress) => $progress->topic->order)
                        ->max();

                    $maxOrder = $course->topics->max('order');

                    $progressValue = $maxOrder > 0 ? ($lastCompletedTopicOrder / $maxOrder) * 100 : 0;
                }
            } else {
                $progressValue = 0;
            }

            return [
                $this->rowNumber,
                $enroll->participant->name,
                $course->title,
                round($progressValue) . '%',
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peserta',
            'Nama Kursus',
            'Progress',
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
