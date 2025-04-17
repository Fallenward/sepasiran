@extends('layouts.app')

@section('template_title')
    {{ $cart->name ?? __('Show') . " " . __('Cart') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cart</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('carts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $cart->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Course Id:</strong>
                                    {{ $cart->course_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Quantity:</strong>
                                    {{ $cart->quantity }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Price:</strong>
                                    {{ $cart->price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Discount Id:</strong>
                                    {{ $cart->discount_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $cart->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
