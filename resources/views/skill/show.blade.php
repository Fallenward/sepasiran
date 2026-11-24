@extends('admin.layout')

@section('template_title')
    {{ $skill->name ?? __('Show') . " " . __('Skill') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Skill</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('skills.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $skill->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $skill->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Active:</strong>
                                    {{ $skill->is_active }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
