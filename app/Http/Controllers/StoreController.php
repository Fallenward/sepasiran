<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $dogs = Dog::query()
            ->when($request->filled('size'), fn($q) => $q->whereIn('size', $request->input('size')))
            ->when($request->filled('category'), fn($q) => $q->whereIn('category', $request->input('category')))
            ->paginate(9);

        $filters = [
            'size' => ['mini' => 'کوچک', 'medium' => 'متوسط', 'large' => 'بزرگ'],
            'category' => [
                'entertainment' => 'لوازم سرگرمی سگ',
                'training'      => 'لوازم آموزشی سگ',
                'lorem1'        => 'لورم',
                'lorem2'        => 'لورم'
            ]
        ];

        return view('stores.index', compact('dogs', 'filters'));
    }
}
