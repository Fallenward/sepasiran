@extends('admin.layout')

@section('template_title')
    {{ __('Edit') }} Dog
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Edit') }} Dog</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('admin.dogs.update', $dog->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            @include('admin.dogs.form')

                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
