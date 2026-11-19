@extends('frontend.layouts.app')

@section('title', ($mode === 'edit' ? 'ویرایش دوره' : 'افزودن دوره') . ' — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--coach">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-pen"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">{{ $mode === 'edit' ? 'ویرایش دوره' : 'افزودن دوره' }}</h1>
                    <p class="sp-dash__lede">اطلاعات دوره آموزشی را تکمیل کنید.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>بازگشت</strong>
                    <p>به لیست دوره‌ها برگردید.</p>
                </div>
                <a class="sp-btn sp-btn--ghost" href="{{ route('coaches.courses.index') }}">دوره‌ها</a>
            </aside>
        </section>

        <form class="panel sp-dash__panel" method="POST"
              action="{{ $mode === 'edit' ? route('coaches.courses.update', $course) : route('coaches.courses.store') }}">
            @csrf
            @if($mode === 'edit')
                @method('PUT')
            @endif

            <div class="field">
                <label for="title">عنوان</label>
                <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" required>
                @error('title')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="short_description">خلاصه</label>
                <input id="short_description" name="short_description" type="text" value="{{ old('short_description', $course->short_description) }}">
            </div>
            <div class="field">
                <label for="description">توضیحات</label>
                <textarea id="description" name="description" rows="5">{{ old('description', $course->description) }}</textarea>
            </div>
            <div class="field">
                <label for="category_id">دسته‌بندی</label>
                <select id="category_id" name="category_id">
                    <option value="">—</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $course->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            @if($levels->count())
            <div class="field">
                <label for="level_id">سطح</label>
                <select id="level_id" name="level_id">
                    <option value="">—</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" @selected(old('level_id', $course->level_id) == $level->id)>{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="field">
                <label for="price">قیمت (تومان)</label>
                <input id="price" class="num" name="price" type="number" min="0" step="1000" value="{{ old('price', $course->price) }}">
            </div>
            <div class="field">
                <label for="discount_price">قیمت با تخفیف</label>
                <input id="discount_price" class="num" name="discount_price" type="number" min="0" step="1000" value="{{ old('discount_price', $course->discount_price) }}">
            </div>
            <div class="field">
                <label for="duration">مدت (دقیقه)</label>
                <input id="duration" class="num" name="duration" type="number" min="0" value="{{ old('duration', $course->duration) }}">
            </div>
            <div class="field">
                <label for="video_demo_url">لینک دمو</label>
                <input id="video_demo_url" name="video_demo_url" type="url" value="{{ old('video_demo_url', $course->video_demo_url) }}">
            </div>
            <div class="field">
                <label for="status">وضعیت انتشار</label>
                <select id="status" name="status">
                    @foreach(['published' => 'منتشر', 'draft' => 'پیش‌نویس'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $course->status ?: 'published') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $course->is_active ?? true))> فعال</label>
            </div>
            <div class="field">
                <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $course->is_featured ?? false))> ویژه</label>
            </div>
            <div class="field">
                <label><input type="checkbox" name="has_certificate" value="1" @checked(old('has_certificate', $course->has_certificate ?? false))> گواهی دارد</label>
            </div>

            <div class="sp-dash__form-actions">
                <button class="sp-btn sp-btn--primary" type="submit">{{ $mode === 'edit' ? 'ذخیره تغییرات' : 'ایجاد دوره' }}</button>
                <a class="sp-btn sp-btn--ghost" href="{{ route('coaches.courses.index') }}">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection
