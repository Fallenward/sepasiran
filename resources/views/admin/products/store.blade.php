@extends('admin.layout')

@section('template_title', 'ایجاد محصول جدید')

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm rounded border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">ایجاد محصول جدید</h5>
                </div>

                <div class="card-body bg-white">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>خطا!</strong> لطفاً اطلاعات ورودی را بررسی کنید.<br><br>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.products.store') }}">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="title" class="form-label">عنوان</label>
                                <input type="text" name="title" id="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" placeholder="عنوان">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="slug" class="form-label">نامک</label>
                                <input type="text" name="slug" id="slug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug') }}" placeholder="نامک">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="price" class="form-label">قیمت</label>
                                <input type="text" name="price" id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" placeholder="قیمت">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="discount_price" class="form-label">قیمت با تخفیف</label>
                                <input type="text" name="discount_price" id="discount_price"
                                       class="form-control @error('discount_price') is-invalid @enderror"
                                       value="{{ old('discount_price') }}" placeholder="قیمت با تخفیف">
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="stock" class="form-label">موجودی</label>
                                <input type="text" name="stock" id="stock"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock') }}" placeholder="موجودی">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">دسته‌بندی</label>
                                <select name="category_id" id="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">— انتخاب دسته‌بندی —</option>
                                    @foreach($categories as $id => $name)
                                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="brand_id" class="form-label">برند</label>
                                <select name="brand_id" id="brand_id"
                                        class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">— انتخاب برند —</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-check mt-3">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                           id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label">فعال باشد؟</label>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">ذخیره محصول</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
