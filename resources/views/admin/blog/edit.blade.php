@extends('admin.layout')

@section('template_title')
    {{ __('Update') }} Blog
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Blog</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('admin.blogs.update', $blog->id) }}" role="form"
                              enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('admin.blog.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
