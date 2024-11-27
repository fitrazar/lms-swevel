<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $coruses = Course::all();


            return DataTables::of($coruses)->make();
        }

        return view('admin.course.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.course.create', [
            'instructors' => Instructor::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'instructors' => 'required',
            'title' => 'required|max:255',
            'slug' => 'required|string|unique:courses,slug',
            'cover' => 'required|image|max:4096',
            'description' => 'required|max:900',
            'duration' => 'sometimes|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($request->hasFile('cover')) {
            $validatedData['cover'] = time() . '.' . $request->file('cover')->getClientOriginalExtension();
            $request->file('cover')->storeAs('course', $validatedData['cover']);
        }
        $validatedData['excerpt'] = Str::limit(strip_tags($request->description), 70);

        $course = Course::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'cover' => $validatedData['cover'],
            'description' => $validatedData['description'],
            'excerpt' => $validatedData['excerpt'],
            'duration' => $validatedData['duration'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
        ]);

        if ($request->has(key: 'instructors')) {
            $course->instructors()->attach($request->instructors);
        }

        return redirect()->route('dashboard.admin.course.index')->with('success', 'Kursus Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('admin.course.edit', [
            'instructors' => Instructor::latest()->get(),
            'course' => $course
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $rules = [
            'instructors' => 'required',
            'title' => 'required|max:255',
            'slug' => 'required|string|unique:courses,slug,' . $course->id,
            'cover' => 'nullable|image|max:4096',
            'description' => 'required',
            'duration' => 'sometimes|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];

        $validatedData = $request->validate($rules);

        $validatedData['cover'] = $request->oldImage;
        if ($request->file('cover')) {
            $path = 'course';
            if ($request->oldImage) {
                Storage::delete($path . '/' . $request->oldImage);
            }
            $validatedData['cover'] = time() . '.' . $request->file('cover')->getClientOriginalExtension();
            $request->file('cover')->storeAs($path, $validatedData['cover']);
        }

        $validatedData['excerpt'] = Str::limit(strip_tags($request->description), 70);

        Course::findOrFail($course->id)->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'cover' => $validatedData['cover'],
            'description' => $validatedData['description'],
            'excerpt' => $validatedData['excerpt'],
            'duration' => $validatedData['duration'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
        ]);

        if ($request->has('instructors')) {
            $course->instructors()->sync((array) $request->input('instructors'));
        }

        return redirect()->route('dashboard.admin.course.index')->with('success', 'Kursus Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if ($course->cover) {
            Storage::delete('course/' . $course->cover);
        }
        Course::destroy($course->id);

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dihapus.'
        ]);
    }
}
