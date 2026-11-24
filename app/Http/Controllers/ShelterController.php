<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShelterController extends Controller
{
    public function index(): View
    {
        $dogs = Dog::query()
            ->with(['breed', 'city'])
            ->whereIn('status', ['available', 'ready'])
            ->latest()
            ->take(9)
            ->get();

        $availableCount = Dog::query()->whereIn('status', ['available', 'ready'])->count();
        $adoptedCount = Dog::query()->whereIn('status', ['owned', 'sold'])->count();
        $clinicCount = User::whereHas('roles', fn ($q) => $q->where('name', 'clinic'))->count();
        $coachCount = User::whereHas('roles', fn ($q) => $q->where('name', 'coach'))->count();

        $actions = [
            [
                'icon' => 'dogs',
                'title' => 'سگ‌های قابل پذیرش',
                'hint' => 'آگهی‌های آمادهٔ واگذاری را ببینید',
                'href' => route('frontend.dog.supply.index'),
                'cta' => 'مشاهده سگ‌ها',
            ],
            [
                'icon' => 'clinic',
                'title' => 'کلینیک و واکسن',
                'hint' => 'معاینه و مراقبت پزشکی بعد از پذیرش',
                'href' => route('frontend.clinics.index'),
                'cta' => 'کلینیک‌ها',
            ],
            [
                'icon' => 'coach',
                'title' => 'آموزش و رفتار',
                'hint' => 'مربی برای عادت‌دهی به خانه جدید',
                'href' => route('frontend.coaches.index'),
                'cta' => 'مربی‌ها',
            ],
            [
                'icon' => 'insurance',
                'title' => 'بیمه حیوان',
                'hint' => 'پوشش مدت‌دار پس از پذیرش',
                'href' => route('frontend.clinic.insurance.index'),
                'cta' => 'طرح‌های بیمه',
            ],
            [
                'icon' => 'cta',
                'title' => 'گزارش سگ بی‌سرپرست',
                'hint' => 'موقعیت و توضیح را برای تیم بفرستید',
                'href' => '#shelter-help',
                'cta' => 'ثبت گزارش',
            ],
            [
                'icon' => 'trust',
                'title' => 'داوطلب / حمایت',
                'hint' => 'حضور حضوری یا کمک مالی را اعلام کنید',
                'href' => '#shelter-help',
                'cta' => 'ثبت علاقه',
            ],
        ];

        return view('shelter.index', [
            'dogs' => $dogs,
            'availableCount' => $availableCount,
            'adoptedCount' => $adoptedCount,
            'clinicCount' => $clinicCount,
            'coachCount' => $coachCount,
            'actions' => $actions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'intent' => 'required|in:report,volunteer,donate,adopt',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:120',
            'message' => 'required|string|max:2000',
        ], [
            'intent.required' => 'نوع درخواست را انتخاب کنید.',
            'name.required' => 'نام را وارد کنید.',
            'email.required' => 'ایمیل را وارد کنید.',
            'phone.required' => 'شماره تماس را وارد کنید.',
            'message.required' => 'توضیح را بنویسید.',
        ]);

        $labels = [
            'report' => 'گزارش سگ بی‌سرپرست',
            'volunteer' => 'داوطلبی پناهگاه',
            'donate' => 'حمایت مالی پناهگاه',
            'adopt' => 'درخواست پذیرش',
        ];

        $parts = [
            '['.$labels[$validated['intent']].']',
        ];
        if (! empty($validated['city'])) {
            $parts[] = 'شهر: '.$validated['city'];
        }
        $parts[] = $validated['message'];

        SupportMessage::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => implode("\n", $parts),
        ]);

        return redirect()
            ->route('frontend.shelter.index')
            ->with('success', 'درخواست شما ثبت شد. تیم سپاسیران پیگیری می‌کند.');
    }
}