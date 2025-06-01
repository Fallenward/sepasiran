@extends('admin.layout')

@section('template_title')
    {{ __('Add') }} Dog
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">{{ __('Add') }} Dog</h2>

    <ul class="nav nav-tabs" id="dogTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="dog-tab" data-bs-toggle="tab" data-bs-target="#dog" type="button" role="tab">اطلاعات سگ</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="mother-tab" data-bs-toggle="tab" data-bs-target="#mother" type="button" role="tab">اطلاعات مادر</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="father-tab" data-bs-toggle="tab" data-bs-target="#father" type="button" role="tab">اطلاعات پدر</button>
        </li>
    </ul>

    <form method="POST" action="{{ route('admin.dogs.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="tab-content mt-3">

            <!-- Dog Info Tab -->
            <div class="tab-pane fade show active" id="dog" role="tabpanel">
                <div class="row p-1">
                    <div class="col-md-12">
                        @include('admin.dogs.partials.dog-input')
                    </div>
                </div>
            </div>

            <!-- Mother Info Tab -->
            <div class="tab-pane fade" id="mother" role="tabpanel">
                <div class="row p-1">
                    <div class="col-md-12">
                        @include('admin.dogs.partials.mother-input')
                    </div>
                </div>
            </div>

            <!-- Father Info Tab -->
            <div class="tab-pane fade" id="father" role="tabpanel">
                <div class="row p-1">
                    <div class="col-md-12">
                        @include('admin.dogs.partials.father-input')
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
