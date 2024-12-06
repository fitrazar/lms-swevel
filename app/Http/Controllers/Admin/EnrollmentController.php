<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EnrollmentController extends Controller
{
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
            $enrolls = Enrollment::query();
            if ($request->has('kursus') && $request->input('kursus') != 'All' && $request->input('kursus') != NULL) {
                $course = $request->input('kursus');
                $enrolls->whereHas('course', function ($query) use ($course) {
                    $query->where('id', $course);
                })->with(['course'])->latest()->get();
            } else {
                $enrolls->whereHas('course')->with(['course'])->latest()->get();
            }

            return DataTables::of($enrolls)->make();
        }

        return view('admin.enrollment.index', compact('courses'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $enrollment->status = 'active';
        $enrollment->save();

        return redirect()->route('dashboard.enrollment.index')->with('success', 'Data Berhasil Diupdate');
    }

    public function updateAll(Request $request)
    {
        Enrollment::query()->update(['status' => 'active']);

        return redirect()->route('dashboard.enrollment.index')->with('success', 'Data Berhasil Diupdate');
    }

    public function destroy(Enrollment $enrollment)
    {
        Enrollment::destroy($enrollment->id);

        return redirect()->route('dashboard.enrollment.index')->with('success', 'Data Berhasil Dihapus!');
    }
}
