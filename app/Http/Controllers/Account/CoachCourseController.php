<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CoachCourseController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach'), 403);

        $courses = Course::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('frontend.pages.account.courses-index', [
            'user' => $user,
            'courses' => $courses,
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }

    public function create(): View
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach'), 403);

        return view('frontend.pages.account.courses-form', [
            'user' => $user,
            'course' => new Course(),
            'categories' => Category::query()->orderBy('name')->get(),
            'levels' => Schema::hasTable('levels') ? DB::table('levels')->orderBy('id')->get() : collect(),
            'roleLabel' => $user->primaryRoleLabel(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach'), 403);

        $data = $this->validated($request);
        $course = new Course();
        $course->forceFill(array_merge($data, [
            'user_id' => $user->id,
            'slug' => $this->uniqueSlug($data['title']),
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'has_certificate' => $request->boolean('has_certificate', false),
            'status' => $data['status'] ?? 'published',
            'view_count' => 0,
        ]))->save();

        return redirect()
            ->route('coaches.courses.index')
            ->with('success', 'دوره با موفقیت ایجاد شد.');
    }

    public function edit(Course $course): View
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach') && (int) $course->user_id === (int) $user->id, 403);

        return view('frontend.pages.account.courses-form', [
            'user' => $user,
            'course' => $course,
            'categories' => Category::query()->orderBy('name')->get(),
            'levels' => Schema::hasTable('levels') ? DB::table('levels')->orderBy('id')->get() : collect(),
            'roleLabel' => $user->primaryRoleLabel(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach') && (int) $course->user_id === (int) $user->id, 403);

        $data = $this->validated($request);
        $course->forceFill(array_merge($data, [
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'has_certificate' => $request->boolean('has_certificate', false),
            'status' => $data['status'] ?? $course->status,
        ]))->save();

        return redirect()
            ->route('coaches.courses.index')
            ->with('success', 'دوره به‌روزرسانی شد.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach') && (int) $course->user_id === (int) $user->id, 403);
        $course->delete();

        return redirect()
            ->route('coaches.courses.index')
            ->with('success', 'دوره حذف شد.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer'],
            'level_id' => ['nullable', 'integer'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'video_demo_url' => ['nullable', 'url', 'max:255'],
            'status' => ['nullable', 'string', 'max:40'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'has_certificate' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'عنوان دوره الزامی است.',
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'course';
        $slug = $base;
        $i = 1;
        while (Course::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
