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
    public function show(Course $course)
    {
        $course = Course::with([
            'topics' => function ($query) {
                $query->orderBy('order', 'asc');
            }
        ])->findOrFail($course->id);

        return view("course-detail", compact("course"));
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

        return view('course-read', compact('course', 'currentTopic', 'prevTopic', 'nextTopic'));
    }
}
