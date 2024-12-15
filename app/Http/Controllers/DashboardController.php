<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AssignmentNotification;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // $user = User::find(1);
        // $assignment = Assignment::find(1);

        // $user->notify(new AssignmentNotification($assignment));
        $user = Auth::user();
        $isParticipant = $user->roles->pluck('name')[0] == 'participant';
        $isInstructor = $user->roles->pluck('name')[0] == 'instructor';

        if (!$isParticipant) {
            if ($isInstructor) {
                return $this->instructor();
            }

            $totalParticipant = Participant::count();
            $totalInstructor = Instructor::count();
            $totalEnrollmentActive = Enrollment::where('status', 'active')->count();
            $totalEnrollmentInActive = Enrollment::where('status', 'inactive')->count();
            $totalMaterial = Material::count();
            $totalAssignment = Assignment::count();
            $totalQuiz = Quiz::count();
            $totalCourse = Course::count();
            $totalParticipantsPerCourse = Course::withCount('enrolls')
                ->get(['id', 'name', 'enrolls_count']);

            $chartDataParticipant = $totalParticipantsPerCourse->map(function ($course) {
                return [
                    'label' => $course->title,
                    'value' => $course->enrolls_count,
                ];
            });

            return view('dashboard', [
                'activeCourses' => collect(),
                'totalParticipant' => $totalParticipant,
                'totalInstructor' => $totalInstructor,
                'totalEnrollmentActive' => $totalEnrollmentActive,
                'totalEnrollmentInActive' => $totalEnrollmentInActive,
                'totalMaterial' => $totalMaterial,
                'totalQuiz' => $totalQuiz,
                'totalAssignment' => $totalAssignment,
                'totalCourse' => $totalCourse,
                'chartDataParticipant' => $chartDataParticipant,
            ]);
        }

        return $this->participant($user, $request->input('search'), $request->input('filter'));


    }

    public function participant($user, $search, $filter)
    {
        $participantId = $user->participant->id;

        // Eager load necessary relationships to minimize N+1 queries
        $coursesQuery = Course::query()
            ->whereHas('enrolls', function ($query) use ($participantId) {
                $query->where('participant_id', $participantId);
            })
            ->with([
                'enrolls' => function ($query) use ($participantId) {
                    $query->where('participant_id', $participantId);
                },
                'topics.progress'
            ]);

        $totalActive = Enrollment::where('status', 'active')->where('participant_id', $participantId)->count();
        $totalInActive = Enrollment::where('status', 'inactive')->where('participant_id', $participantId)->count();

        $activeCourses = $coursesQuery
            ->when($search, fn($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($filter == 'active', fn($query) => $query->whereHas('enrolls', fn($q) => $q->where('status', 'active')))
            ->when($filter == 'inactive', fn($query) => $query->whereHas('enrolls', fn($q) => $q->where('status', 'inactive')))
            ->latest()
            ->paginate(9);

        $courseIds = $coursesQuery->pluck('id');

        $totalCompleted = Enrollment::where('status', 'active')
            ->where('participant_id', $participantId)
            ->whereHas('course.topics.progress', fn($query) => $query->where('is_completed', 1))
            ->count();

        $totalNotCompleted = Enrollment::where('status', 'active')
            ->where('participant_id', $participantId)
            ->whereHas('course.topics.progress', fn($query) => $query->where('is_completed', 0))
            ->count();

        $totalProgress = Course::whereHas('enrolls', function ($query) {
            $query->where('participant_id', Auth::user()->participant->id)
                ->where('status', 'active');
        })
            ->with([
                'enrolls' => function ($query) {
                    $query->where('participant_id', Auth::user()->participant->id);
                },
                'topics.progress'
            ])
            ->take(5)
            ->latest()
            ->get()
            ->map(function ($course) {
                $enroll = $course->enrolls->first();
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
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'progress' => round($progressValue),
                ];
            });
        // dd($coursesQuery->whereHas('enrolls', fn($query) => $query->where('status', 'active'))->whereHas('topics.progress', fn($query) => $query->where('is_completed', 0))->count());
        $courseStatus = [
            'belum_dimulai' => Course::whereHas('enrolls', fn($query) => $query->where('participant_id', Auth::user()->participant->id)->where('status', 'active'))->whereDoesntHave('topics.progress')->count(),
            'sedang_berlangsung' => Course::whereHas('enrolls', fn($query) => $query->where('participant_id', Auth::user()->participant->id)->where('status', 'active'))->whereHas('topics.progress', fn($query) => $query->where('is_completed', 0))->count(),
            'selesai' => Course::whereHas('enrolls', fn($query) => $query->where('participant_id', Auth::user()->participant->id)->where('status', 'active'))->whereDoesntHave('topics.progress', fn($query) => $query->where('is_completed', 0))->count(),
        ];

        $completedCoursesByMonth = DB::table('progress')
            ->join('topics', 'progress.topic_id', '=', 'topics.id')
            ->join('courses', 'topics.course_id', '=', 'courses.id')
            ->selectRaw("DATE_FORMAT(progress.updated_at, '%Y-%m') as month, COUNT(DISTINCT courses.id) as total")
            ->where('progress.is_completed', 1)
            ->whereYear('progress.updated_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = collect(range(1, 12))->map(fn($i) => date('F', mktime(0, 0, 0, $i, 1)));
        $data = $labels->mapWithKeys(fn($label, $i) => [date('Y-m', mktime(0, 0, 0, $i + 1, 1)) => 0]);

        foreach ($completedCoursesByMonth as $record) {
            $data[$record->month] = $record->total;
        }

        $chartData = [
            'labels' => $labels->toArray(),
            'data' => $data->values()->toArray(),
        ];

        return view('dashboard', compact(
            'activeCourses',
            'search',
            'filter',
            'totalInActive',
            'totalActive',
            'totalCompleted',
            'totalNotCompleted',
            'totalProgress',
            'courseStatus',
            'chartData'
        ));
    }

    public function instructor()
    {
        $instructorId = Auth::user()->instructor->id;

        $totalParticipantsPerCourse = Course::whereHas('instructors', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
            ->withCount('enrolls')
            ->get(['id', 'name', 'enrolls_count']);

        $chartDataParticipant = $totalParticipantsPerCourse->map(function ($course) {
            return [
                'label' => $course->title,
                'value' => $course->enrolls_count,
            ];
        });
        // dd($chartDataParticipant, $totalParticipantsPerCourse);

        $totalParticipant = $totalParticipantsPerCourse->sum('enrolls_count');

        // dd($totalParticipant);
        $totalActive = Enrollment::where('status', 'active')
            ->whereHas('course.instructors', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->count();

        $totalInactive = Enrollment::where('status', 'inactive')
            ->whereHas('course.instructors', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->count();

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
            ->orderBy('month')
            ->get();

        // dd($completedPerMonth);

        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = date('Y-m', mktime(0, 0, 0, $i, 1, date('Y')));
            $labels[] = date('F', mktime(0, 0, 0, $i, 1));
            $data[$month] = 0;
        }

        foreach ($completedPerMonth as $record) {
            $data[$record->month] = $record->total;
        }

        $chartData = [
            'labels' => $labels,
            'data' => array_values($data),
        ];

        $topCourse = Course::whereHas('instructors', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
            ->withCount(['enrolls'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($course) {
                return [
                    'title' => $course->title,
                    'total_participants' => $course->enrolls_count,
                ];
            });

        return view('dashboard', [
            'activeCourses' => collect(),
            'totalInActive' => $totalInactive,
            'totalActive' => $totalActive,
            'chartData' => $chartData,
            'totalParticipant' => $totalParticipant,
            'chartDataParticipant' => $chartDataParticipant,
            'completedPerMonth' => $completedPerMonth,
            'topCourse' => $topCourse

        ]);
    }

}
