@extends('admin.layout')

@section('template_title')
    {{ __('Create') }} Coach
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Coach</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('admin.coaches.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('admin.coaches.form') <!-- include form for coach details -->

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
