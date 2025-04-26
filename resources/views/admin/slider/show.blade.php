@extends('admin.layout')

@section('template_title')
    {{ $slider->name ?? __('Show') . " " . __('Slider') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Slider</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.sliders.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $slider->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Image:</strong>
                                    {{ $slider->image }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Link:</strong>
                                    {{ $slider->link }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
