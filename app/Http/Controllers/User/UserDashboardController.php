<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $nearbyClinics = User::query()
            ->role('clinic')
            ->where('id', '!=', $user->id)
            ->when($user->city_id, fn ($q) => $q->where('city_id', $user->city_id))
            ->paginate(12);

        return view('frontend.pages.user.dashboard', [
            'users' => $user,
            'nearbyClinics' => $nearbyClinics,
        ]);
    }
}
