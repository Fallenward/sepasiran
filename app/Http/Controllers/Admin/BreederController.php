<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Dog;
use App\Models\DogOrder;
use App\Models\User;
use Illuminate\Http\Request;

class BreederController extends Controller
{
       public function dashboard()
{
    $users = User::all(); // Get the currently authenticated user


    $readyDogs = Dog::where('status', 'ready')->count();
    $totalDogs = Dog::count();
    $pendingDogs = Dog::where('status', 'pending')->count();
    $soldDogs = Dog::where('status', 'sold')->count();
    $waitingDogs = Dog::where('status', 'waiting')->count();

    $pendingOrders = DogOrder::where('status', 'pending')->count();
    $totalRevenue = DogOrder::where('status', 'paid')->sum('total_price');
    $openOrders = DogOrder::where('status', 'open')->count();

    return view('breeders.dashboard', compact(
        'readyDogs',
        'users',
        'totalDogs',
        'pendingDogs',
        'soldDogs',
        'waitingDogs',
        'pendingOrders',
        'totalRevenue',
        'openOrders'
    ));
}
}
