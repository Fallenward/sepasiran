@extends('layouts.app')

@section('template_title')
    {{ $booking->name ?? __('Show') . " " . __('Booking') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Booking</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('bookings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $booking->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Service Type:</strong>
                                    {{ $booking->service_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Provider Id:</strong>
                                    {{ $booking->provider_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>City Id:</strong>
                                    {{ $booking->city_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Appointment Date:</strong>
                                    {{ $booking->appointment_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $booking->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Notes:</strong>
                                    {{ $booking->notes }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
