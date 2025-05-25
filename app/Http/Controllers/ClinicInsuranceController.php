<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Breed;
use App\Models\Insurance;  // assuming you have an Insurance model
use Illuminate\Http\Request;
use App\Models\Comment;



class ClinicInsuranceController extends Controller
{
public function index(Request $request)
{
    // Filter data
    $cities = City::orderBy('name')->get();

    $durations = [
        '6' => '۶ ماهه',
        '12' => '۱ ساله',
        '24' => '۲ ساله',
    ];

    $query = Insurance::with('city'); // eager-load city

    if ($request->filled('city')) {
        $query->where('city_id', $request->input('city'));
    }

    $insurances = $query->paginate(6);

    $filters = [
        'city'     => $cities,
        'duration' => collect(array_keys($durations)),
    ];

    return view('clinic-insurance.index', compact(
        'cities',
        'durations',
        'insurances',
        'filters'
    ));
}

public function show($id)
    {
        $insurance = Insurance::with('city')->findOrFail($id);

        // Dummy statistics - replace with your actual logic
        $statistics = [
            ['title' => 'مراجعه‌کنندگان', 'counter' => 120],
            ['title' => 'مراکز طرف قرارداد', 'counter' => 45],
            ['title' => 'کاربران راضی', 'counter' => 98],
            ['title' => 'نظرات ثبت‌شده', 'counter' => 70],
        ];

        $comments = Comment::with('user')
            ->where('user_id', $insurance->id)
            ->latest()
            ->take(10)
            ->get();

        return view('insurance.show', compact('insurance', 'statistics', 'comments'));
    }




}
