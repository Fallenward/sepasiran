<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function index()
    {
        $dogs = Dog::with(['city', 'breed', 'specialty'])->paginate(10);
        return view('admin.dogs.index', compact('dogs'));
    }

    public function create()
    {
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all(); // اضافه کردن تخصص‌ها
        return view('admin.dogs.create', compact('cities', 'breeds', 'specialties')); // ارسال تخصص‌ها به ویو
    }

    public function store(Request $request)
    {
        // اعتبارسنجی داده‌ها
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id', // اطمینان از وجود شهر در جدول cities
            'breed_id' => 'required|exists:breeds,id', // اطمینان از وجود نژاد در جدول breeds
            'size' => 'required|string', // سایز سگ
            'description' => 'required|string',
            'image' => 'required|image', // تصویر سگ (اختیاری می‌توانید اضافه کنید)
        ]);

        // ذخیره سگ جدید
        $dog = new Dog();
        $dog->name = $validated['name'];
        $dog->city_id = $validated['city_id'];
        $dog->breed_id = $validated['breed_id'];
        $dog->size = $validated['size'];
        $dog->description = $validated['description'];

        // ذخیره تصویر
        if ($request->hasFile('image')) {
            $dog->image = $request->file('image')->store('dogs', 'public');
        }

        $dog->save();

        return redirect()->route('admin.dogs.index')->with('success', 'Dog created successfully');
    }

    public function show($id)
    {
        $dog = Dog::with(['city', 'breed'])->findOrFail($id);
        return view('admin.dogs.show', compact('dog'));
    }

    public function edit($id)
    {
        $dog = Dog::findOrFail($id);
        $cities = City::all();
        $breeds = Breed::all();
        $specialties = Specialty::all(); // اضافه کردن تخصص‌ها
        return view('admin.dogs.edit', compact('dog', 'cities', 'breeds', 'specialties')); // ارسال تخصص‌ها به ویو
    }

    public function update(Request $request, $id)
    {
        $dog = Dog::findOrFail($id);

        // اعتبارسنجی داده‌ها
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'breed_id' => 'required|exists:breeds,id',
            'size' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image', // تصویر اختیاری
        ]);

        // به‌روزرسانی داده‌ها
        $dog->name = $validated['name'];
        $dog->city_id = $validated['city_id'];
        $dog->breed_id = $validated['breed_id'];
        $dog->size = $validated['size'];
        $dog->description = $validated['description'];

        // اگر تصویر جدیدی انتخاب شده باشد، آن را ذخیره کنید
        if ($request->hasFile('image')) {
            $dog->image = $request->file('image')->store('dogs', 'public');
        }

        $dog->save();

        return redirect()->route('admin.dogs.index')->with('success', 'Dog updated successfully');
    }

    public function destroy($id)
    {
        $dog = Dog::findOrFail($id);
        $dog->delete();

        return redirect()->route('admin.dogs.index')->with('success', 'Dog deleted successfully');
    }
    public function indexFront(Request $request)
    {
        // فیلترها را از درخواست می‌گیریم
        $cities = City::all();
        $breeds = Breed::all();
        $dogs = Dog::with(['city', 'breed']); // رابطه سگ با شهر و نژاد

        // اعمال فیلترها
        if ($request->has('city')) {
            $dogs->whereIn('city_id', $request->city);
        }
        if ($request->has('breed')) {
            $dogs->whereIn('breed_id', $request->breed);
        }
        if ($request->has('size')) {
            $dogs->whereIn('size', $request->size);
        }

        // گرفتن سگ‌ها با توجه به فیلترها
        $dogs = $dogs->paginate(6);

        return view('frontend.dog.supply.index', compact('dogs', 'cities', 'breeds'));
    }
}
