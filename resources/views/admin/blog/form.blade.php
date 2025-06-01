<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">عنوان</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog?->title) }}" id="title" placeholder="عنوان">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="slug" class="form-label">نامک</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $blog?->slug) }}" id="slug" placeholder="نامک">
            {!! $errors->first('slug', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="content" class="form-label">محتوا</label>
            <input type="text" name="content" class="form-control @error('content') is-invalid @enderror" value="{{ old('content', $blog?->content) }}" id="content" placeholder="محتوا">
            {!! $errors->first('content', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">توضیحات</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $blog?->description) }}" id="description" placeholder="توضیحات">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="image" class="form-label">تصویر</label>
            <input type="text" name="image" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', $blog?->image) }}" id="image" placeholder="تصویر">
            {!! $errors->first('image', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="category_id" class="form-label">شناسه دسته‌بندی</label>
            <input type="text" name="category_id" class="form-control @error('category_id') is-invalid @enderror" value="{{ old('category_id', $blog?->category_id) }}" id="category_id" placeholder="شناسه دسته‌بندی">
            {!! $errors->first('category_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="author_id" class="form-label">شناسه نویسنده</label>
            <input type="text" name="author_id" class="form-control @error('author_id') is-invalid @enderror" value="{{ old('author_id', $blog?->author_id) }}" id="author_id" placeholder="شناسه نویسنده">
            {!! $errors->first('author_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="rating" class="form-label">امتیاز</label>
            <input type="text" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating', $blog?->rating) }}" id="rating" placeholder="امتیاز">
            {!! $errors->first('rating', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="views" class="form-label">بازدیدها</label>
            <input type="text" name="views" class="form-control @error('views') is-invalid @enderror" value="{{ old('views', $blog?->views) }}" id="views" placeholder="بازدیدها">
            {!! $errors->first('views', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="likes" class="form-label">پسندها</label>
            <input type="text" name="likes" class="form-control @error('likes') is-invalid @enderror" value="{{ old('likes', $blog?->likes) }}" id="likes" placeholder="پسندها">
            {!! $errors->first('likes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">وضعیت</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $blog?->status) }}" id="status" placeholder="وضعیت">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="comments_enabled" class="form-label">فعال بودن نظرات</label>
            <input type="text" name="comments_enabled" class="form-control @error('comments_enabled') is-invalid @enderror" value="{{ old('comments_enabled', $blog?->comments_enabled) }}" id="comments_enabled" placeholder="فعال بودن نظرات">
            {!! $errors->first('comments_enabled', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">ارسال</button>
    </div>
</div>
