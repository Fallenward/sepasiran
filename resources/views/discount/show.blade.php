@extends('admin.layout')

@section('template_title')
    {{ $discount->name ?? __('Show') . " " . __('Discount') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Discount</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('discounts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $discount->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Code:</strong>
                                    {{ $discount->code }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Role Id:</strong>
                                    {{ $discount->role_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Course Ids:</strong>
                                    {{ $discount->course_ids }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User Ids:</strong>
                                    {{ $discount->user_ids }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Percentage:</strong>
                                    {{ $discount->percentage }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Use Count:</strong>
                                    {{ $discount->use_count }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $discount->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Date:</strong>
                                    {{ $discount->start_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>End Date:</strong>
                                    {{ $discount->end_date }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
