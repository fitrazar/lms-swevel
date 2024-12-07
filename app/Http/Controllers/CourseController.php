<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Question;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
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
        $startTime = null;
        if ($topic->material->type == 'quiz') {
            session()->forget('quiz_start_time_' . $topic->material->quiz->id);
            session()->forget('exitCount');
            $questions = $topic->material->quiz->questions()->with('options')->get();
            $startTime = session()->get('quiz_start_time_' . $topic->material->quiz->id, now());
            session()->put('quiz_start_time_' . $topic->material->quiz->id, $startTime);
        }

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
            ->orWhere('is_completed', 0)
            ->first();
        if ($userProgress) {
            if ($nextTopic) {
                $userProgress->update([
                    'topic_id' => $currentTopic->id
                ]);
            } else {
                $userProgress->update([
                    'topic_id' => $currentTopic->id,
                ]);
            }
        } else {
            Progress::create([
                'participant_id' => Auth::user()->participant->id,
                'topic_id' => $currentTopic->id,
                'is_completed' => 0
            ]);
        }

        return view('participant.course.read', compact('course', 'currentTopic', 'prevTopic', 'nextTopic', 'startTime'));
    }

    public function completed(Topic $topic)
    {
        $userId = Auth::user()->participant->id;

        $progress = Progress::where('participant_id', $userId)
            ->where('topic_id', $topic->id)
            ->first();

        if ($progress) {
            $progress->update(['is_completed' => 1]);
        } else {
            Progress::create([
                'participant_id' => $userId,
                'topic_id' => $topic->id,
                'is_completed' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil diselesaikan.',
        ]);
    }

    public function submit(Request $request, Course $course, Topic $topic)
    {
        $answers = $request->all();
        $totalScore = 0;
        $exitCount = $request->input('exit_count', 0);

        $penalty = $exitCount;
        $penaltyAmount = $penalty;

        $questions = Question::where('quiz_id', $topic->material->quiz->id)->with('options')->get();

        $quizAttempt = QuizAttempt::firstOrCreate([
            'quiz_id' => $topic->material->quiz->id,
            'participant_id' => Auth::user()->participant->id,
        ], [
            'attempt_date' => now(),
            'score' => 0,
            'is_late' => 0,
            'difference' => 0,
        ]);

        $quizStartTime = session()->get('quiz_start_time_' . $topic->material->quiz->id);
        $quizDurationInSeconds = $topic->material->quiz->duration * 60;
        $attemptTime = now()->diffInSeconds($quizStartTime);

        $latePenaltyPoints = 0;

        if ($attemptTime > $quizDurationInSeconds) {
            $quizAttempt->is_late = 1;
            $lateDifferenceInSeconds = $attemptTime - $quizDurationInSeconds;

            $lateMinutes = ceil($lateDifferenceInSeconds / 60);
            $quizAttempt->difference = $lateMinutes;

            $latePenaltyPoints = $lateMinutes;
        }

        $correctAnswersCount = 0;
        $totalQuestionsCount = $questions->count();

        foreach ($questions as $question) {
            $questionId = $question->id;

            $selectedOption = $answers["question_$questionId"] ?? null;
            $isCorrect = false;

            if ($selectedOption) {
                $correctOption = $question->options->firstWhere('is_correct', true);
                if ($selectedOption == $correctOption->id) {
                    $isCorrect = true;
                    $correctAnswersCount += 1;
                }
            }

            QuestionAnswer::create([
                'question_id' => $questionId,
                'quiz_attempt_id' => $quizAttempt->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
            ]);
        }

        if ($totalQuestionsCount > 0) {
            $totalScore = ($correctAnswersCount / $totalQuestionsCount) * 100;
        }

        $totalScore -= $penaltyAmount + $latePenaltyPoints;

        $totalScore = max(0, $totalScore);

        $quizAttempt->update([
            'score' => $totalScore,
            'is_late' => $quizAttempt->is_late,
            'difference' => $quizAttempt->difference,
        ]);

        session()->forget('quiz_start_time_' . $topic->material->quiz->id);
        session()->forget('exitCount');

        return redirect()->route('course.read', ['course' => $course->slug, 'topic' => $request->nextTopic])->with('success', 'Jawaban berhasil dikirim!');
    }

    public function updateExitCount(Request $request)
    {
        $exitCount = $request->input('exitCount', 0);

        session(['exitCount' => $exitCount]);

        return response()->json(['success' => true]);
    }


    public function destroy(Course $course, Topic $topic)
    {
        $quizAttempt = QuizAttempt::where('quiz_id', $topic->material->quiz->id)->where('participant_id', Auth::user()->participant->id)->first();

        $quizAttempt->questionAnswers()->delete();

        $quizAttempt->delete();

        return redirect()->route('course.read', ['course' => $course->slug, 'topic' => $topic->slug])->with('success', 'Ujian diulangi!');
    }
}
