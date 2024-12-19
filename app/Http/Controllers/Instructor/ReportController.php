<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantCourseExport;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\ParticipantCompleteExport;
use App\Exports\ParticipantProgressExport;

class ReportController extends Controller
{
    public function course(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;
        if ($request->ajax()) {
            $courses = Course::whereHas('instructors', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
                ->with([
                    'enrolls.participant'
                ])
                ->withCount(['enrolls'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($course) {
                    return [
                        'title' => $course->title,
                        'total_participants' => $course->enrolls_count,
                        'participants' => $course->enrolls->map(fn($enroll) => $enroll->participant->name)->toArray(),
                    ];
                });

            return DataTables::of($courses)->make();
        }

        return view('instructor.report.course');
    }

    public function progress(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;
        if ($request->ajax()) {
            $courses = Course::whereHas('instructors', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
                ->with([
                    'enrolls.participant.progress.topic',
                    'topics'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->flatMap(function ($course) {
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
                            'participant_name' => $enroll->participant->name,
                            'course_title' => $course->title,
                            'progress' => round($progressValue) . '%',
                        ];
                    });
                });

            return DataTables::of($courses)->make();
        }

        return view('instructor.report.progress');
    }

    public function complete(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;
        if ($request->ajax()) {
            $completedPerMonth = DB::table('progress')
                ->join('topics', 'progress.topic_id', '=', 'topics.id')
                ->join('courses', 'topics.course_id', '=', 'courses.id')
                ->join('course_instructors', 'courses.id', '=', 'course_instructors.course_id')
                ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
                ->selectRaw("DATE_FORMAT(progress.updated_at, '%Y-%m') as month, COUNT(DISTINCT enrollments.participant_id) as total")
                ->where('progress.is_completed', 1)
                ->whereYear('progress.updated_at', date('Y'))
                ->where('course_instructors.instructor_id', $instructorId)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $month = date('Y-m', mktime(0, 0, 0, $i, 1, date('Y')));
                $data[$month] = 0;
            }

            foreach ($completedPerMonth as $record) {
                $data[$record->month] = $record->total;
            }

            $tableData = [];
            foreach ($data as $month => $total) {
                $tableData[] = [
                    'month' => $month,
                    'month_name' => date('F', strtotime($month . '-01')),
                    'total' => $total,
                ];
            }

            return DataTables::of($tableData)->make();
        }


        return view('instructor.report.complete');
    }


    public function exportProgress(Request $request)
    {
        return Excel::download(new ParticipantProgressExport(), 'Laporan Progress Peserta.xlsx');
    }

    public function exportCourse(Request $request)
    {
        return Excel::download(new ParticipantCourseExport(), 'Laporan Kursus.xlsx');
    }

    public function exportComplete(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;

        // Ambil data dengan query yang sama
        $completedPerMonth = DB::table('progress')
            ->join('topics', 'progress.topic_id', '=', 'topics.id')
            ->join('courses', 'topics.course_id', '=', 'courses.id')
            ->join('course_instructors', 'courses.id', '=', 'course_instructors.course_id')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->selectRaw("DATE_FORMAT(progress.updated_at, '%Y-%m') as month, COUNT(DISTINCT enrollments.participant_id) as total")
            ->where('progress.is_completed', 1)
            ->whereYear('progress.updated_at', date('Y'))
            ->where('course_instructors.instructor_id', $instructorId)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = date('Y-m', mktime(0, 0, 0, $i, 1, date('Y')));
            $data[$month] = 0;
        }

        foreach ($completedPerMonth as $record) {
            $data[$record->month] = $record->total;
        }

        // Persiapkan data untuk tabel export
        $tableData = [];
        foreach ($data as $month => $total) {
            $tableData[] = [
                'month' => $month,
                'month_name' => date('F', strtotime($month . '-01')),
                'total' => $total ?? 0,
            ];
        }

        return Excel::download(new ParticipantCompleteExport($tableData), 'Laporan Kursus Selesai.xlsx');
    }

    public function exportCompletePdf(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;

        // Ambil data dengan query yang sama
        $completedPerMonth = DB::table('progress')
            ->join('topics', 'progress.topic_id', '=', 'topics.id')
            ->join('courses', 'topics.course_id', '=', 'courses.id')
            ->join('course_instructors', 'courses.id', '=', 'course_instructors.course_id')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->selectRaw("DATE_FORMAT(progress.updated_at, '%Y-%m') as month, COUNT(DISTINCT enrollments.participant_id) as total")
            ->where('progress.is_completed', 1)
            ->whereYear('progress.updated_at', date('Y'))
            ->where('course_instructors.instructor_id', $instructorId)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = date('Y-m', mktime(0, 0, 0, $i, 1, date('Y')));
            $data[$month] = 0;
        }

        foreach ($completedPerMonth as $record) {
            $data[$record->month] = $record->total;
        }

        // Siapkan data untuk laporan PDF
        $tableData = [];
        foreach ($data as $month => $total) {
            $tableData[] = [
                'month' => $month,
                'month_name' => date('F', strtotime($month . '-01')),
                'total' => $total,
            ];
        }

        // Menggunakan view untuk layout laporan PDF
        $pdf = Pdf::loadView('instructor.report.pdf.complete', compact('tableData'));

        // Menyimpan atau mengunduh file PDF
        return $pdf->download('Laporan Kursus Selesai.pdf');
    }

    public function exportCoursePdf(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;

        // Ambil data kursus yang sama dengan query pada method course
        $courses = Course::whereHas('instructors', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
            ->with([
                'enrolls.participant'
            ])
            ->withCount(['enrolls'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($course) {
                return [
                    'title' => $course->title,
                    'total_participants' => $course->enrolls_count,
                    'participants' => $course->enrolls->map(fn($enroll) => $enroll->participant->name)->toArray(),
                ];
            });

        // Gunakan view untuk layout laporan PDF
        $pdf = Pdf::loadView('instructor.report.pdf.course', compact('courses'));

        // Unduh file PDF
        return $pdf->download('Laporan Kursus.pdf');
    }

    public function exportProgressPdf(Request $request)
    {
        $instructorId = Auth::user()->instructor->id;

        // Ambil data yang sama seperti di method progress
        $courses = Course::whereHas('instructors', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
            ->with([
                'enrolls.participant.progress.topic',
                'topics'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->flatMap(function ($course) {
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
                        'participant_name' => $enroll->participant->name,
                        'course_title' => $course->title,
                        'progress' => round($progressValue) . '%',
                    ];
                });
            });

        // Gunakan view untuk layout laporan PDF
        $pdf = Pdf::loadView('instructor.report.pdf.progress', compact('courses'));

        // Unduh file PDF
        return $pdf->download('Laporan Progress Kursus.pdf');
    }
}
