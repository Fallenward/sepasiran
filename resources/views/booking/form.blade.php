<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $booking?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="service_type" class="form-label">{{ __('Service Type') }}</label>
            <input type="text" name="service_type" class="form-control @error('service_type') is-invalid @enderror" value="{{ old('service_type', $booking?->service_type) }}" id="service_type" placeholder="Service Type">
            {!! $errors->first('service_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="provider_id" class="form-label">{{ __('Provider Id') }}</label>
            <input type="text" name="provider_id" class="form-control @error('provider_id') is-invalid @enderror" value="{{ old('provider_id', $booking?->provider_id) }}" id="provider_id" placeholder="Provider Id">
            {!! $errors->first('provider_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="city_id" class="form-label">{{ __('City Id') }}</label>
            <input type="text" name="city_id" class="form-control @error('city_id') is-invalid @enderror" value="{{ old('city_id', $booking?->city_id) }}" id="city_id" placeholder="City Id">
            {!! $errors->first('city_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="appointment_date" class="form-label">{{ __('Appointment Date') }}</label>
            <input type="text" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', $booking?->appointment_date) }}" id="appointment_date" placeholder="Appointment Date">
            {!! $errors->first('appointment_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $booking?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $booking?->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>