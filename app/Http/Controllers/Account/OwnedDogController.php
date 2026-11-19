<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Dog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class OwnedDogController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $dogs = Dog::query()
            ->where('user_id', $user->id)
            ->with('breed')
            ->latest()
            ->get();

        return view('frontend.pages.account.dogs-index', [
            'user' => $user,
            'dogs' => $dogs,
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }

    public function create(): View
    {
        $user = Auth::user();

        return view('frontend.pages.account.dogs-create', [
            'user' => $user,
            'breeds' => Breed::query()->orderBy('name')->get(),
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'breed_id' => ['nullable', 'exists:breeds,id'],
            'color' => ['nullable', 'string', 'max:80'],
            'age' => ['nullable', 'integer', 'min:0', 'max:400'],
            'birth_date' => ['nullable', 'date'],
            'cover_type' => ['nullable', 'string', 'max:80'],
            'eye_color' => ['nullable', 'string', 'max:80'],
            'shajare' => ['nullable', 'boolean'],
        ]);

        $payload = array_merge($validated, [
            'user_id' => $user->id,
            'status' => $user->hasRole('breeder') ? 'pending' : 'owned',
            'shajare' => $request->boolean('shajare'),
            'image' => 'images/demo/dogs/dog-0'.((($user->id % 8) + 1)).'.jpg',
        ]);

        if (Schema::hasColumn('dogs', 'city_id') && ! empty($user->city_id)) {
            $payload['city_id'] = $user->city_id;
        }

        $dog = new Dog();
        $dog->forceFill($payload)->save();

        return redirect()
            ->route('account.dogs.index')
            ->with('success', 'سگ با موفقیت ثبت شد.');
    }
}
