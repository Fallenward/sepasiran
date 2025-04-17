<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DiscountRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $discounts = Discount::paginate();

        return view('discount.index', compact('discounts'))
            ->with('i', ($request->input('page', 1) - 1) * $discounts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $discount = new Discount();

        return view('discount.create', compact('discount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountRequest $request): RedirectResponse
    {
        Discount::create($request->validated());

        return Redirect::route('discounts.index')
            ->with('success', 'Discount created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $discount = Discount::find($id);

        return view('discount.show', compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $discount = Discount::find($id);

        return view('discount.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountRequest $request, Discount $discount): RedirectResponse
    {
        $discount->update($request->validated());

        return Redirect::route('discounts.index')
            ->with('success', 'Discount updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Discount::find($id)->delete();

        return Redirect::route('discounts.index')
            ->with('success', 'Discount deleted successfully');
    }
}
