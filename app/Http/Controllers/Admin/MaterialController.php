<?php

namespace App\Http\Controllers\Admin;

use App\Models\Topic;
use App\Models\Course;
use App\Models\Material;
use App\Models\Instructor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $courses = Course::orderBy('title')->get();
        } else {
            $courses = Instructor::with('courses')
                ->find(Auth::user()->instructor->id)
                ->courses ?? collect();
        }

        if ($request->ajax()) {
            $materials = Material::query();
            if (Auth::user()->roles->pluck('name')[0] == 'author') {
                if ($request->has('kursus') && $request->input('kursus') != 'All' && $request->input('kursus') != NULL) {
                    $course = $request->input('kursus');
                    $materials->whereHas('topic.course', function ($query) use ($course) {
                        $query->where('id', $course);
                    })->with(['topic.course'])->latest()->get();
                } else {
                    $materials->whereHas('topic.course')->with(['topic.course'])->latest()->get();
                }
            } else {
                if ($request->has('kursus') && $request->input('kursus') != 'All' && $request->input('kursus') != NULL) {
                    $course = $request->input('kursus');
                    $materials->whereHas('topic.course.instructors', function ($query) use ($course) {
                        $query->where('id', $course);
                    })->with(['topic.course'])->latest()->get();
                } else {
                    $materials->whereHas('topic.course.instructors', function ($query) {
                        $query->where('instructor_id', Auth::user()->instructor->id);
                    })->with(['topic.course'])->latest()->get();
                }
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
        $courses = null;
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $courses = Course::with('topics')->latest()->get();
        } else {
            $courses = Instructor::with('courses.topics')
                ->find(Auth::user()->instructor->id)
                ->courses ?? collect();
        }

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
            // 'slug' => [
            //     'required',
            //     Rule::unique('topics')->where(function ($query) use ($request) {
            //         return $query->where('course_id', $request->course_id);
            //     }),
            // ],
            'order_topic' => 'required|numeric',
            'content' => 'required',
            'type' => 'required|in:document,video,assignment,quiz',
        ]);

        $baseSlug = Str::slug($request->title_topic);
        $slug = $baseSlug;

        $existingCount = Topic::where('slug', 'like', $baseSlug . '%')->count();
        if ($existingCount > 0) {
            $slug = $baseSlug . '-' . ($existingCount + 1);
        }

        $topic = Topic::create([
            'course_id' => $validatedData['course_id'],
            'title' => $validatedData['title_topic'],
            'slug' => $slug,
            'order' => $validatedData['order_topic'],
        ]);

        Material::create([
            'topic_id' => $topic->id,
            'content' => $validatedData['content'],
            'type' => $validatedData['type'],
        ]);

        return redirect()->route('dashboard.material.index')->with('success', 'Materi Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $courses = null;
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $courses = Course::with('topics')->get();
        } else {
            $courses = Instructor::with('courses.topics')
                ->find(Auth::user()->instructor->id)
                ->courses ?? collect();
        }
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
            // 'slug' => [
            //     'required',
            //     Rule::unique('topics')->where(function ($query) use ($request) {
            //         return $query->where('course_id', $request->course_id);
            //     })->ignore($request->topic_id),
            // ],
            'order_topic' => 'required|numeric',
            'content' => 'required',
            'type' => 'required|in:document,video,assignment,quiz',
        ]);


        $topic = Topic::findOrFail($request->topic_id);
        $baseSlug = Str::slug($request->title_topic);
        $slug = $baseSlug;

        $existingCount = Topic::where('slug', 'like', $baseSlug . '%')->where('id', '!=', $topic->id)->count();
        if ($existingCount > 0) {
            $slug = $baseSlug . '-' . ($existingCount + 1);
        }
        $topic->update([
            'course_id' => $validatedData['course_id'],
            'title' => $validatedData['title_topic'],
            'slug' => $slug,
            'order' => $validatedData['order_topic'],
        ]);
        $material->update([
            'topic_id' => $topic->id,
            'content' => $validatedData['content'],
            'type' => $validatedData['type'],
        ]);

        return redirect()->route('dashboard.material.index')->with('success', 'Materi Berhasil Diupdate!');
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
        $courses = null;
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $courses = Course::with('topics')->latest()->get();
        } else {
            $courses = Instructor::with('courses.topics')
                ->find(Auth::user()->instructor->id)
                ->courses ?? collect();
        }

        return view('admin.material.createWithCourse', compact('course', 'courses'));
    }
}
