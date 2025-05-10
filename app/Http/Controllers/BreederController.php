<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Breed;
use Illuminate\Http\Request;

class BreederController extends Controller
{
    public function index(Request $request)
    {
        // 1) Load the filter data
        $cities = City::orderBy('name')->get();      // or City::pluck('name','id')
        $breeds = Breed::orderBy('name')->get();

        // 2) Build your breeders query (apply filters if you want)
        $query = Breed::query();

        if ($request->filled('city')) {
            $query->whereIn('city_id', $request->input('city'));
        }
        if ($request->filled('breed')) {
            $query->whereIn('breed_id', $request->input('breed'));
        }
        if ($request->filled('size')) {
            $query->whereIn('size', $request->input('size'));
        }

        // 3) Paginate
        $breeders = $query->paginate(12);

        // 4) Pass everything into the view
        return view('breeders.index', compact('cities', 'breeds', 'breeders'));
    }
}
