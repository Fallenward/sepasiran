<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CoursePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
            ->withCount(['parts', 'lessons'])
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

        return $this->form(new Course(), 'create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach'), 403);

        $data = $this->validatedMeta($request);

        $course = DB::transaction(function () use ($request, $user, $data) {
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

            $this->syncCurriculum($request, $course);

            return $course;
        });

        return redirect()
            ->route('coaches.courses.edit', $course)
            ->with('success', 'دوره با بخش‌ها و درس‌ها ایجاد شد.');
    }

    public function edit(Course $course): View
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach') && (int) $course->user_id === (int) $user->id, 403);

        $course->load(['parts.lessons', 'lessons']);

        return $this->form($course, 'edit');
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach') && (int) $course->user_id === (int) $user->id, 403);

        $data = $this->validatedMeta($request);

        DB::transaction(function () use ($request, $course, $data) {
            $course->forceFill(array_merge($data, [
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'has_certificate' => $request->boolean('has_certificate', false),
                'status' => $data['status'] ?? $course->status,
            ]))->save();

            $this->syncCurriculum($request, $course);
        });

        return redirect()
            ->route('coaches.courses.edit', $course)
            ->with('success', 'دوره و محتوای ویدیویی به‌روزرسانی شد.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user->hasRole('coach') && (int) $course->user_id === (int) $user->id, 403);

        foreach ($course->lessons as $lesson) {
            $lesson->deleteVideoFile();
        }
        Storage::disk('public')->deleteDirectory('courses/'.$course->id);
        $course->delete();

        return redirect()
            ->route('coaches.courses.index')
            ->with('success', 'دوره حذف شد.');
    }

    private function form(Course $course, string $mode): View
    {
        $user = Auth::user();

        return view('frontend.pages.account.courses-form', [
            'user' => $user,
            'course' => $course,
            'categories' => Category::query()->orderBy('name')->get(),
            'levels' => Schema::hasTable('levels') ? DB::table('levels')->orderBy('id')->get() : collect(),
            'roleLabel' => $user->primaryRoleLabel(),
            'mode' => $mode,
            'initialParts' => $this->partsPayload($course),
        ]);
    }

    private function validatedMeta(Request $request): array
    {
        $request->validate([
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
            'parts' => ['nullable', 'array'],
            'parts.*.id' => ['nullable', 'integer'],
            'parts.*.title' => ['nullable', 'string', 'max:180'],
            'parts.*.lessons' => ['nullable', 'array'],
            'parts.*.lessons.*.id' => ['nullable', 'integer'],
            'parts.*.lessons.*.title' => ['nullable', 'string', 'max:180'],
            'parts.*.lessons.*.description' => ['nullable', 'string', 'max:2000'],
            'parts.*.lessons.*.duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'parts.*.lessons.*.is_preview' => ['nullable', 'boolean'],
            'parts.*.lessons.*.remove_video' => ['nullable', 'boolean'],
            'parts.*.lessons.*.video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/x-msvideo', 'max:204800'],
        ], [
            'title.required' => 'عنوان دوره الزامی است.',
            'parts.*.lessons.*.video.mimetypes' => 'فرمت ویدیو باید mp4، webm یا mov باشد.',
            'parts.*.lessons.*.video.max' => 'حجم ویدیو نباید بیشتر از ۲۰۰ مگابایت باشد.',
        ]);

        return $request->only([
            'title', 'short_description', 'description', 'category_id', 'level_id',
            'price', 'discount_price', 'duration', 'video_demo_url', 'status',
        ]);
    }

    private function syncCurriculum(Request $request, Course $course): void
    {
        $partsInput = array_values($request->input('parts', []) ?: []);
        $keptPartIds = [];
        $keptLessonIds = [];

        foreach ($partsInput as $partIndex => $partData) {
            $partTitle = trim((string) ($partData['title'] ?? ''));
            $lessonsInput = array_values($partData['lessons'] ?? []);
            if ($partTitle === '' && $lessonsInput === []) {
                continue;
            }
            if ($partTitle === '') {
                $partTitle = 'بخش '.($partIndex + 1);
            }

            $partId = ! empty($partData['id']) ? (int) $partData['id'] : null;
            $part = null;
            if ($partId) {
                $part = CoursePart::query()
                    ->where('course_id', $course->id)
                    ->where('id', $partId)
                    ->first();
            }
            if (! $part) {
                $part = new CoursePart(['course_id' => $course->id]);
            }
            $part->forceFill([
                'title' => $partTitle,
                'sort_order' => $partIndex + 1,
            ])->save();
            $keptPartIds[] = $part->id;

            foreach ($lessonsInput as $lessonIndex => $lessonData) {
                $lessonTitle = trim((string) ($lessonData['title'] ?? ''));
                /** @var UploadedFile|null $video */
                $video = $request->file("parts.$partIndex.lessons.$lessonIndex.video");
                if ($lessonTitle === '' && ! $video && empty($lessonData['id'])) {
                    continue;
                }
                if ($lessonTitle === '') {
                    $lessonTitle = 'درس '.($lessonIndex + 1);
                }

                $lessonId = ! empty($lessonData['id']) ? (int) $lessonData['id'] : null;
                $lesson = null;
                if ($lessonId) {
                    $lesson = CourseLesson::query()
                        ->where('course_id', $course->id)
                        ->where('id', $lessonId)
                        ->first();
                }
                if (! $lesson) {
                    $lesson = new CourseLesson(['course_id' => $course->id]);
                }

                $lesson->forceFill([
                    'course_part_id' => $part->id,
                    'title' => $lessonTitle,
                    'description' => $lessonData['description'] ?? null,
                    'sort_order' => $lessonIndex + 1,
                    'duration_minutes' => $lessonData['duration_minutes'] ?? null,
                    'is_preview' => ! empty($lessonData['is_preview']),
                ]);

                if (! empty($lessonData['remove_video']) && $lesson->exists) {
                    $lesson->deleteVideoFile();
                    $lesson->video_path = null;
                    $lesson->video_url = null;
                    $lesson->video_filename = null;
                    $lesson->video_mime = null;
                    $lesson->video_size = null;
                }

                if ($video instanceof UploadedFile) {
                    if ($lesson->exists) {
                        $lesson->deleteVideoFile();
                    }
                    $path = $video->store('courses/'.$course->id.'/lessons', 'public');
                    $lesson->video_path = $path;
                    $lesson->video_url = '/storage/'.$path;
                    $lesson->video_filename = $video->getClientOriginalName();
                    $lesson->video_mime = $video->getClientMimeType();
                    $lesson->video_size = $video->getSize();
                }

                $lesson->save();
                $keptLessonIds[] = $lesson->id;
            }
        }

        $obsoleteLessonsQuery = CourseLesson::query()->where('course_id', $course->id);
        if ($keptLessonIds !== []) {
            $obsoleteLessonsQuery->whereNotIn('id', $keptLessonIds);
        }
        foreach ($obsoleteLessonsQuery->get() as $lesson) {
            $lesson->deleteVideoFile();
            $lesson->delete();
        }

        $obsoletePartsQuery = CoursePart::query()->where('course_id', $course->id);
        if ($keptPartIds !== []) {
            $obsoletePartsQuery->whereNotIn('id', $keptPartIds);
        }
        $obsoletePartsQuery->delete();
    }

    private function partsPayload(Course $course): array
    {
        if (! $course->exists) {
            return [[
                'id' => null,
                'title' => 'بخش ۱',
                'lessons' => [[
                    'id' => null,
                    'title' => '',
                    'description' => '',
                    'duration_minutes' => null,
                    'is_preview' => false,
                    'has_video' => false,
                    'video_name' => null,
                    'video_url' => null,
                ]],
            ]];
        }

        $parts = $course->parts;
        if ($parts->isEmpty()) {
            return [[
                'id' => null,
                'title' => 'بخش ۱',
                'lessons' => $course->lessons->map(fn (CourseLesson $lesson) => $this->lessonPayload($lesson))->values()->all()
                    ?: [[
                        'id' => null,
                        'title' => '',
                        'description' => '',
                        'duration_minutes' => null,
                        'is_preview' => false,
                        'has_video' => false,
                        'video_name' => null,
                        'video_url' => null,
                    ]],
            ]];
        }

        return $parts->map(function (CoursePart $part) {
            return [
                'id' => $part->id,
                'title' => $part->title,
                'lessons' => $part->lessons->map(fn (CourseLesson $lesson) => $this->lessonPayload($lesson))->values()->all()
                    ?: [[
                        'id' => null,
                        'title' => '',
                        'description' => '',
                        'duration_minutes' => null,
                        'is_preview' => false,
                        'has_video' => false,
                        'video_name' => null,
                        'video_url' => null,
                    ]],
            ];
        })->values()->all();
    }

    private function lessonPayload(CourseLesson $lesson): array
    {
        return [
            'id' => $lesson->id,
            'title' => $lesson->title,
            'description' => $lesson->description,
            'duration_minutes' => $lesson->duration_minutes,
            'is_preview' => (bool) $lesson->is_preview,
            'has_video' => (bool) ($lesson->video_path || $lesson->video_url),
            'video_name' => $lesson->video_filename,
            'video_url' => $lesson->videoPublicUrl(),
        ];
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