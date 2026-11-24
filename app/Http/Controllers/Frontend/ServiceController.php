<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    /**
     * Former /services listing — content now lives on the home page.
     */
    public function index(): RedirectResponse
    {
        return redirect()->to(route('frontend.home').'#services');
    }
}