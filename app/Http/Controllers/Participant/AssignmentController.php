<?php

namespace App\Http\Controllers\Participant;

use Carbon\Carbon;
use App\Models\Result;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $user = Auth::user()->participant;

            $results = Result::where('participant_id', $user->id)
                ->with('assignment.material.topic.course.enrolls')
                ->whereNotNull('assignment_id')
                ->get();

            return DataTables::of($results)->addColumn('deadline', function ($result) use ($user) {
                $parseDate = Carbon::parse(
                    Auth::user()->participant?->enrolls?->where('course_id', $result->assignment->material->topic->course->id)->first()?->activated_at
                );

                $deadline = $parseDate->addDays((int) $result->assignment->deadline)->endOfDay();

                return [
                    'deadline' => $deadline->toDateTimeString(),
                ];
            })
                ->toJson();
        }
        return view('participant.assignment.index');
    }

    public function result(Result $result)
    {
        $participant = Auth::user()->participant;

        if (!$result) {
            abort(404, 'Anda belum mengikuti kuis ini.');
        }

        return view('participant.assignment.result', compact('result'));
    }
}
