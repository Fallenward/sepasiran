@extends('layouts.app')

@section('template_title')
    {{ $comment->name ?? __('Show') . " " . __('Comment') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Comment</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('comments.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $comment->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Family:</strong>
                                    {{ $comment->family }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Email:</strong>
                                    {{ $comment->email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Phone:</strong>
                                    {{ $comment->phone }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $comment->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Blog Id:</strong>
                                    {{ $comment->blog_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Course Id:</strong>
                                    {{ $comment->course_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Content:</strong>
                                    {{ $comment->content }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Rate:</strong>
                                    {{ $comment->rate }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $comment->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
