<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function index()
    {
        $dogs = Dog::with(['city', 'breed', 'specialty'])->paginate(10);
        return view('admin.dogs.index', compact('dogs'));
    }

    public function create()
    {
        $dogs = Dog::all();
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();
        return view('admin.dogs.create', compact('cities','dogs', 'breeds', 'specialties'));
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'breed_id' => 'nullable|exists:breeds,id',
            'age' => 'nullable|integer',
            'birth_date' => 'nullable|date',
            'cover_type' => 'nullable|string|max:50',
            'eye_color' => 'nullable|string|max:50',
            'shajare' => 'nullable|boolean',
            'father_name' => 'nullable|string|max:255',
            'father_breed_id' => 'nullable|exists:breeds,id',
            'mother_name' => 'nullable|string|max:255',
            'mother_breed_id' => 'nullable|exists:breeds,id',
            'maternal_grandmother_name' => 'nullable|string|max:255',
            'maternal_grandmother_breed_id' => 'nullable|exists:breeds,id',
            'maternal_grandfather_name' => 'nullable|string|max:255',
            'maternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
            'paternal_grandmother_name' => 'nullable|string|max:255',
            'paternal_grandmother_breed_id' => 'nullable|exists:breeds,id',
            'paternal_grandfather_name' => 'nullable|string|max:255',
            'paternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Dog::create($validated);

        return redirect()->route('admin.dogs.index')->with('success', 'Dog created successfully');
    }

    public function show($id)
    {
        $dog = Dog::with([
            'city', 'breed', 'owner', 'media',
            'fatherBreed', 'motherBreed',
            'maternalGrandmotherBreed', 'maternalGrandfatherBreed',
            'paternalGrandmotherBreed', 'paternalGrandfatherBreed',
        ])->findOrFail($id);

        return view('admin.dogs.show', compact('dog'));
    }

    public function edit($id)
    {
        $dog = Dog::findOrFail($id);
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();
        return view('admin.dogs.edit', compact('dog', 'cities', 'breeds', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        $dog = Dog::findOrFail($id);

        // Validation same as store
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'breed_id' => 'nullable|exists:breeds,id',
            'age' => 'nullable|integer',
            'birth_date' => 'nullable|date',
            'cover_type' => 'nullable|string|max:50',
            'eye_color' => 'nullable|string|max:50',
            'shajare' => 'nullable|boolean',
            'father_name' => 'nullable|string|max:255',
            'father_breed_id' => 'nullable|exists:breeds,id',
            'mother_name' => 'nullable|string|max:255',
            'mother_breed_id' => 'nullable|exists:breeds,id',
            'maternal_grandmother_name' => 'nullable|string|max:255',
            'maternal_grandmother_breed_id' => 'nullable|exists:breeds,id',
            'maternal_grandfather_name' => 'nullable|string|max:255',
            'maternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
            'paternal_grandmother_name' => 'nullable|string|max:255',
            'paternal_grandmother_breed_id' => 'nullable|exists:breeds,id',
            'paternal_grandfather_name' => 'nullable|string|max:255',
            'paternal_grandfather_breed_id' => 'nullable|exists:breeds,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $dog->update($validated);

        return redirect()->route('admin.dogs.index')->with('success', 'Dog updated successfully');
    }

    public function destroy($id)
    {
        $dog = Dog::findOrFail($id);
        $dog->delete();

        return redirect()->route('admin.dogs.index')->with('success', 'Dog deleted successfully');
    }

    public function indexFront(Request $request)
    {
        $cities = City::all();
        $breeds = Breed::all();

        $dogs = Dog::with(['city', 'breed']);

        // Filtering by city, breed, size (if these fields exist)
        if ($request->has('city')) {
            $dogs->whereIn('city_id', (array) $request->city);
        }
        if ($request->has('breed')) {
            $dogs->whereIn('breed_id', (array) $request->breed);
        }
        if ($request->has('size')) {
            $dogs->whereIn('size', (array) $request->size);
        }

        $dogs = $dogs->paginate(6);

        return view('frontend.dog.supply.index', compact('dogs', 'cities', 'breeds'));
    }
}
