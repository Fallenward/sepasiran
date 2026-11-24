<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('roles')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . trim((string) $request->q) . '%';
                $query->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', $term)
                        ->orWhere('family', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('user.index', compact('users'));
    }

    public function create(): View
    {
        $user = new User();
        $roles = Role::orderBy('name')->get();
        $selectedRoles = [];

        return view('user.create', compact('user', 'roles', 'selectedRoles'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $roleIds = $data['roles'] ?? [];
        unset($data['roles']);

        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = (bool) ($data['is_admin'] ?? false);

        $user = User::create($data);
        $user->roles()->sync($roleIds);

        return Redirect::route('admin.users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function show($id): View
    {
        $user = User::with('roles')->findOrFail($id);

        return view('user.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        $selectedRoles = $user->roles->pluck('id')->all();

        return view('user.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $roleIds = $data['roles'] ?? [];
        unset($data['roles']);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_admin'] = (bool) ($data['is_admin'] ?? false);

        $user->update($data);
        $user->roles()->sync($roleIds);

        return Redirect::route('admin.users.index')
            ->with('success', 'کاربر به‌روزرسانی شد.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->delete();

        return Redirect::route('admin.users.index')
            ->with('success', 'کاربر حذف شد.');
    }
}