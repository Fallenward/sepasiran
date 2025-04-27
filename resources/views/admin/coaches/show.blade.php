@extends('admin.layout')

@section('template_title')
    {{ $coach->name ?? "Coach Details" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg rounded-lg">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Coach Details</h5>
                        <a href="{{ route('admin.coaches.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <strong class="d-block text-muted">Name:</strong>
                                <span class="text-dark">{{ $coach->name }}</span>
                            </div>

                            <div class="col-md-6">
                                <strong class="d-block text-muted">City:</strong>
                                <span class="text-dark">{{ $coach->city->name }}</span>
                            </div>

                            <div class="col-md-6">
                                <strong class="d-block text-muted">Breed:</strong>
                                <span class="text-dark">{{ $coach->breed->name }}</span>
                            </div>

                            <div class="col-md-6">
                                <strong class="d-block text-muted">Specialty:</strong>
                                <span class="text-dark">{{ $coach->specialty->name }}</span>
                            </div>

                            <div class="col-md-12">
                                <strong class="d-block text-muted">Description:</strong>
                                <p class="text-dark">{{ $coach->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
