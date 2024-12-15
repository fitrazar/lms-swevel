<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Meeting;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $today = now()->dayOfWeek;
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $meetings = Meeting::join('meeting_schedules', 'meetings.id', '=', 'meeting_schedules.meeting_id')
                ->select('meetings.*', 'meeting_schedules.day', 'meeting_schedules.start_time')
                ->where(function ($query) use ($search) {
                    if (!empty($search)) {
                        $query->where('meeting_schedules.day', 'like', '%' . $search . '%');
                    }
                })
                ->orderByRaw("
            CASE
                WHEN meeting_schedules.day = ? THEN 0
                ELSE (FIND_IN_SET(meeting_schedules.day, ?) + 7 - ?) % 7
            END ASC,
            meeting_schedules.start_time ASC
        ", [$days[$today], implode(',', $days), $today])
                ->paginate(7);
        } else {
            $meetings = Meeting::join('meeting_schedules', 'meetings.id', '=', 'meeting_schedules.meeting_id')
                ->join('courses', 'meetings.course_id', '=', 'courses.id')
                ->join('course_instructors', 'courses.id', '=', 'course_instructors.course_id')
                ->select('meetings.*', 'meeting_schedules.day', 'meeting_schedules.start_time')
                ->where('course_instructors.instructor_id', Auth::user()->instructor->id)
                ->where(function ($query) use ($search) {
                    if (!empty($search)) {
                        $query->where('meeting_schedules.day', 'like', '%' . $search . '%');
                    }
                })
                ->orderByRaw("
            CASE
                WHEN meeting_schedules.day = ? THEN 0
                ELSE (FIND_IN_SET(meeting_schedules.day, ?) + 7 - ?) % 7
            END ASC,
            meeting_schedules.start_time ASC
        ", [$days[$today], implode(',', $days), $today])
                ->paginate(7);
        }

        if ($request->ajax()) {
            return view('partials.meeting', compact('meetings'));
        }

        return view('admin.meeting.index', compact('meetings', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $courses = null;
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $courses = Course::with('topics')->latest()->get();
        } else {
            $courses = Instructor::with('courses.topics')
                ->find(Auth::user()->instructor->id)
                ->courses ?? collect();
        }

        return view('admin.meeting.create', compact('courses', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required',
            'link' => 'required|max:255',
            'type' => 'required|max:255',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);


        $meeting = Meeting::create([
            'course_id' => $validatedData['course_id'],
            'link' => $validatedData['link'],
            'type' => $validatedData['type'],
        ]);

        MeetingSchedule::create([
            'meeting_id' => $meeting->id,
            'day' => $validatedData['day'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        return redirect()->route('dashboard.meeting.index')->with('success', 'Meet Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $courses = null;
        if (Auth::user()->roles->pluck('name')[0] == 'author') {
            $courses = Course::with('topics')->get();
        } else {
            $courses = Instructor::with('courses.topics')
                ->find(Auth::user()->instructor->id)
                ->courses ?? collect();
        }

        return view('admin.meeting.edit', compact('meeting', 'courses', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        $validatedData = $request->validate([
            'course_id' => 'required',
            'link' => 'required|max:255',
            'type' => 'required|max:255',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $meeting->update([
            'course_id' => $validatedData['course_id'],
            'link' => $validatedData['link'],
            'type' => $validatedData['type'],
        ]);
        MeetingSchedule::where('meeting_id', $meeting->id)->update([
            'day' => $validatedData['day'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        return redirect()->route('dashboard.meeting.index')->with('success', 'Meet Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        Meeting::destroy($meeting->id);

        return response()->json([
            'success' => true,
            'message' => 'Meet berhasil dihapus.'
        ]);
    }
}
