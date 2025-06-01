@extends('admin.layout')

@section('template_title')
    نمایش سگ
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">جزئیات سگ: {{ $dog->title }}</h2>

    <ul class="nav nav-tabs" id="dogInfoTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">اطلاعات عمومی</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="mother-tab" data-bs-toggle="tab" data-bs-target="#mother" type="button" role="tab">اطلاعات مادر</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="father-tab" data-bs-toggle="tab" data-bs-target="#father" type="button" role="tab">اطلاعات پدر</button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- General Info -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            @include('admin.dogs.partials.general-info')
        </div>

        <!-- Mother Info -->
        <div class="tab-pane fade" id="mother" role="tabpanel">
            @include('admin.dogs.partials.mother-info')
        </div>

        <!-- Father Info -->
        <div class="tab-pane fade" id="father" role="tabpanel">
            @include('admin.dogs.partials.father-info')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
