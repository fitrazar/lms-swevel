<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Option;
use App\Models\Result;
use App\Models\Progress;
use App\Models\Question;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        if ($topic->material->type == 'quiz' && $topic->material->quiz) {
            // session()->forget('quiz_start_time_' . $topic->material->quiz->id);
            // session()->forget('exitCount');
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


        $userProgress = Progress::where('participant_id', Auth::user()?->participant?->id)
            ->where('topic_id', $currentTopic?->id)
            ->orWhere('topic_id', $prevTopic?->id)
            ->orWhere('topic_id', $nextTopic?->id)
            // ->whereIn('is_completed', [0, 1])
            ->first();
        if ($userProgress) {
            $userProgress->update([
                'topic_id' => $currentTopic->id,
            ]);
            // if ($nextTopic) {
            //     $userProgress->update([
            //         'topic_id' => $currentTopic->id
            //     ]);
            //     // dd('in');
            // } else {
            //     $userProgress->update([
            //         'topic_id' => $currentTopic->id,
            //     ]);
            // }
        } else {
            // $checkCompleted = Progress::where('participant_id', Auth::user()->participant->id)
            //     ->where('is_completed', 1)->pluck('topic_id')->toArray();
            $checkTopic = Topic::all()->pluck('id')->toArray();
            $currentUserProgress = Progress::where('participant_id', Auth::user()?->participant?->id)->whereHas('topic.course', function ($query) use ($course) {
                $query->where('id', $course->id);
            })->first();

            // $commonValues = array_intersect($checkCompleted, $checkTopic);

            // dd(vars: $currentTopic->id);
            if (in_array($currentUserProgress?->topic_id, $checkTopic)) {
                Progress::where('participant_id', Auth::user()?->participant?->id)->where('topic_id', $currentUserProgress->topic_id)
                    ->update([
                        'topic_id' => $currentTopic->id,
                    ]);
            } else {
                Progress::create([
                    'participant_id' => Auth::user()?->participant?->id,
                    'topic_id' => $currentTopic->id,
                    'is_completed' => 0
                ]);
            }
            // if (!empty($commonValues)) {
            //     dd($commonValues);
            //     // Progress::where('participant_id', Auth::user()->participant->id)->where('topic_id', )->update()

            // } else {
            //     dd('notfound');
            //     Progress::create([
            //         'participant_id' => Auth::user()->participant->id,
            //         'topic_id' => $currentTopic->id,
            //         'is_completed' => 0
            //     ]);
            // }
        }
        $isOpen = true;

        return view('participant.course.read', compact('course', 'currentTopic', 'prevTopic', 'nextTopic', 'startTime', 'isOpen'));
    }

    public function completed(Course $course, Topic $topic)
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

        Result::create([
            'quiz_id' => $topic->material->quiz->id,
            'participant_id' => Auth::user()->participant->id,
            'score' => $totalScore,
            'graded_at' => now(),
            'is_late' => $quizAttempt->is_late,
            'difference' => $quizAttempt->difference,
        ]);


        session()->forget('quiz_start_time_' . $topic->material->quiz->id);
        session()->forget(keys: 'exitCount');
        session()->forget(keys: 'answers');

        $lastTopicOrder = $course->topics()->max('order');
        if ($topic->order == $lastTopicOrder) {
            Progress::where('participant_id', Auth::user()->participant->id)
                ->where('topic_id', $topic->id)
                ->update(['is_completed' => 1]);

            return redirect()->back()->with('success', 'Jawaban berhasil dikirim!');
        }



        return redirect()->route('course.read', ['course' => $course->slug, 'topic' => $request->nextTopic])->with('success', 'Jawaban berhasil dikirim!');
    }

    public function updateExitCount(Request $request)
    {
        $exitCount = $request->input('exitCount', 0);

        session(['exitCount' => $exitCount]);

        return response()->json(['success' => true]);
    }

    public function updateAnswer(Request $request)
    {
        $questionId = $request->input('question');
        $optionId = $request->input('checked');

        $answers = session('answers', []);

        $answers[$questionId] = $optionId;

        session(['answers' => $answers]);

        return response()->json(['success' => true]);
    }

    public function destroy(Course $course, Topic $topic)
    {
        session()->forget('quiz_start_time_' . $topic->material->quiz->id);
        session()->forget(keys: 'exitCount');
        session()->forget(keys: 'answers');


        $quizAttempt = QuizAttempt::where('quiz_id', $topic->material->quiz->id)->where('participant_id', Auth::user()->participant->id)->first();
        $result = Result::where('quiz_id', $topic->material->quiz->id)->where('participant_id', Auth::user()->participant->id)->first();

        $quizAttempt->questionAnswers()->delete();

        $quizAttempt->delete();
        $result->delete();

        return redirect()->route('course.read', ['course' => $course->slug, 'topic' => $topic->slug])->with('success', 'Ujian diulangi!');
    }

    public function assignment(Request $request, Course $course, Topic $topic)
    {
        $validatedData = $request->validate([
            'file' => 'required|mimes:rar,zip|max:10000',
        ]);

        $parseDate = Carbon::parse(
            Auth::user()->participant?->enrolls?->where('course_id', $course->id)->first()?->activated_at
        );

        $deadline = $parseDate->addDays((int) $topic->material->assignment->deadline)->endOfDay();

        $isLate = 0;
        $lateMinutes = 0;

        if (now()->gte($deadline)) {
            $isLate = 1;
            $lateDifferenceInSeconds = now()->diffInSeconds($deadline);
            $lateMinutes = ceil($lateDifferenceInSeconds / 60);
        }

        $path = 'assignments/' . Auth::user()->participant->id . '/' . $topic->material->assignment->id;
        $validatedData['file'] = time() . '.' . $request->file('file')->getClientOriginalExtension();
        $request->file('file')->storeAs($path, $validatedData['file']);


        $result = new Result();
        $result->participant_id = Auth::user()->participant->id;
        $result->assignment_id = $topic->material->assignment->id;
        $result->file_url = $validatedData['file'];
        $result->is_late = $isLate;
        $result->difference = $lateMinutes;
        $result->save();

        $lastTopicOrder = $course->topics()->max('order');
        if ($topic->order == $lastTopicOrder) {
            Progress::where('participant_id', Auth::user()->participant->id)
                ->where('topic_id', $topic->id)
                ->update(['is_completed' => 1]);

            return redirect()->back()->with('success', 'File berhasil dikirim!');
        }



        return redirect()->route('course.read', ['course' => $course->slug, 'topic' => $request->nextTopic])->with('success', 'File berhasil dikirim!');
    }

    public function destroyAssignment(Course $course, Topic $topic)
    {
        Storage::delete('assignments/' . Auth::user()->participant->id . '/' . $topic->material->assignment->id);
        $result = Result::where('assignment_id', $topic->material->assignment->id)->where('participant_id', Auth::user()->participant->id)->first();

        $result->delete();

        return redirect()->route('course.read', ['course' => $course->slug, 'topic' => $topic->slug])->with('success', 'Tugas Berhasil Dihapus!');
    }
}
