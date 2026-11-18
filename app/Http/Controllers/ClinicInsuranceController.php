<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Comment;
use App\Models\Insurance;
use Illuminate\Http\Request;

class ClinicInsuranceController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::orderBy('name')->get(['id', 'name']);

        $query = Insurance::with('city');

        if ($request->filled('q')) {
            $term = '%' . trim((string) $request->q) . '%';
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('city', fn ($q) => $q->where('name', 'like', $term));
            });
        }

        if ($request->filled('city')) {
            $query->whereIn('city_id', (array) $request->input('city'));
        }

        if ($request->filled('duration')) {
            $query->whereIn('duration', (array) $request->input('duration'));
        }

        $insurances = $query->paginate(9)->appends($request->query());

        $filters = [
            'city' => $cities,
            'duration' => [
                ['id' => '6', 'name' => '۶ ماهه'],
                ['id' => '12', 'name' => '۱ ساله'],
                ['id' => '24', 'name' => '۲ ساله'],
            ],
        ];

        return view('clinic-insurance.index', compact('insurances', 'filters'));
    }

    public function show($id)
    {
        $insurance = Insurance::with('city')->findOrFail($id);

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