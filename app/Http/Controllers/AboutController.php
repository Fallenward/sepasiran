<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog; // Fix: Capital "B" in Blog
use App\Models\User;
use App\Models\Dog;


class AboutController extends Controller
{
    public function index()
    {

            // Count users by role (coach, breeder, clinic)
    $coachCount = User::whereHas('roles', fn($q) => $q->where('name', 'coach'))->count();
    $breederCount = User::whereHas('roles', fn($q) => $q->where('name', 'breeder'))->count();
    $clinicCount = User::whereHas('roles', fn($q) => $q->where('name', 'clinic'))->count();

    // Count dogs
    $dogCount = Dog::count();

    // Statistics array
    $statistics = [
        ['title' => 'تعداد مربی', 'counter' => $coachCount],
        ['title' => 'پرورش دهندگان', 'counter' => $breederCount],
        ['title' => 'کلینیک ها', 'counter' => $clinicCount],
        ['title' => 'سگ ها', 'counter' => $dogCount],
    ];

        // Fix: Get the first blog ordered by created_at DESC (latest)
        $blog = Blog::orderBy('created_at', 'desc')->first();

        return view('about.about-us', compact('blog', 'statistics'));
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
}
