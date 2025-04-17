@extends('layouts.app')

@section('template_title')
    {{ $course->name ?? __('Show') . " " . __('Course') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Course</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('courses.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $course->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Slug:</strong>
                                    {{ $course->slug }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Short Description:</strong>
                                    {{ $course->short_description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $course->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Thumbnail:</strong>
                                    {{ $course->thumbnail }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Instructor Id:</strong>
                                    {{ $course->instructor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Category Id:</strong>
                                    {{ $course->category_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Level Id:</strong>
                                    {{ $course->level_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Price:</strong>
                                    {{ $course->price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Discount Price:</strong>
                                    {{ $course->discount_price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $course->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Featured:</strong>
                                    {{ $course->is_featured }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Has Certificate:</strong>
                                    {{ $course->has_certificate }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Language:</strong>
                                    {{ $course->language }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Video Demo Url:</strong>
                                    {{ $course->video_demo_url }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Duration:</strong>
                                    {{ $course->duration }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>View Count:</strong>
                                    {{ $course->view_count }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
