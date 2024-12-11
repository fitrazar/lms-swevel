<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quiz;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            if (Auth::user()->roles->pluck('name')[0] == 'author') {
                $questions = Question::all();
            } else {
                $questions = Question::whereHas('quiz.material.topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                    ->with('quiz.material.topic.course.instructors')
                    ->get();
            }


            return DataTables::of($questions)
                ->make();
        }
        return view(view: 'admin.question.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $quizzes = Quiz::all();
        } else {
            $quizzes = Quiz::whereHas('material.topic.course', function ($query) {
                $query->where('instructor_id', Auth::user()->instructor->id);
            })
                ->with('material.topic.course')
                ->get();
        }

        return view('admin.question.create', compact('quizzes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.options' => 'array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.is_correct' => 'boolean',
        ]);

        DB::beginTransaction();


        try {
            foreach ($validated['questions'] as $questionData) {
                $question = Question::create([
                    'quiz_id' => $validated['quiz_id'],
                    'question_text' => $questionData['question_text'],
                ]);

                if (!empty($questionData['options'])) {
                    foreach ($questionData['options'] as $optionData) {

                        Option::create([
                            'question_id' => $question->id,
                            'option_text' => $optionData['option_text'],
                            'is_correct' => $optionData['is_correct'] ?? false,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Questions successfully added.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to add questions.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        return view('admin.question.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'correct_option' => 'required|numeric|min:0',
        ]);

        $question = Question::findOrFail($question->id);
        $question->update(['question_text' => $request->question_text]);

        foreach ($request->options as $index => $option) {
            $questionOption = Option::find($option['id']);
            $questionOption->update([
                'option_text' => $option['option_text'],
                'is_correct' => ($request->correct_option == $index),
            ]);
        }

        return redirect()->route('dashboard.question.index')->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->options()->delete();
        $question->delete();

        return redirect()->route('dashboard.question.index')->with('success', 'Pertanyaan berhasil dihapus!');
    }

    public function createWithQuiz(Quiz $quiz)
    {
        return view('admin.question.createWithQuiz', compact('quiz'));
    }
}
