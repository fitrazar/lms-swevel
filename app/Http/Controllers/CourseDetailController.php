<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class CourseDetailController extends Controller
{
    public function  show(Course $course)
    {
        if (!$course) {
            abort(404, 'Course not found');
        }

        return view("CourseDetailController", compact("course"));
    } 
}
