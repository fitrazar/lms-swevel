<?php

namespace App\Http\Controllers\Admin;

use App\Models\Topic;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::orderBy('title')->get();

        if ($request->ajax()) {
            $materials = Material::query();
            if ($request->has('kursus') && $request->input('kursus') != 'All' && $request->input('kursus') != NULL) {
                $course = $request->input('kursus');
                $materials->whereHas('topic.course', function ($query) use ($course) {
                    $query->where('id', $course);
                })->with(['topic.course'])->latest()->get();
            } else {
                $materials->whereHas('topic.course')->with(['topic.course'])->latest()->get();
            }

            return DataTables::of($materials)->make();
        }

        return view('admin.material.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::with('topics')->latest()->get();

        return view('admin.material.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required',
            'title_topic' => 'required|max:255',
            'slug' => [
                'required',
                Rule::unique('topics')->where(function ($query) use ($request) {
                    return $query->where('course_id', $request->course_id);
                }),
            ],
            'order_topic' => 'required|numeric',
            'content' => 'required',
            'type' => 'required|in:document,video,assignment,quiz',
        ]);

        $topic = Topic::create([
            'course_id' => $validatedData['course_id'],
            'title' => $validatedData['title_topic'],
            'slug' => $validatedData['slug'],
            'order' => $validatedData['order_topic'],
        ]);

        Material::create([
            'topic_id' => $topic->id,
            'content' => $validatedData['content'],
            'type' => $validatedData['type'],
        ]);

        return redirect()->route('dashboard.admin.material.index')->with('success', 'Materi Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $courses = Course::with('topics')->get();
        $usedOrders = $material->topic->course->topics->pluck('order')->toArray();

        return view('admin.material.edit', compact('material', 'courses', 'usedOrders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validatedData = $request->validate([
            'course_id' => 'required',
            'title_topic' => 'required|max:255',
            'slug' => [
                'required',
                Rule::unique('topics')->where(function ($query) use ($request) {
                    return $query->where('course_id', $request->course_id);
                })->ignore($request->topic_id),
            ],
            'order_topic' => 'required|numeric',
            'content' => 'required',
            'type' => 'required|in:document,video,assignment,quiz',
        ]);

        $topic = Topic::findOrFail($request->topic_id);
        $topic->update([
            'course_id' => $validatedData['course_id'],
            'title' => $validatedData['title_topic'],
            'slug' => $validatedData['slug'],
            'order' => $validatedData['order_topic'],
        ]);
        $material->update([
            'topic_id' => $topic->id,
            'content' => $validatedData['content'],
            'type' => $validatedData['type'],
        ]);

        return redirect()->route('dashboard.admin.material.index')->with('success', 'Materi Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        Material::destroy($material->id);
        Topic::destroy($material->topic->id);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus.'
        ]);
    }

    public function createWithCourse(Course $course)
    {
        $courses = Course::with('topics')->latest()->get();

        return view('admin.material.createWithCourse', compact('course', 'courses'));
    }
}
