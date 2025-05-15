@extends('admin.layout')

@section('title', 'پروفایل کاربری')

@section('content')
    <h4 class="mb-3">پروفایل کاربری</h4>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile') }}">
        @csrf

        <!-- User Info -->
        <div class="mb-3">
            <label>نام</label>
            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label>نام خانوادگی</label>
            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label>ایمیل</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email ?? '') }}">
        </div>
        <div class="mb-3">
            <label>کد ملی</label>
            <input type="text" class="form-control" name="national_id" value="{{ old('national_id', $user->national_id ?? '') }}" placeholder="2281111111">
        </div>
        <div class="mb-3">
            <label>شماره تماس</label>
            <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="09111111111">
        </div>
        <div class="mb-3">
            <label>رمز عبور (در صورت نیاز به تغییر)</label>
            <input type="password" class="form-control" name="password" placeholder="****" autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label>تأیید رمز عبور</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="****" autocomplete="new-password">
        </div>

        <hr class="my-4">
        <h5 class="mb-3">تغییرات اطلاعات حساب</h5>
        <div class="mb-3">
            <label>نام پرورش دهنده</label>
            <input type="text" class="form-control" name="breeder_name" value="{{ old('breeder_name', $user->breeder_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label>آدرس کسب و کار</label>
            <input type="text" class="form-control" name="business_address" value="{{ old('business_address', $user->business_address ?? '') }}">
        </div>
        <div class="mb-3">
            <label>شماره تماس کسب و کار</label>
            <input type="text" class="form-control" name="business_phone" value="{{ old('business_phone', $user->business_phone ?? '') }}">
        </div>
        <div class="mb-3">
            <label>نوع پرورش دهنده (نژادها)</label>
            <select class="form-select" name="breeder_type">
                @foreach($breeds as $breed)
                    <option value="{{ $breed->name }}" {{ old('breeder_type', $user->breeder_type ?? '') == $breed->name ? 'selected' : '' }}>
                        {{ $breed->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>اطلاعات حساب بانکی</label>
            <input type="text" class="form-control" name="bank_info" value="{{ old('bank_info', $user->bank_info ?? '') }}">
        </div>

        <button class="btn btn-primary w-100 mb-4">ذخیره</button>
    </form>
@endsection
