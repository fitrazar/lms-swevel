<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quiz;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->roles->pluck('name')[0] == 'author') {
                $quizzes = Quiz::all();
            } else {
                $quizzes = Quiz::whereHas('material.topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                    ->with('material.topic.course.instructors')
                    ->get();
            }

            return DataTables::of($quizzes)->make();
        }

        return view('admin.quiz.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $materials = Material::where('type', 'quiz')
                ->with(['topic.course'])
                ->get();
        } else {
            $materials = Material::where('type', 'quiz')
                ->whereHas('topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                ->with(['topic.course.instructors'])
                ->get();
        }


        return view('admin.quiz.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'material_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|min:5',
        ]);

        Quiz::create([
            'title' => $validatedData['title'],
            'material_id' => $validatedData['material_id'],
            'description' => $validatedData['description'],
            'duration' => $validatedData['duration'],
        ]);

        return redirect()->route('dashboard.quiz.index')->with('success', 'Kuis Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $materials = Material::where('type', 'quiz')
                ->with(['topic.course'])
                ->get();
        } else {
            $materials = Material::where('type', 'quiz')
                ->whereHas('topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                ->with(['topic.course.instructors'])
                ->get();
        }

        return view('admin.quiz.edit', compact('quiz', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $rules = [
            'material_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
            'duration' => 'required|min:5',
        ];

        $validatedData = $request->validate($rules);

        Quiz::findOrFail($quiz->id)->update([
            'title' => $validatedData['title'],
            'material_id' => $validatedData['material_id'],
            'description' => $validatedData['description'],
            'duration' => $validatedData['duration'],
        ]);

        return redirect()->route('dashboard.quiz.index')->with('success', 'Kuis Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        Quiz::destroy($quiz->id);

        return response()->json([
            'success' => true,
            'message' => 'Kuis berhasil dihapus.'
        ]);
    }
}
