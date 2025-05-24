<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog; // Fix: Capital "B" in Blog
use App\Models\User;
use App\Models\Dog;
use App\Models\SupportMessage;


class AboutController extends Controller
{
public function index()
{
    $coachCount = User::whereHas('roles', fn($q) => $q->where('name', 'coach'))->count();
    $breederCount = User::whereHas('roles', fn($q) => $q->where('name', 'breeder'))->count();
    $clinicCount = User::whereHas('roles', fn($q) => $q->where('name', 'clinic'))->count();
    $dogCount = Dog::count();

    $statistics = [
        ['title' => 'تعداد مربی', 'counter' => $coachCount],
        ['title' => 'پرورش دهندگان', 'counter' => $breederCount],
        ['title' => 'کلینیک ها', 'counter' => $clinicCount],
        ['title' => 'سگ ها', 'counter' => $dogCount],
    ];

    $blogs = Blog::where('type', 'about-us')->latest()->get();
    $latestBlog = $blogs->first();

    return view('about.about-us', compact('latestBlog', 'blogs', 'statistics'));
}


     public function contact()
    {
        return view('about.contact');
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Optionally: store or email the message here

        return redirect()->route('contact.index')->with('success', 'پیام شما با موفقیت ارسال شد.');
    }


        public function support()
    {
        // Optionally fetch a support-related blog
    $blog = Blog::where('type', 'support')->latest()->first();

        return view('about.support', compact('blog'));
    }

    public function storeSupport(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::create($validated);

        return back()->with('success', 'پیام پشتیبانی شما با موفقیت ارسال شد.');
    }
}
