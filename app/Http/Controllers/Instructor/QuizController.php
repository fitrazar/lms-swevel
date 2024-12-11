<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $quizAttempts = QuizAttempt::whereHas('quiz.material.topic.course.instructors', function ($query) {
                $query->where('instructor_id', Auth::user()->instructor->id);
            })->with('quiz.material.topic.course.instructors')
                ->get();
            ;

            return DataTables::of($quizAttempts)
                ->make();
        }
        return view('instructor.quiz.result');
    }

    public function show(QuizAttempt $attempt)
    {
        $questions = Question::with('options')->where('quiz_id', $attempt->quiz->id)->get();
        $userAnswers = QuestionAnswer::where('quiz_attempt_id', $attempt->id)->pluck('selected_option')->toArray();

        return view('instructor.quiz.show', compact('attempt', 'questions', 'userAnswers'));
    }

    public function destroy(QuizAttempt $attempt)
    {
        $attempt->questionAnswers()->delete();

        $attempt->delete();

        return response()->json(['message' => 'Hasil kuis berhasil dihapus!']);
    }
}
