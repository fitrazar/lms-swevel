<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles->pluck('name')[0] == 'participant') {
            $activeCourses = Enrollment::where('participant_id', Auth::user()->participant->id)
                ->with('course')
                ->get()
                ->pluck('course');
        } else {
            $activeCourses = collect();
        }

        return view('dashboard', compact('activeCourses'));
    }
}
