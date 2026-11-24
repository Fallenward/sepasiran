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
                    <p class="sp-dash__lede">اطلاعات دوره، بخش‌ها، درس‌ها و آپلود ویدیو را مدیریت کنید.</p>
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

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif

        <form class="panel sp-dash__panel" method="POST" enctype="multipart/form-data"
              action="{{ $mode === 'edit' ? route('coaches.courses.update', $course) : route('coaches.courses.store') }}"
              id="spCourseForm">
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
                <label for="duration">مدت کل (دقیقه)</label>
                <input id="duration" class="num" name="duration" type="number" min="0" value="{{ old('duration', $course->duration) }}">
            </div>
            <div class="field">
                <label for="video_demo_url">لینک دمو (اختیاری)</label>
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

            <section class="sp-course-curriculum">
                <header class="sp-course-curriculum__head">
                    <div>
                        <h2>بخش‌ها و درس‌ها</h2>
                        <p class="sp-dash__muted">هر دوره می‌تواند چند بخش داشته باشد و هر بخش چند درس با ویدیوی جداگانه.</p>
                    </div>
                    <button type="button" class="sp-btn sp-btn--ghost" id="spAddPart">افزودن بخش</button>
                </header>
                <div id="spParts" class="sp-course-curriculum__parts"></div>
                @error('parts')<p class="auth-alert">{{ $message }}</p>@enderror
            </section>

            <div class="sp-dash__form-actions">
                <button class="sp-btn sp-btn--primary" type="submit">{{ $mode === 'edit' ? 'ذخیره تغییرات' : 'ایجاد دوره' }}</button>
                <a class="sp-btn sp-btn--ghost" href="{{ route('coaches.courses.index') }}">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const initialParts = @json(old('parts') ? null : ($initialParts ?? []));
  const oldParts = @json(old('parts'));
  const partsRoot = document.getElementById('spParts');
  const addPartBtn = document.getElementById('spAddPart');
  if (!partsRoot || !addPartBtn) return;

  const blankLesson = () => ({
    id: null,
    title: '',
    description: '',
    duration_minutes: '',
    is_preview: false,
    has_video: false,
    video_name: null,
    video_url: null,
  });
  const blankPart = (n) => ({
    id: null,
    title: 'بخش ' + n,
    lessons: [blankLesson()],
  });

  let parts = Array.isArray(oldParts) && oldParts.length
    ? oldParts.map((p, i) => ({
        id: p.id || null,
        title: p.title || ('بخش ' + (i + 1)),
        lessons: Array.isArray(p.lessons) && p.lessons.length
          ? p.lessons.map((l) => ({
              id: l.id || null,
              title: l.title || '',
              description: l.description || '',
              duration_minutes: l.duration_minutes || '',
              is_preview: !!l.is_preview,
              has_video: !!l.has_video,
              video_name: l.video_name || null,
              video_url: l.video_url || null,
            }))
          : [blankLesson()],
      }))
    : (Array.isArray(initialParts) && initialParts.length ? initialParts : [blankPart(1)]);

  const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function render() {
    partsRoot.innerHTML = parts.map((part, pi) => `
      <article class="sp-course-part" data-part="${pi}">
        <div class="sp-course-part__head">
          <div class="field" style="margin:0;flex:1">
            <label>عنوان بخش</label>
            <input type="text" name="parts[${pi}][title]" value="${esc(part.title)}" placeholder="مثلاً مقدمات">
            ${part.id ? `<input type="hidden" name="parts[${pi}][id]" value="${esc(part.id)}">` : ''}
          </div>
          <button type="button" class="sp-btn sp-btn--ghost" data-remove-part="${pi}" style="color:var(--sp-danger)">حذف بخش</button>
        </div>
        <div class="sp-course-part__lessons">
          ${part.lessons.map((lesson, li) => `
            <div class="sp-course-lesson" data-lesson="${li}">
              ${lesson.id ? `<input type="hidden" name="parts[${pi}][lessons][${li}][id]" value="${esc(lesson.id)}">` : ''}
              <div class="sp-course-lesson__grid">
                <div class="field">
                  <label>عنوان درس</label>
                  <input type="text" name="parts[${pi}][lessons][${li}][title]" value="${esc(lesson.title)}" placeholder="مثلاً جلسه ۱">
                </div>
                <div class="field">
                  <label>مدت (دقیقه)</label>
                  <input class="num" type="number" min="0" name="parts[${pi}][lessons][${li}][duration_minutes]" value="${esc(lesson.duration_minutes ?? '')}">
                </div>
              </div>
              <div class="field">
                <label>توضیح درس</label>
                <textarea name="parts[${pi}][lessons][${li}][description]" rows="2">${esc(lesson.description)}</textarea>
              </div>
              <div class="field">
                <label>ویدیو درس</label>
                <input type="file" name="parts[${pi}][lessons][${li}][video]" accept="video/mp4,video/webm,video/quicktime">
                <p class="sp-dash__muted">mp4 / webm / mov — حداکثر حدود ۲۰۰ مگابایت</p>
                ${lesson.has_video ? `
                  <div class="sp-course-lesson__video">
                    <span>ویدیوی فعلی: ${esc(lesson.video_name || 'آپلود شده')}</span>
                    ${lesson.video_url ? `<a href="${esc(lesson.video_url)}" target="_blank" rel="noopener">مشاهده</a>` : ''}
                    <label><input type="checkbox" name="parts[${pi}][lessons][${li}][remove_video]" value="1"> حذف ویدیو</label>
                  </div>` : ''}
              </div>
              <div class="field">
                <label><input type="checkbox" name="parts[${pi}][lessons][${li}][is_preview]" value="1" ${lesson.is_preview ? 'checked' : ''}> پیش‌نمایش رایگان</label>
              </div>
              <button type="button" class="sp-btn sp-btn--ghost" data-remove-lesson="${pi}:${li}" style="min-height:34px;padding:6px 12px;color:var(--sp-danger)">حذف درس</button>
            </div>
          `).join('')}
        </div>
        <button type="button" class="sp-btn sp-btn--ghost" data-add-lesson="${pi}">افزودن درس</button>
      </article>
    `).join('');
  }

  partsRoot.addEventListener('click', (e) => {
    const addLesson = e.target.closest('[data-add-lesson]');
    if (addLesson) {
      const pi = Number(addLesson.getAttribute('data-add-lesson'));
      parts[pi].lessons.push(blankLesson());
      render();
      return;
    }
    const removeLesson = e.target.closest('[data-remove-lesson]');
    if (removeLesson) {
      const [pi, li] = removeLesson.getAttribute('data-remove-lesson').split(':').map(Number);
      parts[pi].lessons.splice(li, 1);
      if (!parts[pi].lessons.length) parts[pi].lessons.push(blankLesson());
      render();
      return;
    }
    const removePart = e.target.closest('[data-remove-part]');
    if (removePart) {
      const pi = Number(removePart.getAttribute('data-remove-part'));
      if (parts.length === 1) {
        parts = [blankPart(1)];
      } else {
        parts.splice(pi, 1);
      }
      render();
    }
  });

  addPartBtn.addEventListener('click', () => {
    parts.push(blankPart(parts.length + 1));
    render();
  });

  render();
})();
</script>
@endpush