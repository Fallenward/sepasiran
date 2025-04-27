@extends('admin.layout')

@section('template_title')
    {{ $dog->name ?? "Dog Details" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg rounded-lg">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dog Details</h5>
                        <a href="{{ route('admin.dogs.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body bg-light">
                        <div class="row">
                            <!-- نام -->
                            <div class="col-md-6">
                                <strong class="d-block text-muted">Name:</strong>
                                <span class="text-dark">{{ $dog->name }}</span>
                            </div>

                            <!-- شهر -->
                            <div class="col-md-6">
                                <strong class="d-block text-muted">City:</strong>
                                <span class="text-dark">{{ $dog->city->name }}</span>
                            </div>

                            <!-- نژاد -->
                            <div class="col-md-6">
                                <strong class="d-block text-muted">Breed:</strong>
                                <span class="text-dark">{{ $dog->breed->name }}</span>
                            </div>

                            <!-- اندازه -->
                            <div class="col-md-6">
                                <strong class="d-block text-muted">Size:</strong>
                                <span class="text-dark">{{ $dog->size }}</span>
                            </div>

                            <!-- توضیحات -->
                            <div class="col-md-12">
                                <strong class="d-block text-muted">Description:</strong>
                                <p class="text-dark">{{ $dog->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
