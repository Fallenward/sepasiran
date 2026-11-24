@extends('layouts.app')
@section('title','جلسات باز')
@section('content')
<div class="wrap" style="padding:32px 0 64px">
  <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:18px">
    <h1 style="margin:0;font-size:1.5rem">جلسات باز ارائه‌دهندگان</h1>
    @auth
      <a class="btn btn-primary" href="{{ route('frontend.open-sessions.create') }}">ایجاد جلسه</a>
    @endauth
  </div>
  <form method="get" style="display:flex;gap:8px;margin-bottom:16px">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجو…">
    <input name="category" value="{{ request('category') }}" placeholder="دسته">
    <button class="btn btn-ghost" type="submit">فیلتر</button>
  </form>
  <div style="display:grid;gap:12px">
    @forelse($sessions as $s)
      <a class="panel" href="{{ route('frontend.open-sessions.show', $s) }}" style="text-decoration:none;color:inherit">
        <strong>{{ $s->title }}</strong>
        <p class="sp-booking__hint" style="margin:6px 0 0">{{ \Illuminate\Support\Str::limit($s->description, 140) }}</p>
        <p class="sp-booking__hint num">ظرفیت {{ $s->participants_filled }}/{{ $s->participants_needed }}
          @if($s->budget_min || $s->budget_max)
            · بودجه {{ number_format((int)$s->budget_min) }}–{{ number_format((int)$s->budget_max) }}
          @endif
        </p>
      </a>
    @empty
      <p class="sp-booking__hint">جلسه‌ای منتشر نشده است.</p>
    @endforelse
  </div>
  <div style="margin-top:16px">{{ $sessions->links() }}</div>
</div>
@endsection