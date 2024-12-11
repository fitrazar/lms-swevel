<?php

namespace App\Http\Controllers\Participant;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $user = Auth::user()->participant;

            $quizzes = Quiz::whereHas('material.topic.course.enrolls', function ($query) use ($user) {
                $query->where('participant_id', $user->id)
                    ->where('status', 'active');
            })
                ->with([
                    'quizAttempts' => function ($query) use ($user) {
                        $query->where('participant_id', $user->id);
                    }
                ]);

            return DataTables::of($quizzes)
                ->addColumn('action', function ($quiz) use ($user) {
                    $attempt = $quiz->quizAttempts->first();

                    if ($attempt) {
                        return [
                            'status' => 'completed',
                            'id' => $quiz->id,
                            'score' => $attempt->score,
                        ];
                    }

                    return ['status' => 'not_started', 'id' => $quiz->id];
                })
                ->toJson();
        }
        return view('participant.quiz.index');
    }

    public function result(Quiz $quiz)
    {
        $participant = Auth::user()->participant;

        $attempt = $quiz->quizAttempts()->where('participant_id', $participant->id)->first();

        if (!$attempt) {
            abort(404, 'Anda belum mengikuti ujian ini.');
        }

        $answers = $attempt->questionAnswers()
            ->with('question')
            ->get();

        $result = $quiz->results()->where('participant_id', $participant->id)->first();
        $questions = Question::with('options')->where('quiz_id', $quiz->id)->get();
        $userAnswers = QuestionAnswer::where('quiz_attempt_id', $attempt->id)->pluck('selected_option')->toArray();

        return view('participant.quiz.result', compact('quiz', 'attempt', 'answers', 'questions', 'userAnswers', 'result'));
    }
}
