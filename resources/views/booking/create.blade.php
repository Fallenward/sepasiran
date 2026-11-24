@extends('admin.layout')

@section('template_title')
    {{ __('Create') }} Booking
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Booking</span>
                    </div>
                    <div class="card-body bg-white">
                        <a class="btn btn-outline-secondary btn-sm mb-3" href="{{ route('admin.bookings.index') }}">بازگشت</a>
                        <form method="POST" action="{{ route('admin.bookings.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('booking.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
