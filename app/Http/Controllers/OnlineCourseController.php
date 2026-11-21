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


public function index(Request $request)
{
    $title = 'دوره‌ها';

    $query = Course::query()
        ->with(['category:id,name', 'user:id,name,family'])
        ->where('status', 'published')
        ->where('is_active', 1);

    if ($request->filled('q')) {
        $term = '%'.trim((string) $request->q).'%';
        $query->where(function ($builder) use ($term) {
            $builder->where('title', 'like', $term)
                ->orWhere('short_description', 'like', $term)
                ->orWhere('description', 'like', $term)
                ->orWhereHas('category', fn ($q) => $q->where('name', 'like', $term));
        });
    }

    if ($request->filled('category')) {
        $query->whereIn('category_id', (array) $request->category);
    }

    if ($request->filled('level')) {
        $query->whereIn('level_id', (array) $request->level);
    }

    if ($request->filled('price')) {
        $prices = (array) $request->price;
        $query->where(function ($builder) use ($prices) {
            if (in_array('free', $prices, true)) {
                $builder->orWhere('price', 0)->orWhereNull('price');
            }
            if (in_array('paid', $prices, true)) {
                $builder->orWhere('price', '>', 0);
            }
        });
    }

    $courses = $query->latest('id')->paginate(9)->appends($request->query());

    $filters = [
        'category' => Category::query()->select('id', 'name')->orderBy('name')->get(),
        'level' => Level::query()->select('id', 'name')->orderBy('name')->get(),
        'price' => [
            ['id' => 'free', 'name' => 'رایگان'],
            ['id' => 'paid', 'name' => 'پولی'],
        ],
    ];

    return view('frontend.pages.courses.index', compact('title', 'courses', 'filters'));
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

