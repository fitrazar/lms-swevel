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
        $search = null;
        $filter = null;
        $totalActive = null;
        $totalInActive = null;
        $totalCompleted = null;
        $totalNotCompleted = null;
        $totalProgress = null;
        $courseStatus = null;
        $chartData = null;
        if (Auth::user()->roles->pluck('name')[0] == 'participant') {
            $totalActive = Enrollment::where('status', 'active')->where('participant_id', Auth::user()->participant->id)->count();
            $totalInActive = Enrollment::where('status', 'inactive')->where('participant_id', Auth::user()->participant->id)->count();

            $courseId = Course::query()->whereHas('enrolls', function ($query) {
                $query->where('participant_id', Auth::user()->participant->id);
            })->pluck('id')->toArray();

            $search = $request->input('search');
            $filter = $request->input('filter');
            $activeCourses = Course::query()
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->when($filter == 'active', function ($query) {
                    return $query->whereHas('enrolls', function ($query) {
                        $query->where('status', 'active');
                    });
                })
                ->when($filter == 'inactive', function ($query) {
                    return $query->whereHas('enrolls', function ($query) {
                        $query->where('status', 'inactive');
                    });
                })
                ->whereHas('enrolls', function ($query) {
                    $query->where('participant_id', Auth::user()->participant->id);
                })
                ->latest()
                ->paginate(9);

            $totalCompleted = Enrollment::where('status', 'active')
                ->where('participant_id', Auth::user()->participant->id)
                ->whereHas('course.topics', function ($query) use ($courseId) {
                    $query->whereIn('course_id', $courseId)
                        ->whereHas('progress', function ($query) {
                            $query->where('is_completed', 1);
                        });
                })->count();
            $totalNotCompleted = Enrollment::where('status', 'active')
                ->where('participant_id', Auth::user()->participant->id)
                ->whereHas('course.topics', function ($query) use ($courseId) {
                    $query->whereIn('course_id', $courseId)
                        ->whereHas('progress', function ($query) {
                            $query->where('is_completed', 0);
                        });
                })->count();

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


            $courseStatus = [
                'belum_dimulai' => Course::whereHas('enrolls', function ($query) {
                    $query->where('participant_id', Auth::user()->participant->id)
                        ->where('status', 'active');
                })->whereDoesntHave('topics.progress')->count(),
                'sedang_berlangsung' => Course::whereHas('enrolls', function ($query) {
                    $query->where('participant_id', Auth::user()->participant->id)
                        ->where('status', 'active');
                })->whereHas('topics.progress', function ($query) {
                    $query->where('is_completed', 0);
                })->count(),
                'selesai' => Course::whereHas('enrolls', function ($query) {
                    $query->where('participant_id', Auth::user()->participant->id)
                        ->where('status', 'active');
                })->whereDoesntHave('topics.progress', function ($query) {
                    $query->where('is_completed', 0);
                })->count(),
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

            $labels = [];
            $data = [];

            for ($i = 1; $i <= 12; $i++) {
                $month = date('Y-m', mktime(0, 0, 0, $i, 1, date('Y')));
                $labels[] = date('F', mktime(0, 0, 0, $i, 1));
                $data[$month] = 0;
            }

            foreach ($completedCoursesByMonth as $record) {
                $data[$record->month] = $record->total;
            }

            $chartData = [
                'labels' => $labels,
                'data' => array_values($data),
            ];



        } else {
            $activeCourses = collect();
        }

        return view(
            'dashboard',
            compact(
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
            )
        );
    }
}
