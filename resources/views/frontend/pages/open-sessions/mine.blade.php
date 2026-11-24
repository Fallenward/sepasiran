@extends('layouts.app')
@section('title','جلسات باز من')
@section('content')
<div class="wrap" style="padding:32px 0 64px">
  <h1>جلسات باز من</h1>
  <a class="btn btn-primary" href="{{ route('frontend.open-sessions.create') }}">جدید</a>
  <div style="display:grid;gap:10px;margin-top:16px">
    @foreach($sessions as $s)
      <a class="panel" href="{{ route('frontend.open-sessions.show', $s) }}">{{ $s->title }} · {{ $s->status }}</a>
    @endforeach
  </div>
  {{ $sessions->links() }}
</div>
@endsection