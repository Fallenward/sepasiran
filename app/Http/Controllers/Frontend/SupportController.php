<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        return view('support.index', [
            'title' => 'پشتیبانی',
            'isAuth' => Auth::check(),
            'loginUrl' => route('login', ['redirect' => route('frontend.support.index')]),
        ]);
    }
}