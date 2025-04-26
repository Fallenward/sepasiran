<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course?->title) }}" id="title" placeholder="Title">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="slug" class="form-label">{{ __('Slug') }}</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $course?->slug) }}" id="slug" placeholder="Slug">
            {!! $errors->first('slug', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="short_description" class="form-label">{{ __('Short Description') }}</label>
            <input type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description', $course?->short_description) }}" id="short_description" placeholder="Short Description">
            {!! $errors->first('short_description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $course?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="thumbnail" class="form-label">{{ __('Thumbnail') }}</label>
            <input type="text" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" value="{{ old('thumbnail', $course?->thumbnail) }}" id="thumbnail" placeholder="Thumbnail">
            {!! $errors->first('thumbnail', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="instructor_id" class="form-label">{{ __('Instructor Id') }}</label>
            <input type="text" name="instructor_id" class="form-control @error('instructor_id') is-invalid @enderror" value="{{ old('instructor_id', $course?->instructor_id) }}" id="instructor_id" placeholder="Instructor Id">
            {!! $errors->first('instructor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="category_id" class="form-label">{{ __('Category Id') }}</label>
            <input type="text" name="category_id" class="form-control @error('category_id') is-invalid @enderror" value="{{ old('category_id', $course?->category_id) }}" id="category_id" placeholder="Category Id">
            {!! $errors->first('category_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="level_id" class="form-label">{{ __('Level Id') }}</label>
            <input type="text" name="level_id" class="form-control @error('level_id') is-invalid @enderror" value="{{ old('level_id', $course?->level_id) }}" id="level_id" placeholder="Level Id">
            {!! $errors->first('level_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $course?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="discount_price" class="form-label">{{ __('Discount Price') }}</label>
            <input type="text" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $course?->discount_price) }}" id="discount_price" placeholder="Discount Price">
            {!! $errors->first('discount_price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $course?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_featured" class="form-label">{{ __('Is Featured') }}</label>
            <input type="text" name="is_featured" class="form-control @error('is_featured') is-invalid @enderror" value="{{ old('is_featured', $course?->is_featured) }}" id="is_featured" placeholder="Is Featured">
            {!! $errors->first('is_featured', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="has_certificate" class="form-label">{{ __('Has Certificate') }}</label>
            <input type="text" name="has_certificate" class="form-control @error('has_certificate') is-invalid @enderror" value="{{ old('has_certificate', $course?->has_certificate) }}" id="has_certificate" placeholder="Has Certificate">
            {!! $errors->first('has_certificate', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="language" class="form-label">{{ __('Language') }}</label>
            <input type="text" name="language" class="form-control @error('language') is-invalid @enderror" value="{{ old('language', $course?->language) }}" id="language" placeholder="Language">
            {!! $errors->first('language', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="video_demo_url" class="form-label">{{ __('Video Demo Url') }}</label>
            <input type="text" name="video_demo_url" class="form-control @error('video_demo_url') is-invalid @enderror" value="{{ old('video_demo_url', $course?->video_demo_url) }}" id="video_demo_url" placeholder="Video Demo Url">
            {!! $errors->first('video_demo_url', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="duration" class="form-label">{{ __('Duration') }}</label>
            <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $course?->duration) }}" id="duration" placeholder="Duration">
            {!! $errors->first('duration', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="view_count" class="form-label">{{ __('View Count') }}</label>
            <input type="text" name="view_count" class="form-control @error('view_count') is-invalid @enderror" value="{{ old('view_count', $course?->view_count) }}" id="view_count" placeholder="View Count">
            {!! $errors->first('view_count', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>