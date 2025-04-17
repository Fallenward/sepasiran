<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $commentAnswer?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="blog_id" class="form-label">{{ __('Blog Id') }}</label>
            <input type="text" name="blog_id" class="form-control @error('blog_id') is-invalid @enderror" value="{{ old('blog_id', $commentAnswer?->blog_id) }}" id="blog_id" placeholder="Blog Id">
            {!! $errors->first('blog_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="course_id" class="form-label">{{ __('Course Id') }}</label>
            <input type="text" name="course_id" class="form-control @error('course_id') is-invalid @enderror" value="{{ old('course_id', $commentAnswer?->course_id) }}" id="course_id" placeholder="Course Id">
            {!! $errors->first('course_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="comment_id" class="form-label">{{ __('Comment Id') }}</label>
            <input type="text" name="comment_id" class="form-control @error('comment_id') is-invalid @enderror" value="{{ old('comment_id', $commentAnswer?->comment_id) }}" id="comment_id" placeholder="Comment Id">
            {!! $errors->first('comment_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="content" class="form-label">{{ __('Content') }}</label>
            <input type="text" name="content" class="form-control @error('content') is-invalid @enderror" value="{{ old('content', $commentAnswer?->content) }}" id="content" placeholder="Content">
            {!! $errors->first('content', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $commentAnswer?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>