<?php

namespace App\Http\Controllers\Participant;

use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
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

        $meetings = Meeting::join('meeting_schedules', 'meetings.id', '=', 'meeting_schedules.meeting_id')
            ->join('courses', 'meetings.course_id', '=', 'courses.id')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->select('meetings.*', 'meeting_schedules.day', 'meeting_schedules.start_time')
            ->where('enrollments.participant_id', Auth::user()->participant->id)
            ->where('enrollments.status', 'active')
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

        return view('participant.meeting.index', compact('meetings', 'search'));
    }
}
