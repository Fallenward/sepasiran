<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\City;
use App\Models\Breed;
use App\Models\Specialty;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::with(['city', 'breed', 'specialty'])->paginate(10);
        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();
        return view('admin.coaches.create', compact('cities', 'breeds', 'specialties'));
    }

    public function store(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id', // اطمینان از اینکه city_id در جدول cities موجود است
            'breed_id' => 'required|exists:breeds,id', // اطمینان از اینکه breed_id در جدول breeds موجود است
            'specialty_id' => 'required|exists:specialties,id', // اطمینان از اینکه specialty_id در جدول specialties موجود است
            'description' => 'required|string',
        ]);

        // ذخیره داده‌ها
        $coach = new Coach();
        $coach->name = $validated['name'];
        $coach->city_id = $validated['city_id'];
        $coach->breed_id = $validated['breed_id'];
        $coach->specialty_id = $validated['specialty_id'];
        $coach->description = $validated['description'];
        $coach->save();

        // بازگشت به صفحه اصلی با پیام موفقیت
        return redirect()->route('admin.coaches.index')->with('success', 'Coach created successfully');
    }


    public function show($id)
    {
        $coach = Coach::findOrFail($id);
        return view('admin.coaches.show', compact('coach'));
    }

    public function edit($id)
    {
        $coach = Coach::findOrFail($id);
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();
        return view('admin.coaches.edit', compact('coach', 'cities', 'breeds', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        // اعتبارسنجی ورودی‌ها
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'breed_id' => 'required|exists:breeds,id',
            'specialty_id' => 'required|exists:specialties,id',
            'description' => 'required|string',
        ]);

        // پیدا کردن مربی با شناسه $id
        $coach = Coach::findOrFail($id);

        // به‌روزرسانی داده‌ها
        $coach->name = $validated['name'];
        $coach->city_id = $validated['city_id'];
        $coach->breed_id = $validated['breed_id'];
        $coach->specialty_id = $validated['specialty_id'];
        $coach->description = $validated['description'];
        $coach->save();

        // بازگشت به صفحه اصلی با پیام موفقیت
        return redirect()->route('admin.coaches.index')->with('success', 'Coach updated successfully');
    }


    public function destroy($id)
    {
        $coach = Coach::findOrFail($id);
        $coach->delete();
        return redirect()->route('admin.coaches.index')->with('success', 'Coach deleted successfully');
    }


    public function indexFront(Request $request)
    {
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all();

        // اعمال فیلترها بر اساس درخواست کاربر
        $query = Coach::query();

        if ($request->has('city')) {
            $query->whereIn('city_id', $request->input('city'));
        }

        if ($request->has('breed')) {
            $query->whereIn('breed_id', $request->input('breed'));
        }

        if ($request->has('specialty')) {
            $query->whereIn('specialty_id', $request->input('specialty'));
        }

        $coaches = $query->get();

        return view('coaches.index', compact('coaches', 'cities', 'breeds', 'specialties'));
    }
}
