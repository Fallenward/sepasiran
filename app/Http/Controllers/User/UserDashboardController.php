<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{

public function index()
{
    $user = Auth::user();

    // Get clinics in the same city as the current user
    $nearbyClinics = User::role('clinic')
        ->where('id', '!=', $user->id)
        ->where('city_id', $user->city_id)
        ->get();

    return view('frontend.pages.user.dashboard', [
        'users' => $user,
        'nearbyClinics' => $nearbyClinics,
    ]);
}
}
