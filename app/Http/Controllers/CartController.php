<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $carts = Cart::paginate();

        return view('cart.index', compact('carts'))
            ->with('i', ($request->input('page', 1) - 1) * $carts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cart = new Cart();

        return view('cart.create', compact('cart'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request): RedirectResponse
    {
        Cart::create($request->validated());

        return Redirect::route('carts.index')
            ->with('success', 'Cart created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cart = Cart::find($id);

        return view('cart.show', compact('cart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cart = Cart::find($id);

        return view('cart.edit', compact('cart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, Cart $cart): RedirectResponse
    {
        $cart->update($request->validated());

        return Redirect::route('carts.index')
            ->with('success', 'Cart updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Cart::find($id)->delete();

        return Redirect::route('carts.index')
            ->with('success', 'Cart deleted successfully');
    }
}
