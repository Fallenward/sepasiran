<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Insurance;
use Illuminate\Http\Request;

class ClinicInsuranceController extends Controller
{
    public function index(Request $request)
    {
        $query = Insurance::query()->where('is_active', true);

        if ($request->filled('q')) {
            $term = '%' . trim((string) $request->q) . '%';
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('contact_phone', 'like', $term)
                    ->orWhere('website', 'like', $term);
            });
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $insurances = $query->latest()->paginate(9)->appends($request->query());

        $filters = [
            'featured' => [
                ['id' => '1', 'name' => 'ویژه'],
            ],
        ];

        return view('clinic-insurance.index', compact('insurances', 'filters'));
    }

    public function show($id)
    {
        $insurance = Insurance::query()->findOrFail($id);

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