@extends('layouts.app')

@section('template_title')
    {{ $insurance->name ?? __('Show') . " " . __('Insurance') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Insurance</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('insurances.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $insurance->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Slug:</strong>
                                    {{ $insurance->slug }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $insurance->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Logo:</strong>
                                    {{ $insurance->logo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Contact Phone:</strong>
                                    {{ $insurance->contact_phone }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Contact Email:</strong>
                                    {{ $insurance->contact_email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Website:</strong>
                                    {{ $insurance->website }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Active:</strong>
                                    {{ $insurance->is_active }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Featured:</strong>
                                    {{ $insurance->is_featured }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
