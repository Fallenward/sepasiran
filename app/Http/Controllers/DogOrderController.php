<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\DogOrder;
use App\Support\DogPurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DogOrderController extends Controller
{
    public function store(Request $request, int $id, DogPurchaseService $purchase): RedirectResponse
    {
        $buyer = Auth::user();
        abort_unless($buyer, 401);

        $dog = Dog::query()->findOrFail($id);

        try {
            $order = $purchase->createOrder($buyer, $dog);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('frontend.dog-supply.show', $dog->id)
            ->with('success', 'سفارش ثبت شد. برای تکمیل خرید، پرداخت را انجام دهید.')
            ->with('pending_dog_order_id', $order->id);
    }

    public function pay(Request $request, int $id, int $order, DogPurchaseService $purchase): RedirectResponse
    {
        $buyer = Auth::user();
        abort_unless($buyer, 401);

        $dog = Dog::query()->findOrFail($id);
        $dogOrder = DogOrder::query()
            ->where('id', $order)
            ->where('dog_id', $dog->id)
            ->firstOrFail();

        $data = $request->validate([
            'payment_method' => ['nullable', 'in:wallet'],
        ]);

        try {
            $purchase->completePayment($buyer, $dogOrder, $data['payment_method'] ?? 'wallet');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('frontend.dog-supply.show', $dog->id)
            ->with('success', 'پرداخت انجام شد و مالکیت سگ به شما منتقل شد.');
    }
}
