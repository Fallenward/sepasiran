<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="blog_id" class="form-label">{{ __('Blog Id') }}</label>
            <input type="text" name="blog_id" class="form-control @error('blog_id') is-invalid @enderror" value="{{ old('blog_id', $blogRating?->blog_id) }}" id="blog_id" placeholder="Blog Id">
            {!! $errors->first('blog_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="rating" class="form-label">{{ __('Rating') }}</label>
            <input type="text" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating', $blogRating?->rating) }}" id="rating" placeholder="Rating">
            {!! $errors->first('rating', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ip" class="form-label">{{ __('Ip') }}</label>
            <input type="text" name="ip" class="form-control @error('ip') is-invalid @enderror" value="{{ old('ip', $blogRating?->ip) }}" id="ip" placeholder="Ip">
            {!! $errors->first('ip', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>