@extends('layouts.app')

@section('template_title')
    {{ $dog->name ?? __('Show') . " " . __('Dog') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Dog</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('dogs.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $dog->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color:</strong>
                                    {{ $dog->color }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Age:</strong>
                                    {{ $dog->age }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Birth Date:</strong>
                                    {{ $dog->birth_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cover Type:</strong>
                                    {{ $dog->cover_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Eye Color:</strong>
                                    {{ $dog->eye_color }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Shajare:</strong>
                                    {{ $dog->shajare }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Breed Id:</strong>
                                    {{ $dog->breed_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Father Name:</strong>
                                    {{ $dog->father_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Father Breed Id:</strong>
                                    {{ $dog->father_breed_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Mother Name:</strong>
                                    {{ $dog->mother_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Mother Breed Id:</strong>
                                    {{ $dog->mother_breed_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Maternal Grandmother Name:</strong>
                                    {{ $dog->maternal_grandmother_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Maternal Grandmother Breed Id:</strong>
                                    {{ $dog->maternal_grandmother_breed_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Maternal Grandfather Name:</strong>
                                    {{ $dog->maternal_grandfather_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Maternal Grandfather Breed Id:</strong>
                                    {{ $dog->maternal_grandfather_breed_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Paternal Grandmother Name:</strong>
                                    {{ $dog->paternal_grandmother_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Paternal Grandmother Breed Id:</strong>
                                    {{ $dog->paternal_grandmother_breed_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Paternal Grandfather Name:</strong>
                                    {{ $dog->paternal_grandfather_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Paternal Grandfather Breed Id:</strong>
                                    {{ $dog->paternal_grandfather_breed_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
