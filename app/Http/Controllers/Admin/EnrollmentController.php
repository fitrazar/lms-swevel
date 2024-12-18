<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Exports\EnrollmentExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ParticipantEnrollmentNotification;

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
            if (Auth::user()->roles->pluck('name')[0] == 'author') {
                if ($request->has('kursus') && $request->input('kursus') != 'All' && $request->input('kursus') != NULL) {
                    $course = $request->input('kursus');
                    $enrolls->whereHas('course', function ($query) use ($course) {
                        $query->where('id', $course);
                    })->with(['course'])->latest()->get();
                } else {
                    $enrolls->whereHas('course')->with(['course'])->latest()->get();
                }
            } else {
                if ($request->has('kursus') && $request->input('kursus') != 'All' && $request->input('kursus') != NULL) {
                    $course = $request->input('kursus');
                    $enrolls->whereHas('course.instructors', function ($query) use ($course) {
                        $query->where('id', $course);
                    })->with(['course'])->latest()->get();
                } else {
                    $enrolls->whereHas('course.instructors', function ($query) {
                        $query->where('instructor_id', Auth::user()->instructor->id);
                    })->with(['course'])->latest()->get();
                }
            }

            return DataTables::of($enrolls)->make();
        }

        return view('admin.enrollment.index', compact('courses'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $enrollment->status = 'active';
        $enrollment->activated_at = now();

        $enrollment->save();
        $enrollment->participant->user->notify(new ParticipantEnrollmentNotification($enrollment, 'Terdaftar', 'Kamu telah terdaftar di kursus ' . $enrollment->course->title));

        return redirect()->route('dashboard.enrollment.index')->with('success', 'Data Berhasil Diupdate');
    }

    public function updateAll(Request $request)
    {
        Enrollment::query()->update(['status' => 'active', 'activated_at' => now()]);

        return redirect()->route('dashboard.enrollment.index')->with('success', 'Data Berhasil Diupdate');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->participant->user->notify(new ParticipantEnrollmentNotification($enrollment, 'Ditolak', 'Kamu ditolak di kursus ' . $enrollment->course->title));
        Enrollment::destroy($enrollment->id);

        return redirect()->route('dashboard.enrollment.index')->with('success', 'Data Berhasil Dihapus!');
    }

    public function export(Request $request)
    {
        $kursus = $request->kursusExport;
        return Excel::download(new EnrollmentExport($kursus), 'Data Pendaftar.xlsx');
    }
}
