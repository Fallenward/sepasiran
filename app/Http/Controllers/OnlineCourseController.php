<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Level;
use App\Support\CoursePurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OnlineCourseController extends Controller
{
    public function index(Request $request): View
    {
        $title = 'دوره‌ها';

        $query = Course::query()
            ->with(['category:id,name'])
            ->where(function ($builder) {
                $builder->where('status', 'published')
                    ->orWhere('is_active', 1);
            });

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
            $query->where('title', 'like', '%'.$request->searchItem.'%');
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

    public function showCourse(string $slug): View
    {
        $course = Course::query()
            ->with([
                'category:id,name',
                'level:id,name',
                'users:id,name',
                'parts.lessons',
                'lessons',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $comments = Comment::query()->where('course_id', $course->id)->latest('id')->get();
        $averageRating = (float) (Comment::query()->where('course_id', $course->id)->avg('rate') ?? 0);

        $enrollment = null;
        if (Auth::check()) {
            $enrollment = CourseEnrollment::query()
                ->where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->first();
        }

        $parts = $course->parts;
        $orphanLessons = $course->lessons->whereNull('course_part_id')->values();
        $lessonCount = $course->lessons->count();
        $durationMinutes = (int) $course->lessons->sum('duration_minutes');
        if ($durationMinutes <= 0 && $course->duration) {
            $durationMinutes = (int) $course->duration;
        }

        $loginUrl = route('login', [
            'redirect' => route('frontend.courses.show', $course->slug),
        ]);

        return view('frontend.pages.courses.show', [
            'course' => $course,
            'title' => $course->title,
            'comments' => $comments,
            'averageRating' => $averageRating,
            'enrollment' => $enrollment,
            'parts' => $parts,
            'orphanLessons' => $orphanLessons,
            'lessonCount' => $lessonCount,
            'durationMinutes' => $durationMinutes,
            'loginUrl' => $loginUrl,
        ]);
    }

    public function purchase(Request $request, string $slug, CoursePurchaseService $purchase): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login', [
                'redirect' => route('frontend.courses.show', $slug),
            ]));
        }

        $course = Course::query()
            ->where('slug', $slug)
            ->where(function ($builder) {
                $builder->where('status', 'published')->orWhere('is_active', 1);
            })
            ->firstOrFail();

        $data = $request->validate([
            'payment_method' => ['nullable', 'in:wallet'],
        ]);

        try {
            $enrollment = $purchase->purchase(
                Auth::user(),
                $course,
                $data['payment_method'] ?? 'wallet'
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('account.course-enrollments.index')
            ->with('success', 'پرداخت انجام شد. دسترسی به دوره با کد '.$enrollment->enrollment_code.' فعال شد.');
    }

    public function myEnrollments(): View
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $enrollments = CourseEnrollment::query()
            ->where('user_id', $user->id)
            ->with(['course.category'])
            ->latest('id')
            ->paginate(12);

        $roleLabel = 'کاربر';
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('coach')) {
                $roleLabel = 'مربی';
            } elseif ($user->hasRole('breeder')) {
                $roleLabel = 'پرورش‌دهنده';
            } elseif ($user->hasRole('clinic') || $user->hasRole('vet')) {
                $roleLabel = 'کلینیک';
            }
        }

        return view('frontend.pages.account.course-enrollments', [
            'enrollments' => $enrollments,
            'user' => $user,
            'roleLabel' => $roleLabel,
        ]);
    }
}
