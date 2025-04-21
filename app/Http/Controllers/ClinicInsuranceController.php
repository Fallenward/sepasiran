<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClinicInsuranceController extends Controller
{
    public function index()
    {
        return view('clinic-insurance.index');
    }
}


