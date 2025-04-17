@extends('layouts.app')

@section('template_title')
    {{ $product->name ?? __('Show') . " " . __('Product') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Product</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $product->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $product->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Slug:</strong>
                                    {{ $product->slug }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Price:</strong>
                                    {{ $product->price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Discount Price:</strong>
                                    {{ $product->discount_price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Stock:</strong>
                                    {{ $product->stock }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Active:</strong>
                                    {{ $product->is_active }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Featured:</strong>
                                    {{ $product->is_featured }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sku:</strong>
                                    {{ $product->sku }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Barcode:</strong>
                                    {{ $product->barcode }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Category Id:</strong>
                                    {{ $product->category_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Brand Id:</strong>
                                    {{ $product->brand_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Thumbnail:</strong>
                                    {{ $product->thumbnail }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Images:</strong>
                                    {{ $product->images }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Buy Link:</strong>
                                    {{ $product->buy_link }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Created By:</strong>
                                    {{ $product->created_by }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Updated By:</strong>
                                    {{ $product->updated_by }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
