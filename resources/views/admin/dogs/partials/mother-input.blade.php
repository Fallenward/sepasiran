<!-- Mother Name -->
<div class="form-group mb-2">
    <label for="mother_name" class="form-label">{{ __('Mother Name') }}</label>
    <input type="text" name="mother_name" class="form-control @error('mother_name') is-invalid @enderror"
           value="{{ old('mother_name', $dog->mother_name ?? '') }}" id="mother_name" placeholder="Mother's Name">
    @error('mother_name')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Mother Breed -->
<div class="form-group mb-2">
    <label for="mother_breed_id" class="form-label">{{ __('Mother Breed') }}</label>
    <select name="mother_breed_id" class="form-control @error('mother_breed_id') is-invalid @enderror">
        <option value="">Select Breed</option>
        @foreach($breeds as $breed)
            <option value="{{ $breed->id }}" {{ old('mother_breed_id', $dog->mother_breed_id ?? '') == $breed->id ? 'selected' : '' }}>
                {{ $breed->name }}
            </option>
        @endforeach
    </select>
    @error('mother_breed_id')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Mother City -->
<div class="form-group mb-2">
    <label for="mother_city_id" class="form-label">{{ __('Mother City') }}</label>
    <select name="mother_city_id" class="form-control @error('mother_city_id') is-invalid @enderror">
        <option value="">Select City</option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}" {{ old('mother_city_id', $dog->mother_city_id ?? '') == $city->id ? 'selected' : '' }}>
                {{ $city->name }}
            </option>
        @endforeach
    </select>
    @error('mother_city_id')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Mother Specialty -->
<div class="form-group mb-2">
    <label for="mother_specialty_id" class="form-label">{{ __('Mother Specialty') }}</label>
    <select name="mother_specialty_id" class="form-control @error('mother_specialty_id') is-invalid @enderror">
        <option value="">Select Specialty</option>
        @foreach($specialties as $specialty)
            <option value="{{ $specialty->id }}" {{ old('mother_specialty_id', $dog->mother_specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                {{ $specialty->name }}
            </option>
        @endforeach
    </select>
    @error('mother_specialty_id')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Mother Description -->
<div class="form-group mb-2">
    <label for="mother_description" class="form-label">{{ __('Mother Description') }}</label>
    <textarea name="mother_description" class="form-control @error('mother_description') is-invalid @enderror"
              id="mother_description" placeholder="Description">{{ old('mother_description', $dog->mother_description ?? '') }}</textarea>
    @error('mother_description')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Mother Image -->
<div class="form-group mb-2">
    <label for="mother_image" class="form-label">{{ __('Mother Image') }}</label>
    <input type="file" name="mother_image" class="form-control @error('mother_image') is-invalid @enderror" id="mother_image">
    @error('mother_image')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>
