<div class="row p-1">
    <div class="col-md-12">

        <!-- Title (Name) -->
        <div class="form-group mb-2">
            <label for="title" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $dog->title ?? '') }}" id="title" placeholder="Dog Name">
            @error('title')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- City -->
        <div class="form-group mb-2">
            <label for="city_id" class="form-label">{{ __('City') }}</label>
            <select name="city_id" class="form-control @error('city_id') is-invalid @enderror">
                <option value="">Select City</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ old('city_id', $dog->city_id ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            @error('city_id')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Breed -->
        <div class="form-group mb-2">
            <label for="breed_id" class="form-label">{{ __('Breed') }}</label>
            <select name="breed_id" class="form-control @error('breed_id') is-invalid @enderror">
                <option value="">Select Breed</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}" {{ old('breed_id', $dog->breed_id ?? '') == $breed->id ? 'selected' : '' }}>
                        {{ $breed->name }}
                    </option>
                @endforeach
            </select>
            @error('breed_id')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Specialty (if applicable) -->
        <div class="form-group mb-2">
            <label for="specialty_id" class="form-label">{{ __('Specialty') }}</label>
            <select name="specialty_id" class="form-control @error('specialty_id') is-invalid @enderror">
                <option value="">Select Specialty</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}" {{ old('specialty_id', $dog->specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
            @error('specialty_id')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-2">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                      id="description" placeholder="Dog Description">{{ old('description', $dog->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Image -->
        <div class="form-group mb-2">
            <label for="image" class="form-label">{{ __('Image') }}</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image">
            @error('image')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
<div class="row p-1">
    <div class="col-md-12">

        <!-- Title (Name) -->
        <div class="form-group mb-2">
            <label for="title" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $dog->title ?? '') }}" id="title" placeholder="Dog Name">
            @error('title')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- City -->
        <div class="form-group mb-2">
            <label for="city_id" class="form-label">{{ __('City') }}</label>
            <select name="city_id" class="form-control @error('city_id') is-invalid @enderror">
                <option value="">Select City</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ old('city_id', $dog->city_id ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            @error('city_id')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Breed -->
        <div class="form-group mb-2">
            <label for="breed_id" class="form-label">{{ __('Breed') }}</label>
            <select name="breed_id" class="form-control @error('breed_id') is-invalid @enderror">
                <option value="">Select Breed</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}" {{ old('breed_id', $dog->breed_id ?? '') == $breed->id ? 'selected' : '' }}>
                        {{ $breed->name }}
                    </option>
                @endforeach
            </select>
            @error('breed_id')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Specialty (if applicable) -->
        <div class="form-group mb-2">
            <label for="specialty_id" class="form-label">{{ __('Specialty') }}</label>
            <select name="specialty_id" class="form-control @error('specialty_id') is-invalid @enderror">
                <option value="">Select Specialty</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}" {{ old('specialty_id', $dog->specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
            @error('specialty_id')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group mb-2">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                      id="description" placeholder="Dog Description">{{ old('description', $dog->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <!-- Image -->
        <div class="form-group mb-2">
            <label for="image" class="form-label">{{ __('Image') }}</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image">
            @error('image')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
