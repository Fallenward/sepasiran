@extends('layouts.app')

@section('template_title')
    {{ $blogRating->name ?? __('Show') . " " . __('Blog Rating') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Blog Rating</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('blog-ratings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Blog Id:</strong>
                                    {{ $blogRating->blog_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Rating:</strong>
                                    {{ $blogRating->rating }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ip:</strong>
                                    {{ $blogRating->ip }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
