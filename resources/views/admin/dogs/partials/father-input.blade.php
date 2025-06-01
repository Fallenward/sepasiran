<!-- Father Name -->
<div class="form-group mb-2">
    <label for="father_name" class="form-label">{{ __('Father Name') }}</label>
    <input type="text" name="father_name" class="form-control @error('father_name') is-invalid @enderror"
           value="{{ old('father_name', $dog->father_name ?? '') }}" id="father_name" placeholder="Father's Name">
    @error('father_name')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Father Breed -->
<div class="form-group mb-2">
    <label for="father_breed_id" class="form-label">{{ __('Father Breed') }}</label>
    <select name="father_breed_id" class="form-control @error('father_breed_id') is-invalid @enderror">
        <option value="">Select Breed</option>
        @foreach($breeds as $breed)
            <option value="{{ $breed->id }}" {{ old('father_breed_id', $dog->father_breed_id ?? '') == $breed->id ? 'selected' : '' }}>
                {{ $breed->name }}
            </option>
        @endforeach
    </select>
    @error('father_breed_id')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Father City -->
<div class="form-group mb-2">
    <label for="father_city_id" class="form-label">{{ __('Father City') }}</label>
    <select name="father_city_id" class="form-control @error('father_city_id') is-invalid @enderror">
        <option value="">Select City</option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}" {{ old('father_city_id', $dog->father_city_id ?? '') == $city->id ? 'selected' : '' }}>
                {{ $city->name }}
            </option>
        @endforeach
    </select>
    @error('father_city_id')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Father Specialty -->
<div class="form-group mb-2">
    <label for="father_specialty_id" class="form-label">{{ __('Father Specialty') }}</label>
    <select name="father_specialty_id" class="form-control @error('father_specialty_id') is-invalid @enderror">
        <option value="">Select Specialty</option>
        @foreach($specialties as $specialty)
            <option value="{{ $specialty->id }}" {{ old('father_specialty_id', $dog->father_specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                {{ $specialty->name }}
            </option>
        @endforeach
    </select>
    @error('father_specialty_id')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Father Description -->
<div class="form-group mb-2">
    <label for="father_description" class="form-label">{{ __('Father Description') }}</label>
    <textarea name="father_description" class="form-control @error('father_description') is-invalid @enderror"
              id="father_description" placeholder="Description">{{ old('father_description', $dog->father_description ?? '') }}</textarea>
    @error('father_description')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>

<!-- Father Image -->
<div class="form-group mb-2">
    <label for="father_image" class="form-label">{{ __('Father Image') }}</label>
    <input type="file" name="father_image" class="form-control @error('father_image') is-invalid @enderror" id="father_image">
    @error('father_image')
        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
    @enderror
</div>
