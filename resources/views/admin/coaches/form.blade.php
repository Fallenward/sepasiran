<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="city_id" class="form-label">{{ __('City') }}</label>
            <select name="city_id" class="form-control @error('city_id') is-invalid @enderror">
                <option value="">Select City</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ old('city_id', $coach->city_id ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('city_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="breed_id" class="form-label">{{ __('Breed') }}</label>
            <select name="breed_id" class="form-control @error('breed_id') is-invalid @enderror">
                <option value="">Select Breed</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}" {{ old('breed_id', $coach->breed_id ?? '') == $breed->id ? 'selected' : '' }}>
                        {{ $breed->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="specialty_id" class="form-label">{{ __('Specialty') }}</label>
            <select name="specialty_id" class="form-control @error('specialty_id') is-invalid @enderror">
                <option value="">Select Specialty</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}" {{ old('specialty_id', $coach->specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('specialty_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description">{{ old('description') }}</textarea>
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
