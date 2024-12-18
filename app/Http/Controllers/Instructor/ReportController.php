<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantCourseExport;
use Yajra\DataTables\Facades\DataTables;
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


    public function exportProgress(Request $request)
    {
        return Excel::download(new ParticipantProgressExport(), 'Laporan Progress Peserta.xlsx');
    }

    public function exportCourse(Request $request)
    {
        return Excel::download(new ParticipantCourseExport(), 'Laporan Kursus.xlsx');
    }
}
