<?php

namespace App\Http\Controllers;

use App\Http\Requests\DogRequest;
use App\Models\Dog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $dogs = Dog::with('owner')->paginate(20);

        return view('dog.index', compact('dogs'))
            ->with('i', ($request->input('page', 1) - 1) * $dogs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dog = new Dog;

        return view('dog.create', compact('dog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DogRequest $request): RedirectResponse
    {
        Dog::create($request->validated());

        return Redirect::route('dogs.index')
            ->with('success', 'Dog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $dog = Dog::find($id);

        return view('dog.show', compact('dog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dog = Dog::find($id);

        return view('dog.edit', compact('dog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DogRequest $request, Dog $dog): RedirectResponse
    {
        $dog->update($request->validated());

        return Redirect::route('dogs.index')
            ->with('success', 'Dog updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Dog::find($id)->delete();

        return Redirect::route('dogs.index')
            ->with('success', 'Dog deleted successfully');
    }
}
