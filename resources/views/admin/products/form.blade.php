    <div class="card shadow-sm rounded border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $product ? 'ویرایش محصول' : 'ایجاد محصول جدید' }}</h5>
        </div>

        <div class="card-body bg-white">
            <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                @if($product)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">عنوان</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $product?->title) }}">
                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">
                        <label for="slug" class="form-label">نامک</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product?->slug) }}">
                        {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Price --}}
                    <div class="col-md-4">
                        <label for="price" class="form-label">قیمت</label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product?->price) }}">
                        {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Discount Price --}}
                    <div class="col-md-4">
                        <label for="discount_price" class="form-label">قیمت با تخفیف</label>
                        <input type="number" name="discount_price" id="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product?->discount_price) }}">
                        {!! $errors->first('discount_price', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Stock --}}
                    <div class="col-md-4">
                        <label for="stock" class="form-label">موجودی</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product?->stock) }}">
                        {!! $errors->first('stock', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">دسته‌بندی</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">انتخاب کنید</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ old('category_id', $product?->category_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('category_id', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Brand --}}
                    <div class="col-md-6">
                        <label for="brand_id" class="form-label">برند</label>
                        <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">انتخاب کنید</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product?->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('brand_id', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label">توضیحات</label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product?->description) }}</textarea>
                        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Image --}}
                    <div class="col-md-6">
                        <label for="image" class="form-label">تصویر محصول</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                        {!! $errors->first('image', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{-- Is Active --}}
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" {{ old('is_active', $product?->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">فعال باشد؟</label>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100">ذخیره محصول</button>
                </div>
            </form>
        </div>
    </div>
