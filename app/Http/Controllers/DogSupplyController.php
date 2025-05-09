<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DogSupplyController extends Controller
{

    public function index(Request $request)
    {
        $query = Dog::with(['breed', 'owner']);

        if ($request->filled('city')) {
            $query->whereIn('city_id', (array) $request->city);
        }

        if ($request->filled('breed')) {
            $query->whereIn('breed_id', (array) $request->breed);
        }

        if ($request->filled('size')) {
            $query->whereIn('size', (array) $request->size);
        }

        $dogs = $query->paginate(9)->appends($request->query());

        // Check if AJAX
        if ($request->ajax()) {
            $html = view('partials.dog-cards', ['dogs' => $dogs])->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $dogs->hasMorePages(),
                'nextPage' => $dogs->currentPage() + 1,
            ]);
        }

        // ✅ Add this part back!
        return view('dog-supply.index', [
            'dogs' => $dogs,
            'cities' => City::all(),
            'breeds' => Breed::all(),
            'filters' => [
                'city' => City::all(),
                'breed' => Breed::all(),
                'size' => ['small', 'medium', 'large'],
            ],
        ]);
    }

}
