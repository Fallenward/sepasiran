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

}
