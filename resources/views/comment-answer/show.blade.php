@extends('layouts.app')

@section('template_title')
    {{ $commentAnswer->name ?? __('Show') . " " . __('Comment Answer') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Comment Answer</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('comment-answers.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $commentAnswer->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Blog Id:</strong>
                                    {{ $commentAnswer->blog_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Course Id:</strong>
                                    {{ $commentAnswer->course_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Comment Id:</strong>
                                    {{ $commentAnswer->comment_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Content:</strong>
                                    {{ $commentAnswer->content }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $commentAnswer->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
