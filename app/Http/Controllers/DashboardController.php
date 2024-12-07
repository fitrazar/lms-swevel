<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function participant()
    {
        $user = Auth::user();

        $participant = $user->participant;

        if ($participant) {
            $activeCourses = Enrollment::where('status', 'active')
                ->where('participant_id', $participant->id)
                ->with('course')
                ->get()
                ->pluck('course');
        } else {
            $activeCourses = collect();
        }

        return view('dashboard', compact('activeCourses'));
    }
}
