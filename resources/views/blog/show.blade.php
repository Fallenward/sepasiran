@extends('layouts.app')

@section('template_title')
    {{ $blog->name ?? __('Show') . " " . __('Blog') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Blog</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('blogs.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $blog->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Slug:</strong>
                                    {{ $blog->slug }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Content:</strong>
                                    {{ $blog->content }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $blog->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Image:</strong>
                                    {{ $blog->image }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Category Id:</strong>
                                    {{ $blog->category_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Author Id:</strong>
                                    {{ $blog->author_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Rating:</strong>
                                    {{ $blog->rating }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Views:</strong>
                                    {{ $blog->views }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Likes:</strong>
                                    {{ $blog->likes }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $blog->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Comments Enabled:</strong>
                                    {{ $blog->comments_enabled }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
