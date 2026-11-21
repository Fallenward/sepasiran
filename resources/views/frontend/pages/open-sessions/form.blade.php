@extends('layouts.app')
@section('title','ایجاد جلسه باز')
@section('content')
<div class="wrap" style="padding:32px 0 64px;max-width:720px">
  <form class="panel" method="post" action="{{ route('frontend.open-sessions.store') }}" style="display:grid;gap:10px">
    @csrf
    <h1 style="margin:0">ایجاد جلسه باز</h1>
    <input name="title" required placeholder="عنوان" value="{{ old('title') }}">
    <textarea name="description" rows="4" placeholder="توضیحات">{{ old('description') }}</textarea>
    <input name="category" placeholder="دسته" value="{{ old('category') }}">
    <select name="required_role">
      <option value="">نقش لازم (اختیاری)</option>
      @foreach($roles as $r)<option value="{{ $r }}">{{ $r }}</option>@endforeach
    </select>
    <input name="location" placeholder="مکان">
    <label><input type="checkbox" name="is_online" value="1"> آنلاین</label>
    <label><input type="checkbox" name="flexible_schedule" value="1" checked> زمان منعطف</label>
    <input type="date" name="preferred_date">
    <input name="preferred_slot" placeholder="ساعت مثلا 14:30">
    <input class="num" type="number" name="duration_minutes" value="60" min="15" required>
    <input class="num" type="number" name="participants_needed" value="1" min="1" required>
    <input class="num" type="number" name="budget_min" placeholder="بودجه حداقل">
    <input class="num" type="number" name="budget_max" placeholder="بودجه حداکثر">
    <input type="datetime-local" name="deadline_at">
    <label><input type="checkbox" name="publish" value="1"> انتشار فوری</label>
    <button class="btn btn-primary" type="submit">ذخیره</button>
  </form>
</div>
@endsection