<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Breed;
use Illuminate\Http\Request;

class BreederController extends Controller
{

    public function index(Request $request)
    {
        // Prepare filters for the sidebar component
        $filters = [
            'cities' => City::orderBy('name')->get(),
            'breeds' => Breed::orderBy('name')->get(),
            'sizes' => [
                'mini' => 'کوچک (Mini)',
                'medium' => 'متوسط (Medium)',
                'large' => 'بزرگ (Large)',
            ],
        ];

        // Base query
        $query = Breed::query();

        // Apply filters
        if ($request->filled('city')) {
            $query->whereIn('city_id', (array) $request->input('city'));
        }

        if ($request->filled('breed')) {
            $query->whereIn('breed_id', (array) $request->input('breed'));
        }

        if ($request->filled('size')) {
            $query->whereIn('size', (array) $request->input('size'));
        }

        // Paginate results
        $breeders = $query->latest()->paginate(12);

        // Return view
        return view('breeders.index', compact('filters', 'breeders'));
    }
}
