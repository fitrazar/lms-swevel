<?php

namespace App\Http\Controllers\Admin;

use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->roles->pluck('name')[0] == 'author') {
                $assignments = Assignment::all();
            } else {
                $assignments = Assignment::whereHas('material.topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                    ->with('material.topic.course.instructors')
                    ->get();
            }

            return DataTables::of($assignments)->make();
        }

        return view('admin.assignment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $materials = Material::where('type', 'assignment')
                ->with(['topic.course'])
                ->get();
        } else {
            $materials = Material::where('type', 'assignment')
                ->whereHas('topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                ->with(['topic.course.instructors'])
                ->get();
        }


        return view('admin.assignment.create', compact('materials'));
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
            'deadline' => 'required|numeric',
        ]);

        Assignment::create([
            'title' => $validatedData['title'],
            'material_id' => $validatedData['material_id'],
            'description' => $validatedData['description'],
            'deadline' => $validatedData['deadline'],
        ]);

        return redirect()->route('dashboard.assignment.index')->with('success', 'Tugas Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $materials = Material::where('type', 'assignment')
                ->with(['topic.course'])
                ->get();
        } else {
            $materials = Material::where('type', 'assignment')
                ->whereHas('topic.course.instructors', function ($query) {
                    $query->where('instructor_id', Auth::user()->instructor->id);
                })
                ->with(['topic.course.instructors'])
                ->get();
        }

        return view('admin.assignment.edit', compact('assignment', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $rules = [
            'material_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
            'deadline' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Assignment::findOrFail($assignment->id)->update([
            'title' => $validatedData['title'],
            'material_id' => $validatedData['material_id'],
            'description' => $validatedData['description'],
            'deadline' => $validatedData['deadline'],
        ]);

        return redirect()->route('dashboard.assignment.index')->with('success', 'Tugas Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        Assignment::destroy($assignment->id);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.'
        ]);
    }
}
