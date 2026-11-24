<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\DogOrder;
use App\Support\DogPurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            ->with('success', 'سفارش ثبت شد. برای تکمیل خرید، کیف پول یا درگاه بانکی را انتخاب کنید.')
            ->with('pending_dog_order_id', $order->id);
    }

    public function pay(Request $request, int $id, int $order, DogPurchaseService $purchase)
    {
        $buyer = Auth::user();
        abort_unless($buyer, 401);

        $dog = Dog::query()->findOrFail($id);
        $dogOrder = DogOrder::query()
            ->where('id', $order)
            ->where('dog_id', $dog->id)
            ->firstOrFail();

        $data = $request->validate([
            'payment_method' => ['required', 'in:wallet,gateway'],
        ]);

        $method = $data['payment_method'];

        try {
            if ($method === 'gateway') {
                $form = $purchase->startGatewayPayment($buyer, $dogOrder);

                // Local/test drivers may return HTML form; bank drivers redirect.
                return $form instanceof \Shetabit\Multipay\RedirectionForm
                    ? new Response($form->render())
                    : $form;
            }

            $purchase->completePayment($buyer, $dogOrder, 'wallet');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('frontend.dog-supply.show', $dog->id)
            ->with('success', 'پرداخت انجام شد و مالکیت سگ به شما منتقل شد.');
    }

    public function callback(Request $request, int $order, DogPurchaseService $purchase): RedirectResponse
    {
        $dogOrder = DogOrder::query()->with('dog')->findOrFail($order);

        // Prefer authenticated buyer; fall back to order buyer after gateway return.
        $buyer = Auth::user();
        if ($buyer && (int) $buyer->id !== (int) $dogOrder->buyer_id) {
            abort(403);
        }

        $buyer = $buyer ?: $dogOrder->buyer;

        try {
            $purchase->completeGatewayPayment(
                $dogOrder,
                $request->input('transactionId') ?: $request->input('Authority')
            );
        } catch (ValidationException $e) {
            return redirect()
                ->route('frontend.dog-supply.show', $dogOrder->dog_id)
                ->withErrors($e->errors());
        }

        return redirect()
            ->route('frontend.dog-supply.show', $dogOrder->dog_id)
            ->with('success', 'پرداخت درگاه تأیید شد و مالکیت سگ منتقل شد.');
    }
}
