<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\InsuranceRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $insurances = Insurance::paginate();

        return view('insurance.index', compact('insurances'))
            ->with('i', ($request->input('page', 1) - 1) * $insurances->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $insurance = new Insurance();

        return view('insurance.create', compact('insurance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InsuranceRequest $request): RedirectResponse
    {
        Insurance::create($request->validated());

        return Redirect::route('insurances.index')
            ->with('success', 'Insurance created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $insurance = Insurance::find($id);

        return view('insurance.show', compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $insurance = Insurance::find($id);

        return view('insurance.edit', compact('insurance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InsuranceRequest $request, Insurance $insurance): RedirectResponse
    {
        $insurance->update($request->validated());

        return Redirect::route('insurances.index')
            ->with('success', 'Insurance updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Insurance::find($id)->delete();

        return Redirect::route('insurances.index')
            ->with('success', 'Insurance deleted successfully');
    }
}
