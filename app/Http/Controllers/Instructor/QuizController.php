<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Quiz;
use App\Models\Result;
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
        $result = $attempt->quiz->results()->where('participant_id', $attempt->participant->id)->first();
        $questions = Question::with('options')->where('quiz_id', $attempt->quiz->id)->get();
        $userAnswers = QuestionAnswer::where('quiz_attempt_id', $attempt->id)->pluck('selected_option')->toArray();

        return view('instructor.quiz.show', compact('attempt', 'questions', 'userAnswers', 'result'));
    }

    public function destroy(QuizAttempt $attempt)
    {
        $result = Result::where('quiz_id', $attempt->quiz->id)->first();

        $attempt->questionAnswers()->delete();

        $attempt->delete();
        $result->delete();

        return redirect()->route('dashboard.instructor.quiz.result')->with('success', 'Hasil Kuis Berhasil Dihapus!');
    }

    public function feedback(Quiz $quiz, Result $result)
    {
        return view('instructor.quiz.feedback', compact('quiz', 'result'));
    }

    public function storeFeedback(Request $request, Quiz $quiz, Result $result)
    {
        $validatedData = $request->validate([
            'feedback' => 'required',
        ]);

        Result::where('id', $result->id)->update([
            'feedback' => $validatedData['feedback'],
        ]);

        return redirect()->route('dashboard.instructor.quiz.result')->with('success', 'Catatan Berhasil Ditambahkan!');
    }

    public function editFeedback(Quiz $quiz, Result $result)
    {
        return view('instructor.quiz.editFeedback', compact('quiz', 'result'));
    }

    public function updateFeedback(Request $request, Quiz $quiz, Result $result)
    {
        $validatedData = $request->validate([
            'feedback' => 'required',
        ]);

        Result::where('id', $result->id)->update([
            'feedback' => $validatedData['feedback'],
        ]);

        return redirect()->route('dashboard.instructor.quiz.result')->with('success', 'Catatan Berhasil Diupdate!');
    }

    public function deleteFeedback(Quiz $quiz, Result $result)
    {
        Result::where('id', $result->id)->update([
            'feedback' => null,
        ]);

        return redirect()->route('dashboard.instructor.quiz.result')->with('success', 'Catatan Berhasil Dihapus!');
    }
}
