<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;  // import your Course model
use App\Models\Category;
use App\Models\Level;
use App\Models\User;
use App\Models\Comment;





class OnlineCourseController extends Controller
{


public function index()
{
    $title = 'لیست دوره‌ها';

    // Filters
    $levels = Level::select('id', 'name')->get();
    $categories = Category::select('id', 'name')->get();
    $teachers = User::whereHas('roles', function ($q) {
        $q->where('name', 'teacher');
    })->select('id', 'name')->get();

    $paymentTypes = [
        ['id' => 'free', 'name' => 'رایگان'],
        ['id' => 'paid', 'name' => 'پولی'],
    ];

    // ✅ Load all active courses with category
    $courses = Course::with('category')
        ->where('status', 'published')
        ->where('is_active', 1)
        ->latest()
        ->paginate(12);

    return view('frontend.pages.courses.index', compact(
        'title', 'levels', 'categories', 'teachers', 'paymentTypes', 'courses'
    ));
}



public function filter(Request $request)
{
    $query = Course::query()->with('category');

    if ($request->filled('searchItem')) {
        $query->where('title', 'like', '%' . $request->searchItem . '%');
    }

    if ($request->filled('level_id')) {
        $query->whereIn('level_id', $request->level_id);
    }

    if ($request->filled('category_id')) {
        $query->whereIn('category_id', $request->category_id);
    }

    if ($request->filled('user_id')) {
        $query->whereIn('user_id', $request->user_id);
    }

    if ($request->filled('price')) {
        $query->where(function ($q) use ($request) {
            if (in_array('free', $request->price)) {
                $q->orWhere('price', 0);
            }
            if (in_array('paid', $request->price)) {
                $q->orWhere('price', '>', 0);
            }
        });
    }

    $courses = $query->latest()->paginate(8);
    $count = $courses->count();

    return view('frontend.pages.courses.courses-filtered', compact('courses', 'count'))->render();
}


public function showCourse($slug)
{
    $course = Course::where('slug', $slug)->firstOrFail();
    $latestCourses = Course::latest()->take(5)->get();
    $categories = Category::all();

    // Get course comments
    $comments = Comment::where('course_id', $course->id)->get();
    $lessons = $course->lessons; // assuming relationship exists
    $averageRating = Comment::where('course_id', $course->id)->avg('rate') ?? 0;

        return view('frontend.pages.courses.show', [
            'course' => $course,
            'title' => $course->title,
            'latestCourses' => $latestCourses,
            'categories' => $categories,
            'comments' => $comments,
            'averageRating' => $averageRating,  // <-- fix here
        ]);

}
}

