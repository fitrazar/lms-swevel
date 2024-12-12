<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = null;
        $filter = null;
        if (Auth::user()->roles->pluck('name')[0] == 'participant') {
            $search = $request->input('search');
            $filter = $request->input('filter');
            $activeCourses = Course::query()
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->when($filter == 'active', function ($query) {
                    return $query->whereHas('enrolls', function ($query) {
                        $query->where('status', 'active');
                    });
                })
                ->when($filter == 'inactive', function ($query) {
                    return $query->whereHas('enrolls', function ($query) {
                        $query->where('status', 'inactive');
                    });
                })
                ->whereHas('enrolls', function ($query) {
                    $query->where('participant_id', Auth::user()->participant->id);
                })
                ->latest()
                ->paginate(9);

        } else {
            $activeCourses = collect();
        }

        return view('dashboard', compact('activeCourses', 'search', 'filter'));
    }
}
