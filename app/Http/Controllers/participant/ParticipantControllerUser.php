<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class ParticipantControllerUser extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil participant terkait dengan user yang login
        $participant = $user->participant;  // Relasi antara User dan Participant

        // Jika participant ditemukan, ambil courses yang statusnya 'aktif' dan sesuai dengan participant_id
        if ($participant) {
            $activeCourses = Enrollment::where('status', 'active')
                ->where('participant_id', $participant->id)
                ->with('course')  // Pastikan relasi course dimuat
                ->get()
                ->pluck('course');  // Ambil hanya data course
        } else {
            $activeCourses = collect();  // Jika tidak ada participant, kembalikan collection kosong
        }

        return view('participant.dashboard', compact('activeCourses'));
    }
}
