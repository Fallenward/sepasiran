@extends('admin.layout')

@section('title', 'پروفایل کاربری')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">پروفایل کاربری</h4>
    <img src="{{ asset('images/user.svg') }}"
         alt="User Avatar"
         class="rounded-circle"
         width="60"
         height="60"
         style="object-fit: cover;">
</div>


    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('breeder.profile') }}">
        @csrf

        {{-- Personal Information --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">نام</label>
                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">نام خانوادگی</label>
                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">ایمیل</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="national_id" class="form-label">کد ملی</label>
                <input type="text" class="form-control" name="national_id" value="{{ old('national_id', $user->national_id) }}" placeholder="2281111111">
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">شماره تماس</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="09111111111">
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">رمز عبور (در صورت نیاز به تغییر)</label>
                <input type="password" class="form-control" name="password" autocomplete="new-password" placeholder="••••••••">
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">تأیید رمز عبور</label>
                <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="••••••••">
            </div>
        </div>

        <hr class="my-4">

        {{-- Breeder Information --}}
        <h5 class="mb-3">اطلاعات پرورش‌دهنده</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="breeder_name" class="form-label">نام پرورش‌دهنده</label>
                <input type="text" class="form-control" name="breeder_name" value="{{ old('breeder_name', $user->breeder_name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="business_phone" class="form-label">شماره تماس کسب و کار</label>
                <input type="text" class="form-control" name="business_phone" value="{{ old('business_phone', $user->business_phone) }}">
            </div>

            <div class="col-md-12 mb-3">
                <label for="business_address" class="form-label">آدرس کسب و کار</label>
                <input type="text" class="form-control" name="business_address" value="{{ old('business_address', $user->business_address) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="breeder_type" class="form-label">نوع پرورش‌دهنده (نژادها)</label>
                <select class="form-select" name="breeder_type">
                    <option value="">— انتخاب کنید —</option>
                    @foreach($breeds as $breed)
                        <option value="{{ $breed->name }}" {{ old('breeder_type', $user->breeder_type) == $breed->name ? 'selected' : '' }}>
                            {{ $breed->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="bank_info" class="form-label">اطلاعات حساب بانکی</label>
                <input type="text" class="form-control" name="bank_info" value="{{ old('bank_info', $user->bank_info) }}">
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn w-100 text-white" style="background-color: #0d47a1;">ذخیره</button>
        </div>
    </form>
@endsection
