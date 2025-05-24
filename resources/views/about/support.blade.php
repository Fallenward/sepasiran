@extends('layouts.app')

@section('content')

<x-page-header
    title="پشتیبانی"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'پشتیبانی', 'route' => route('frontend.support-web.index')],
    ]"
    bgColor="linear-gradient(90deg, #0D47A1 0%, #051A3B 100%)"
/>

{{-- Section: About Support --}}
<div class="container">
    <div class="row align-items-center text-end section-spacing" style="min-height: 350px;">
        {{-- Content --}}
        <div class="col-md-6 order-2 order-md-1 d-flex flex-column justify-content-center">
            @if($blog)
                {{-- Dynamic Title --}}
                <h2 class="fw-bold mb-2">{{ $blog->title }}</h2>

                {{-- Blue divider line below title --}}
                <div style="height: 6px; width: 72px; background-color: #0d47a1;" class="mb-4 rounded"></div>

                @php
                    $content = strip_tags($blog->excerpt ?? $blog->content ?? '');
                    $content = trim($content);
                @endphp

                @if($content)
                    <p class="text-muted" style="white-space: pre-line;">
                        {{ $content }}
                    </p>
                @else
                    <p class="text-muted">توضیحی برای این بلاگ موجود نیست.</p>
                @endif
            @else
                <h2 class="fw-bold mb-2">پشتیبانی</h2>
                <div style="height: 6px; width: 72px; background-color: #0d47a1;" class="mb-4 rounded"></div>
                <p class="text-danger">محتوایی برای نمایش وجود ندارد.</p>
            @endif
        </div>

        {{-- Static Image --}}
        <div class="col-md-6 order-1 order-md-2 text-center">
            <img src="{{ asset('images/support-card.png') }}" alt="پشتیبانی" class="img-fluid rounded shadow-sm" style="max-width: 300px;">
        </div>
    </div>
</div>

{{-- Support Form --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-wrapper p-4 rounded bg-light shadow-sm">
                @if(session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('frontend.support-web.store') }}">
                    @csrf

                    <div class="row g-4 align-items-center">
                        {{-- Contact Info --}}
                        <div class="col-md-6 text-end">
                            <h5 class="mb-3">اطلاعات تماس</h5>

                            <div class="mb-3">
                                <label class="form-label">نام و نام خانوادگی</label>
                                <input type="text" name="name" class="form-control blue-border @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name') <div class="invalid-feedback d-block text-end">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ایمیل</label>
                                <input type="email" name="email" class="form-control blue-border @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email') <div class="invalid-feedback d-block text-end">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">شماره تماس</label>
                                <input type="text" name="phone" class="form-control blue-border @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone') <div class="invalid-feedback d-block text-end">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Complaint Details --}}
                        <div class="col-md-6 text-end">
                            <h5 class="mb-3">جزئیات شکایت</h5>

                            <div class="mb-3">
                                <label class="form-label">موضوع شکایت</label>
                                <input type="text" name="complaint_subject" class="form-control blue-border @error('complaint_subject') is-invalid @enderror" value="{{ old('complaint_subject') }}">
                                @error('complaint_subject') <div class="invalid-feedback d-block text-end">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">توضیحات شکایت</label>
                                <textarea name="complaint_details" rows="5" class="form-control blue-border @error('complaint_details') is-invalid @enderror">{{ old('complaint_details') }}</textarea>
                                @error('complaint_details') <div class="invalid-feedback d-block text-end">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-12 d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary btn-sm rounded">ثبت شکایت</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .blue-border {
        border: 2px solid #007bff !important;
        border-radius: 0.5rem;
    }

    .blue-border:focus {
        border-color: #0056b3 !important;
        box-shadow: none !important;
    }

    .form-wrapper {
        background-color: #f8f9fa;
        border-radius: 1rem;
    }
</style>

@endsection
