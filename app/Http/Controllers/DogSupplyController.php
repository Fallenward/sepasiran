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
        $query = Dog::with([
            'owner:id,name',
            'city:id,name',
            'specialty:id,name',
        ]);

        // Optional filters
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

        $filters = [
            'city' => City::select('id', 'name')->get(),
            'breed' => Breed::select('id', 'name')->get(),
            'size' => ['small', 'medium', 'large'],
        ];

        if ($request->ajax()) {
            $html = '';
            foreach ($dogs as $dog) {
                $html .= view('components.dog-card', ['item' => $dog])->render();
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $dogs->hasMorePages(),
                'nextPage' => $dogs->currentPage() + 1,
            ]);
        }

        return view('dog-supply.index', compact('dogs', 'filters'));
    }

public function show($id)
{
    $dog = Dog::with([
        'owner:id,name',
        'city:id,name',
        'specialty:id,name',
        'fatherBreed:id,name',
        'motherBreed:id,name',
        'maternalGrandmotherBreed:id,name',
        'maternalGrandfatherBreed:id,name',
        'paternalGrandmotherBreed:id,name',
        'paternalGrandfatherBreed:id,name',
    ])->findOrFail($id);

    return view('dogOrder.index', compact('dog'));
}





}
