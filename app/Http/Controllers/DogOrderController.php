<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DogOrderController extends Controller
{
    public function index()
    {
        return view('dogOrder.index'); // اینجا به نمای ثبت سفارش سگ ارجاع می‌دهیم
    }

    public function store(Request $request)
    {
        return redirect()->route('frontend.dog.order')->with('success', 'سفارش با موفقیت ثبت شد!');
    }
        public function dashboard()
    {
        return view('breeders.dashboard');
    }

    // 🆕 Optional new function: orders
    public function orders()
    {
        // later you can pass order data if needed
        return view('breeders.orders');
    }
}
