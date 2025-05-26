@extends('layouts.app')

@section('content')
    <style>
        /* Apply a constant blue border and padding */
        .form-control,
        .form-select,
        textarea {
            border: 2px solid #0d47a1 !important;
            padding: 10px !important;
            box-shadow: none !important;
        }

        /* Prevent border color from changing on focus */
        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #0d47a1 !important;
            box-shadow: none !important;
        }
    </style>

    <h4 class="mb-4">پروفایل کاربری</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('clinic.profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">نام کلینیک</label>
            <input type="text" name="clinic_name" class="form-control" value="{{ old('clinic_name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">شماره تماس</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">ساعات کاری</label>
            @php
                $workingHours = ['14 تا 20', '8 تا 14', '10 تا 18'];
            @endphp
            <select class="form-select" name="working_hours">
                @foreach($workingHours as $hour)
                    <option value="{{ $hour }}" {{ old('working_hours', $user->working_hours ?? '') == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">حیوانات تحت درمان</label>
            @php
                $animalOptions = ['سگ', 'گربه', 'پرندگان'];
                $selectedAnimals = old('animals', explode(',', $user->animals ?? ''));
            @endphp
            <select class="form-select" name="animals[]" multiple>
                @foreach($animalOptions as $animal)
                    <option value="{{ $animal }}" {{ in_array($animal, $selectedAnimals) ? 'selected' : '' }}>{{ $animal }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">وب‌سایت / شبکه‌های اجتماعی</label>
            <input type="text" name="social" class="form-control" value="{{ old('social', $user->social ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">توضیحات (بیوگرافی کلینیک)</label>
            <textarea class="form-control" name="description" rows="4">{{ old('description', $user->bio) }}</textarea>
        </div>

        <hr>

        <h5 class="mb-3">اطلاعات آدرس</h5>

        <div class="mb-3">
            <label class="form-label">استان</label>
            <select class="form-select" name="province">
                <option value="فارس" {{ old('province', $user->province ?? '') == 'فارس' ? 'selected' : '' }}>فارس</option>
                <option value="تهران" {{ old('province', $user->province ?? '') == 'تهران' ? 'selected' : '' }}>تهران</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">شهر</label>
            <select class="form-select" name="city">
                <option value="شیراز" {{ old('city', $user->city ?? '') == 'شیراز' ? 'selected' : '' }}>شیراز</option>
                <option value="تهران" {{ old('city', $user->city ?? '') == 'تهران' ? 'selected' : '' }}>تهران</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">آدرس کامل</label>
            <input type="text" name="full_address" class="form-control" value="{{ old('full_address', $user->address) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">موقعیت روی نقشه</label>
            <div class="rounded overflow-hidden">
                <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=44.0%2C25.0%2C63.3%2C40.0&layer=mapnik&marker=32.0%2C53.7"
                style="border: 1px solid black"></iframe>
                <br/>
                <small><a href="https://www.openstreetmap.org/?mlat=32.0&mlon=53.7#map=5/32.0/53.7">View Larger Map</a></small>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">ذخیره</button>
    </form>
@endsection
