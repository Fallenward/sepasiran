@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
  <div class="row">
    <!-- Main Content -->
    <div class="col-lg-9">
      <div class="bg-white rounded shadow-sm p-4 mb-4">
        <img src="{{ asset('images/' . $post->image) }}" class="img-fluid rounded mb-3" alt="{{ $post->title }}">
        <h5 class="mb-2">{{ $post->title }}</h5>
        <small class="text-muted">{{ jdate($post->created_at)->format('H:i | Y/m/d') }}</small>
        <p class="mt-3">{{ $post->excerpt }}</p>
        <div>{!! nl2br(e($post->body)) !!}</div>
      </div>

      <!-- Contact Form -->
      <div class="bg-white rounded shadow-sm p-4">
        <h6 class="mb-3">ارسال تیکت</h6>
        <form>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <input type="text" class="form-control" placeholder="نام">
            </div>
            <div class="col-md-4">
              <input type="email" class="form-control" placeholder="ایمیل">
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control" placeholder="شماره تماس">
            </div>
          </div>
          <textarea class="form-control mb-3" rows="4" placeholder="متن پیام شما..."></textarea>
          <button type="submit" class="btn btn-primary">مشاهده</button>
        </form>
      </div>
    </div>

    <!-- Sidebar (on the Right) -->
    <div class="col-lg-3 mb-4">
      <div class="bg-white rounded shadow-sm mb-4 p-3">
        <h6 class="text-white bg-primary p-2 rounded">آخرین مطالب</h6>
        <div class="d-flex flex-column gap-2">
          <div class="d-flex gap-2">
            <img src="dog.jpg" alt="Dog" class="rounded" width="50">
            <div>
              <a href="#" class="text-decoration-none">
                <p class="mb-1">نگهداری و تربیت سگ...</p>
              </a>
              <small>10:00 | 1403/02/22</small>
            </div>
          </div>
          <div class="d-flex gap-2">
            <img src="dog.jpg" alt="Dog" class="rounded" width="50">
            <div>
              <a href="#" class="text-decoration-none">
                <p class="mb-1">چگونه به سگ خود آموزش دهیم؟</p>
              </a>
              <small>12:00 | 1403/02/22</small>
            </div>
          </div>
          <div class="d-flex gap-2">
            <img src="dog.jpg" alt="Dog" class="rounded" width="50">
            <div>
              <a href="#" class="text-decoration-none">
                <p class="mb-1">مراقبت از سگ‌های خانگی...</p>
              </a>
              <small>14:00 | 1403/02/22</small>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded shadow-sm mb-4 p-3">
        <h6 class="bg-primary text-white p-2 rounded">فیلتر ها</h6>
        <div class="d-grid gap-2">
          <button class="btn btn-outline-secondary">لورم اپیسوم</button>
          <button class="btn btn-outline-secondary">لورم اپیسوم</button>
        </div>
      </div>

      <div class="bg-white rounded shadow-sm p-3">
        <h6 class="text-white bg-primary p-2 rounded">محبوب‌ترین‌ها</h6>
        <div class="d-flex gap-2">
          <img src="dog.jpg" alt="Dog" class="rounded" width="50">
          <div>
            <a href="#" class="text-decoration-none">
              <p class="mb-1">سگ‌های محبوب در ایران</p>
            </a>
            <small>15:00 | 1403/02/22</small>
          </div>
        </div>
        <div class="d-flex gap-2">
          <img src="dog.jpg" alt="Dog" class="rounded" width="50">
          <div>
            <a href="#" class="text-decoration-none">
              <p class="mb-1">آموزش بهترین روش‌ها برای تربیت سگ</p>
            </a>
            <small>16:00 | 1403/02/22</small>
          </div>
        </div>
        <div class="d-flex gap-2">
          <img src="dog.jpg" alt="Dog" class="rounded" width="50">
          <div>
            <a href="#" class="text-decoration-none">
              <p class="mb-1">چگونه سگ خود را خوشحال نگه داریم؟</p>
            </a>
            <small>17:00 | 1403/02/22</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
