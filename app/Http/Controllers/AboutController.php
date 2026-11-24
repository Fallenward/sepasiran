<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Dog;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $coachCount = User::whereHas('roles', fn ($q) => $q->where('name', 'coach'))->count();
        $breederCount = User::whereHas('roles', fn ($q) => $q->where('name', 'breeder'))->count();
        $clinicCount = User::whereHas('roles', fn ($q) => $q->where('name', 'clinic'))->count();
        $dogCount = Dog::count();

        $statistics = [
            ['title' => 'مربی', 'counter' => $coachCount, 'icon' => 'coach'],
            ['title' => 'پرورش‌دهنده', 'counter' => $breederCount, 'icon' => 'dogs'],
            ['title' => 'کلینیک', 'counter' => $clinicCount, 'icon' => 'clinic'],
            ['title' => 'سگ', 'counter' => $dogCount, 'icon' => 'dogs'],
        ];

        $blogs = Blog::query()->where('type', 'about-us')->latest()->get();
        $latestBlog = $blogs->first();

        return view('about.about-us', compact('latestBlog', 'blogs', 'statistics'));
    }

    public function contact(): View
    {
        return view('about.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'نام را وارد کنید.',
            'email.required' => 'ایمیل را وارد کنید.',
            'phone.required' => 'شماره تماس را وارد کنید.',
            'message.required' => 'پیام را بنویسید.',
        ]);

        SupportMessage::query()->create($validated);

        return redirect()
            ->route('frontend.contact.index')
            ->with('success', 'پیام شما با موفقیت ارسال شد.');
    }

    public function support(): View
    {
        $blog = Blog::query()->where('type', 'support')->latest()->first();

        return view('about.support', compact('blog'));
    }

    public function storeSupport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::query()->create($validated);

        return back()->with('success', 'پیام پشتیبانی شما با موفقیت ارسال شد.');
    }
}