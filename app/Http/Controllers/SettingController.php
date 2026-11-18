<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use App\Support\AppCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $settings = Setting::paginate();

        return view('setting.index', compact('settings'))
            ->with('i', ($request->input('page', 1) - 1) * $settings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $setting = new Setting();

        return view('setting.create', compact('setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request): RedirectResponse
    {
        Setting::create($request->validated());
        AppCache::forgetSettings();

        return Redirect::route('settings.index')
            ->with('success', 'Setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $setting = Setting::find($id);

        return view('setting.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $setting = Setting::find($id);

        return view('setting.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, Setting $setting): RedirectResponse
    {
        $setting->update($request->validated());
        AppCache::forgetSettings();

        return Redirect::route('settings.index')
            ->with('success', 'Setting updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Setting::find($id)->delete();
        AppCache::forgetSettings();

        return Redirect::route('settings.index')
            ->with('success', 'Setting deleted successfully');
    }
}
