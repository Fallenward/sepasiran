@extends('admin.layout')

@section('template_title')
    {{ __('Update') }} Booking
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Booking</span>
                    </div>
                    <div class="card-body bg-white">
                        <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.bookings.index') }}">بازگشت</a>
                        <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('booking.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
