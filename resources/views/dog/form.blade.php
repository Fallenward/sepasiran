<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $dog?->title) }}" id="title" placeholder="Title">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Color') }}</label>
            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $dog?->color) }}" id="color" placeholder="Color">
            {!! $errors->first('color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="age" class="form-label">{{ __('Age') }}</label>
            <input type="text" name="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age', $dog?->age) }}" id="age" placeholder="Age">
            {!! $errors->first('age', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="birth_date" class="form-label">{{ __('Birth Date') }}</label>
            <input type="text" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $dog?->birth_date) }}" id="birth_date" placeholder="Birth Date">
            {!! $errors->first('birth_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cover_type" class="form-label">{{ __('Cover Type') }}</label>
            <input type="text" name="cover_type" class="form-control @error('cover_type') is-invalid @enderror" value="{{ old('cover_type', $dog?->cover_type) }}" id="cover_type" placeholder="Cover Type">
            {!! $errors->first('cover_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="eye_color" class="form-label">{{ __('Eye Color') }}</label>
            <input type="text" name="eye_color" class="form-control @error('eye_color') is-invalid @enderror" value="{{ old('eye_color', $dog?->eye_color) }}" id="eye_color" placeholder="Eye Color">
            {!! $errors->first('eye_color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="shajare" class="form-label">{{ __('Shajare') }}</label>
            <input type="text" name="shajare" class="form-control @error('shajare') is-invalid @enderror" value="{{ old('shajare', $dog?->shajare) }}" id="shajare" placeholder="Shajare">
            {!! $errors->first('shajare', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="breed_id" class="form-label">{{ __('Breed Id') }}</label>
            <input type="text" name="breed_id" class="form-control @error('breed_id') is-invalid @enderror" value="{{ old('breed_id', $dog?->breed_id) }}" id="breed_id" placeholder="Breed Id">
            {!! $errors->first('breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="father_name" class="form-label">{{ __('Father Name') }}</label>
            <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror" value="{{ old('father_name', $dog?->father_name) }}" id="father_name" placeholder="Father Name">
            {!! $errors->first('father_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="father_breed_id" class="form-label">{{ __('Father Breed Id') }}</label>
            <input type="text" name="father_breed_id" class="form-control @error('father_breed_id') is-invalid @enderror" value="{{ old('father_breed_id', $dog?->father_breed_id) }}" id="father_breed_id" placeholder="Father Breed Id">
            {!! $errors->first('father_breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="mother_name" class="form-label">{{ __('Mother Name') }}</label>
            <input type="text" name="mother_name" class="form-control @error('mother_name') is-invalid @enderror" value="{{ old('mother_name', $dog?->mother_name) }}" id="mother_name" placeholder="Mother Name">
            {!! $errors->first('mother_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="mother_breed_id" class="form-label">{{ __('Mother Breed Id') }}</label>
            <input type="text" name="mother_breed_id" class="form-control @error('mother_breed_id') is-invalid @enderror" value="{{ old('mother_breed_id', $dog?->mother_breed_id) }}" id="mother_breed_id" placeholder="Mother Breed Id">
            {!! $errors->first('mother_breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="maternal_grandmother_name" class="form-label">{{ __('Maternal Grandmother Name') }}</label>
            <input type="text" name="maternal_grandmother_name" class="form-control @error('maternal_grandmother_name') is-invalid @enderror" value="{{ old('maternal_grandmother_name', $dog?->maternal_grandmother_name) }}" id="maternal_grandmother_name" placeholder="Maternal Grandmother Name">
            {!! $errors->first('maternal_grandmother_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="maternal_grandmother_breed_id" class="form-label">{{ __('Maternal Grandmother Breed Id') }}</label>
            <input type="text" name="maternal_grandmother_breed_id" class="form-control @error('maternal_grandmother_breed_id') is-invalid @enderror" value="{{ old('maternal_grandmother_breed_id', $dog?->maternal_grandmother_breed_id) }}" id="maternal_grandmother_breed_id" placeholder="Maternal Grandmother Breed Id">
            {!! $errors->first('maternal_grandmother_breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="maternal_grandfather_name" class="form-label">{{ __('Maternal Grandfather Name') }}</label>
            <input type="text" name="maternal_grandfather_name" class="form-control @error('maternal_grandfather_name') is-invalid @enderror" value="{{ old('maternal_grandfather_name', $dog?->maternal_grandfather_name) }}" id="maternal_grandfather_name" placeholder="Maternal Grandfather Name">
            {!! $errors->first('maternal_grandfather_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="maternal_grandfather_breed_id" class="form-label">{{ __('Maternal Grandfather Breed Id') }}</label>
            <input type="text" name="maternal_grandfather_breed_id" class="form-control @error('maternal_grandfather_breed_id') is-invalid @enderror" value="{{ old('maternal_grandfather_breed_id', $dog?->maternal_grandfather_breed_id) }}" id="maternal_grandfather_breed_id" placeholder="Maternal Grandfather Breed Id">
            {!! $errors->first('maternal_grandfather_breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paternal_grandmother_name" class="form-label">{{ __('Paternal Grandmother Name') }}</label>
            <input type="text" name="paternal_grandmother_name" class="form-control @error('paternal_grandmother_name') is-invalid @enderror" value="{{ old('paternal_grandmother_name', $dog?->paternal_grandmother_name) }}" id="paternal_grandmother_name" placeholder="Paternal Grandmother Name">
            {!! $errors->first('paternal_grandmother_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paternal_grandmother_breed_id" class="form-label">{{ __('Paternal Grandmother Breed Id') }}</label>
            <input type="text" name="paternal_grandmother_breed_id" class="form-control @error('paternal_grandmother_breed_id') is-invalid @enderror" value="{{ old('paternal_grandmother_breed_id', $dog?->paternal_grandmother_breed_id) }}" id="paternal_grandmother_breed_id" placeholder="Paternal Grandmother Breed Id">
            {!! $errors->first('paternal_grandmother_breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paternal_grandfather_name" class="form-label">{{ __('Paternal Grandfather Name') }}</label>
            <input type="text" name="paternal_grandfather_name" class="form-control @error('paternal_grandfather_name') is-invalid @enderror" value="{{ old('paternal_grandfather_name', $dog?->paternal_grandfather_name) }}" id="paternal_grandfather_name" placeholder="Paternal Grandfather Name">
            {!! $errors->first('paternal_grandfather_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paternal_grandfather_breed_id" class="form-label">{{ __('Paternal Grandfather Breed Id') }}</label>
            <input type="text" name="paternal_grandfather_breed_id" class="form-control @error('paternal_grandfather_breed_id') is-invalid @enderror" value="{{ old('paternal_grandfather_breed_id', $dog?->paternal_grandfather_breed_id) }}" id="paternal_grandfather_breed_id" placeholder="Paternal Grandfather Breed Id">
            {!! $errors->first('paternal_grandfather_breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>