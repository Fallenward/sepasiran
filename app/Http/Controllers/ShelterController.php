<?php

namespace App\Http\Controllers;

use App\Models\Service; // اضافه کردن مدل Service
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function index()
    {
        $services = Service::latest()->take(12)->get(); // آخرین ۱۲ سرویس

        return view('shelter.index', compact('services'));
    }
}
