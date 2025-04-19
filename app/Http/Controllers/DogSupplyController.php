<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DogSupplyController extends Controller
{
    public function index()
    {
        return view('dog-supply.index'); // نمای dog-supply را باز می‌کند
    }
}
