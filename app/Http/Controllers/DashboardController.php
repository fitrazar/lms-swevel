<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isParticipant = $user->roles->pluck('name')[0] == 'participant';

        if (!$isParticipant) {
            return view('dashboard', [
                'activeCourses' => collect(),
            ]);
        }

        $participantId = $user->participant->id;
        $search = $request->input('search');
        $filter = $request->input('filter');

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

}
