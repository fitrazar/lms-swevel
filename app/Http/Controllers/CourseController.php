<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $courses = Course::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(9);
        return view('participant.course.index', compact('courses', 'search'));
    }


    public function show(Course $course)
    {
        $course = Course::with([
            'topics' => function ($query) {
                $query->orderBy('order', 'asc');
            }
        ])->findOrFail($course->id);
        $courses = Course::latest()->take(5)->get();

        return view("participant.course.show", compact("course", "courses"));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'agreement' => 'required',
        ]);


        Enrollment::create([
            'participant_id' => Auth::user()->participant->id,
            'course_id' => $request->course_id,
        ]);

        return redirect()->back()->with('success', 'Berhasil Melakukan Pendaftaran.');
    }

    public function read(Course $course, Topic $topic)
    {
        $course = Course::with([
            'topics' => function ($query) {
                $query->orderBy('order', 'asc');
            }
        ])->findOrFail($course->id);

        $currentTopic = $course->topics->where('slug', $topic->slug)->first();
        $prevTopic = $course->topics->where('order', $currentTopic->order - 1)->first();
        $nextTopic = $course->topics->where('order', $currentTopic->order + 1)->first();

        $userProgress = Progress::where('participant_id', Auth::user()->participant->id)
            ->where('topic_id', $prevTopic?->id)
            ->orWhere('topic_id', $nextTopic?->id)
            ->orWhere('topic_id', $currentTopic?->id)
            ->orWhere('is_completed', 1)
            ->first();
        if ($userProgress) {
            if ($nextTopic) {
                $userProgress->update([
                    'topic_id' => $currentTopic->id
                ]);
            } else {
                $userProgress->update([
                    'topic_id' => $currentTopic->id,
                    'is_completed' => 1
                ]);
            }
        } else {
            Progress::create([
                'participant_id' => Auth::user()->participant->id,
                'topic_id' => $currentTopic->id,
                'is_completed' => 0
            ]);
        }

        return view('participant.course.read', compact('course', 'currentTopic', 'prevTopic', 'nextTopic'));
    }

    public function completed(Course $course)
    {
        $userId = Auth::user()->participant->id;

        $progress = Progress::where('participant_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if ($progress) {
            $progress->update(['is_completed' => 1]);
        } else {
            Progress::create([
                'participant_id' => $userId,
                'course_id' => $course->id,
                'is_completed' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil diselesaikan.',
        ]);
    }


}
