@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-4 p-4">

    {{-- Sidebar --}}
    <aside class="w-full lg:w-1/4 space-y-4">
        {{-- Latest Courses --}}
        <div class="bg-blue-500 text-white p-3 rounded">
            <h2 class="text-lg font-bold mb-2">آخرین دوره‌ها</h2>
            @foreach ($latestCourses as $course)
                <div class="bg-white text-black p-2 mb-2 rounded shadow">
                    <a href="{{ route('frontend.courses.show', $course->slug) }}">
                        <p class="font-bold">{{ \Illuminate\Support\Str::limit($course->title, 40) }}</p>
                    </a>
                    <p class="text-sm text-gray-500">{{ $course->created_at->format('H:i - d M') }}</p>
                </div>
            @endforeach
        </div>

        {{-- Featured Courses --}}
        <div class="bg-blue-500 text-white p-3 rounded">
            <h2 class="text-lg font-bold mb-2">محبوب‌ترین‌ها</h2>
            @foreach ($featuredCourses as $course)
                <div class="bg-white text-black p-2 mb-2 rounded shadow">
                    <a href="{{ route('frontend.courses.show', $course->slug) }}">
                        <p class="font-bold">{{ \Illuminate\Support\Str::limit($course->title, 40) }}</p>
                    </a>
                    <p class="text-sm text-gray-500">{{ $course->created_at->format('H:i - d M') }}</p>
                </div>
            @endforeach
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="w-full lg:w-3/4 bg-white p-6 rounded shadow text-right">
        <h1 class="text-3xl font-bold mb-6">{{ $course->title }}</h1>

        @if($course->thumbnail)
            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full rounded mb-6">
        @endif

        <p class="text-gray-600 mb-4">مدرس: {{ $course->user->name ?? 'نامشخص' }}</p>
        <p class="mb-6">{{ $course->description }}</p>

        <h2 class="text-2xl font-bold mb-4">جزئیات دوره</h2>
        <p><strong>قیمت: </strong>{{ number_format($course->discount_price ?? $course->price) }} تومان</p>

        {{-- You can add more course details here like lessons, duration, etc. --}}

        {{-- Ticket submission form --}}
        <div class="mt-10 border-t pt-6">
            <h2 class="text-xl font-bold mb-4">ارسال تیکت</h2>
            <form action="{{ route('ticket.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="نام" class="p-2 border rounded" required>
                    <input type="email" name="email" placeholder="ایمیل" class="p-2 border rounded" required>
                    <input type="text" name="phone" placeholder="شماره موبایل" class="p-2 border rounded md:col-span-2" required>
                </div>
                <textarea name="message" placeholder="پیام شما" class="w-full p-2 border mt-4 rounded h-32" required></textarea>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600 transition">ارسال</button>
            </form>
        </div>
    </main>
</div>
@endsection
