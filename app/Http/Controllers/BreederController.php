<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreederController extends Controller
{
    public function index()
    {
        // اینجا می‌توانید اطلاعات مربوط به پرورش‌دهندگان را بارگذاری کنید
        return view('breeders.index');
    }
}
