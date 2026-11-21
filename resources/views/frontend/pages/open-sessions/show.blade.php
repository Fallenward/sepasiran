@extends('layouts.app')
@section('title', $session->title)
@section('content')
<div class="wrap" style="padding:32px 0 64px;max-width:760px">
  <div class="panel">
    @if(session('success'))<p style="color:var(--sp-green)">{{ session('success') }}</p>@endif
    <h1 style="margin:0 0 8px">{{ $session->title }}</h1>
    <p>{{ $session->description }}</p>
    <div class="list-row"><span>وضعیت</span><strong>{{ $session->status }}</strong></div>
    <div class="list-row"><span>ظرفیت</span><strong class="num">{{ $session->participants_filled }}/{{ $session->participants_needed }}</strong></div>
    <div class="list-row"><span>مدت</span><strong class="num">{{ $session->duration_minutes }} دقیقه</strong></div>
    @if($session->location)<div class="list-row"><span>مکان</span><strong>{{ $session->location }}</strong></div>@endif

    @auth
      @if((int)Auth::id() === (int)$session->user_id)
        @if($session->status === 'DRAFT' || $session->status === 'PAUSED')
          <form method="post" action="{{ route('frontend.open-sessions.publish', $session) }}">@csrf
            <button class="btn btn-primary" type="submit">انتشار</button></form>
        @endif
        @if($session->status === 'PUBLISHED')
          <form method="post" action="{{ route('frontend.open-sessions.pause', $session) }}">@csrf
            <button class="btn btn-ghost" type="submit">توقف</button></form>
        @endif
        <a class="btn btn-ghost" href="{{ route('frontend.open-sessions.mine') }}">جلسات من</a>
      @elseif($session->isOpenForApplications())
        @if($myApplication)
          <a class="btn btn-primary" href="{{ route('frontend.open-sessions.application', $myApplication) }}">مشاهده درخواست من</a>
        @else
          <form method="post" action="{{ route('frontend.open-sessions.apply', $session) }}" style="display:grid;gap:8px;margin-top:16px">
            @csrf
            <textarea name="intro" placeholder="معرفی" rows="2"></textarea>
            <textarea name="experience" placeholder="تجربه" rows="2"></textarea>
            <input class="num" type="number" name="proposed_price" placeholder="قیمت پیشنهادی (تومان)">
            <textarea name="message" placeholder="پیام" rows="2"></textarea>
            <button class="btn btn-primary" type="submit">ارسال درخواست</button>
          </form>
        @endif
      @endif
    @else
      <a class="btn btn-primary" href="{{ route('login') }}">ورود برای درخواست</a>
    @endauth
  </div>
</div>
@endsection