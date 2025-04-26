@extends('admin.layout')
@section('template_title')
    {{ $transaction->name ?? __('Show') . " " . __('Transaction') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Transaction</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.transactions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $transaction->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Transaction Id:</strong>
                                    {{ $transaction->transaction_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cart Ids:</strong>
                                    {{ $transaction->cart_ids }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $transaction->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Card Number:</strong>
                                    {{ $transaction->card_number }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ref Id:</strong>
                                    {{ $transaction->ref_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $transaction->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Amount:</strong>
                                    {{ $transaction->amount }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cart Price:</strong>
                                    {{ $transaction->cart_price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tax:</strong>
                                    {{ $transaction->tax }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Discount:</strong>
                                    {{ $transaction->discount }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Payment Method:</strong>
                                    {{ $transaction->payment_method }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
