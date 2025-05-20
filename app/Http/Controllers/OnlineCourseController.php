<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;  // import your Course model

class OnlineCourseController extends Controller
{
    public function index()
    {
        // Eager load the user (instructor) relation to get their name
        $courses = Course::with('user:id,name')->paginate(10); // paginate if you want

        return view('online-courses.index', compact('courses'));
    }
}
