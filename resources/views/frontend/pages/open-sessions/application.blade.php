@extends('layouts.app')
@section('title','گفتگوی درخواست')
@section('content')
<div class="wrap" style="padding:32px 0 64px;max-width:760px">
  <div class="panel">
    <h1 style="margin:0 0 8px">{{ $application->session->title }}</h1>
    <p class="sp-booking__hint">وضعیت درخواست: {{ $application->status }}</p>
    @if(session('success'))<p style="color:var(--sp-green)">{{ session('success') }}</p>@endif

    <div style="display:grid;gap:10px;margin:16px 0;max-height:360px;overflow:auto">
      @foreach($application->messages as $msg)
        <div class="sp-booking__locked-card" style="margin:0">
          <span>{{ $msg->user->name ?? 'کاربر' }} · {{ $msg->type }}</span>
          <strong>{{ $msg->body }}</strong>
          @if($msg->offer_id && $isOwner && $msg->offer && $msg->offer->status === 'pending' && in_array($application->status, ['PENDING','NEGOTIATING'], true))
            <form method="post" action="{{ route('frontend.open-sessions.accept-offer', [$application, $msg->offer]) }}" style="margin-top:8px">
              @csrf
              <button class="btn btn-primary" type="submit">پذیرش این پیشنهاد و ایجاد رزرو</button>
            </form>
          @endif
        </div>
      @endforeach
    </div>

    @if(in_array($application->status, ['PENDING','NEGOTIATING'], true))
      <form method="post" action="{{ route('frontend.open-sessions.propose', $application) }}" style="display:grid;gap:8px">
        @csrf
        <input class="num" type="number" name="amount" required placeholder="مبلغ پیشنهاد">
        <input name="note" placeholder="یادداشت">
        <button class="btn btn-primary" type="submit">ارسال پیشنهاد قیمت</button>
      </form>
    @endif
  </div>
</div>
@endsection