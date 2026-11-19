<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Dog;
use App\Models\DogColor;
use App\Models\DogCoverType;
use App\Models\DogEyeColor;
use App\Models\DogMedia;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;

class OwnedDogController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $dogs = Dog::query()
            ->where('user_id', $user->id)
            ->with(['breed', 'media'])
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
        return $this->form(new Dog(), 'create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $this->validated($request);

        $payload = array_merge($validated, [
            'user_id' => $user->id,
            'status' => $user->hasRole('breeder') ? 'pending' : 'owned',
            'shajare' => $request->boolean('shajare'),
        ]);

        if (Schema::hasColumn('dogs', 'city_id') && ! empty($user->city_id)) {
            $payload['city_id'] = $user->city_id;
        }

        $dog = new Dog();
        $dog->forceFill($payload)->save();
        $this->storeMedia($request, $dog);

        if (empty($dog->image) && $dog->media()->exists()) {
            $dog->forceFill(['image' => $dog->media()->first()->path])->save();
        }

        return redirect()
            ->route('account.dogs.index')
            ->with('success', 'سگ با موفقیت ثبت شد.');
    }

    public function edit(Dog $dog): View
    {
        $this->authorizeDog($dog);

        return $this->form($dog->load('media'), 'edit');
    }

    public function update(Request $request, Dog $dog): RedirectResponse
    {
        $this->authorizeDog($dog);
        $validated = $this->validated($request);

        $dog->forceFill(array_merge($validated, [
            'shajare' => $request->boolean('shajare'),
        ]))->save();

        $this->storeMedia($request, $dog);

        if ($request->filled('remove_media')) {
            $ids = array_map('intval', (array) $request->input('remove_media', []));
            $media = DogMedia::query()
                ->where('dog_id', $dog->id)
                ->whereIn('id', $ids)
                ->get();
            foreach ($media as $item) {
                Storage::disk('public')->delete($item->path);
                $item->delete();
            }
        }

        return redirect()
            ->route('account.dogs.index')
            ->with('success', 'مشخصات سگ به‌روزرسانی شد.');
    }

    public function destroy(Dog $dog): RedirectResponse
    {
        $this->authorizeDog($dog);

        foreach ($dog->media as $item) {
            Storage::disk('public')->delete($item->path);
        }
        $dog->media()->delete();
        $dog->delete();

        return redirect()
            ->route('account.dogs.index')
            ->with('success', 'سگ حذف شد.');
    }

    private function form(Dog $dog, string $mode): View
    {
        $user = Auth::user();
        $birth = $dog->birth_date ? Carbon::parse($dog->birth_date) : null;
        $jalali = $birth ? Jalalian::fromCarbon($birth) : null;

        return view('frontend.pages.account.dogs-form', [
            'user' => $user,
            'dog' => $dog,
            'mode' => $mode,
            'breeds' => Breed::query()->orderBy('name')->get(),
            'colors' => DogColor::query()->active()->get(),
            'eyeColors' => DogEyeColor::query()->active()->get(),
            'coverTypes' => DogCoverType::query()->active()->get(),
            'jalaliYear' => old('birth_year', $jalali?->getYear()),
            'jalaliMonth' => old('birth_month', $jalali?->getMonth()),
            'jalaliDay' => old('birth_day', $jalali?->getDay()),
            'years' => $this->jalaliYears(),
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
        ]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'breed_id' => ['nullable', 'exists:breeds,id'],
            'color' => ['nullable', 'string', 'max:80'],
            'cover_type' => ['nullable', 'string', 'max:80'],
            'eye_color' => ['nullable', 'string', 'max:80'],
            'birth_year' => ['nullable', 'integer', 'min:1370', 'max:1500'],
            'birth_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'shajare' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'max:4096'],
            'remove_media' => ['nullable', 'array'],
            'remove_media.*' => ['integer'],
        ], [
            'title.required' => 'نام سگ الزامی است.',
            'images.*.image' => 'فایل تصویر معتبر نیست.',
            'images.*.max' => 'حجم هر تصویر باید کمتر از ۴ مگابایت باشد.',
        ]);

        $birthDate = null;
        $age = null;
        if (! empty($data['birth_year']) && ! empty($data['birth_month']) && ! empty($data['birth_day'])) {
            try {
                $birthDate = Jalalian::fromFormat(
                    'Y/m/d',
                    sprintf('%04d/%02d/%02d', $data['birth_year'], $data['birth_month'], $data['birth_day'])
                )->toCarbon()->startOfDay();
                $age = (int) $birthDate->diffInMonths(now());
            } catch (\Throwable) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'birth_day' => 'تاریخ تولد معتبر نیست.',
                ]);
            }
        }

        unset($data['birth_year'], $data['birth_month'], $data['birth_day'], $data['images'], $data['remove_media'], $data['shajare']);

        $data['birth_date'] = $birthDate?->format('Y-m-d');
        $data['age'] = $age;

        return $data;
    }

    private function storeMedia(Request $request, Dog $dog): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        foreach ($request->file('images', []) as $file) {
            if (! $file) {
                continue;
            }
            $path = $file->store('dogs/'.$dog->id, 'public');
            DogMedia::query()->create([
                'dog_id' => $dog->id,
                'type' => 'image',
                'path' => $path,
                'url' => '/storage/'.$path,
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        if (empty($dog->image)) {
            $first = $dog->media()->first();
            if ($first) {
                $dog->forceFill(['image' => $first->url ?: '/storage/'.$first->path])->save();
            }
        }
    }

    private function authorizeDog(Dog $dog): void
    {
        abort_unless((int) $dog->user_id === (int) Auth::id(), 403);
    }

    /** @return list<int> */
    private function jalaliYears(): array
    {
        $current = (int) Jalalian::now()->getYear();

        return range($current, $current - 25);
    }
}
