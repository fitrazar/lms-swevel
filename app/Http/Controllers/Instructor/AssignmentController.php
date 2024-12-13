<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AssignmentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $results = Result::whereHas('assignment.material.topic.course.instructors', function ($query) {
                $query->where('instructor_id', Auth::user()->instructor->id);
            })->with('assignment.material.topic.course.instructors')
                ->get();
            ;

            return DataTables::of($results)
                ->make();
        }
        return view('instructor.assignment.index');
    }

    public function show(Result $result)
    {
        return view('instructor.assignment.show', compact('result'));
    }

    public function create(Result $result)
    {
        return view('instructor.assignment.create', compact('result'));
    }

    public function store(Request $request, Result $result)
    {
        $validatedData = $request->validate([
            'score' => 'required|numeric|max:100',
            'feedback' => 'required',
        ]);

        Result::where('id', $result->id)->update([
            'feedback' => $validatedData['feedback'],
            'score' => $validatedData['score'],
            'graded_at' => now(),
        ]);

        return redirect()->route('dashboard.instructor.assignment.index')->with('success', 'Nilai Berhasil Ditambahkan!');
    }

    public function edit(Result $result)
    {
        return view('instructor.assignment.edit', compact('result'));
    }

    public function update(Request $request, Result $result)
    {
        $validatedData = $request->validate([
            'score' => 'required|numeric|max:100',
            'feedback' => 'required',
        ]);

        Result::where('id', $result->id)->update([
            'feedback' => $validatedData['feedback'],
            'score' => $validatedData['score'],
        ]);

        return redirect()->route('dashboard.instructor.assignment.index')->with('success', 'Nilai Berhasil Diupdate!');
    }

    public function destroy(Result $result)
    {
        $result->delete();

        return redirect()->route('dashboard.instructor.assignment.index')->with('success', 'Hasil Tugas Berhasil Dihapus!');
    }
}
