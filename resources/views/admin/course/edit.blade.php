@extends('admin.layout')

@section('template_title')
    {{ __('Update') }} Course
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Course</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('admin.courses.update', $course->id) }}" role="form"
                              enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('admin.course.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
