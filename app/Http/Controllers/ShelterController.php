<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function index()
    {
        return view('shelter.index'); // به نمای shelter.index ارجاع می‌دهیم
    }
}
