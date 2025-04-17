<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $insurance?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="slug" class="form-label">{{ __('Slug') }}</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $insurance?->slug) }}" id="slug" placeholder="Slug">
            {!! $errors->first('slug', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $insurance?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="logo" class="form-label">{{ __('Logo') }}</label>
            <input type="text" name="logo" class="form-control @error('logo') is-invalid @enderror" value="{{ old('logo', $insurance?->logo) }}" id="logo" placeholder="Logo">
            {!! $errors->first('logo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="contact_phone" class="form-label">{{ __('Contact Phone') }}</label>
            <input type="text" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $insurance?->contact_phone) }}" id="contact_phone" placeholder="Contact Phone">
            {!! $errors->first('contact_phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="contact_email" class="form-label">{{ __('Contact Email') }}</label>
            <input type="text" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $insurance?->contact_email) }}" id="contact_email" placeholder="Contact Email">
            {!! $errors->first('contact_email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="website" class="form-label">{{ __('Website') }}</label>
            <input type="text" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $insurance?->website) }}" id="website" placeholder="Website">
            {!! $errors->first('website', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_active" class="form-label">{{ __('Is Active') }}</label>
            <input type="text" name="is_active" class="form-control @error('is_active') is-invalid @enderror" value="{{ old('is_active', $insurance?->is_active) }}" id="is_active" placeholder="Is Active">
            {!! $errors->first('is_active', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_featured" class="form-label">{{ __('Is Featured') }}</label>
            <input type="text" name="is_featured" class="form-control @error('is_featured') is-invalid @enderror" value="{{ old('is_featured', $insurance?->is_featured) }}" id="is_featured" placeholder="Is Featured">
            {!! $errors->first('is_featured', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>