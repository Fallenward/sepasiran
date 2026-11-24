@extends('admin.layout')

@section('title', 'سگ: '.($dog->title ?? $dog->id))

@section('content')
@include('admin.components.page-header', [
    'title' => $dog->title ?: 'جزئیات سگ',
    'subtitle' => 'وضعیت: '.($dog->status ?: '—'),
])

<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dogs.index') }}">بازگشت</a>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.dogs.edit', $dog) }}">ویرایش</a>
</div>

<div class="card sp-admin-card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs" id="dogInfoTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">اطلاعات عمومی</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="mother-tab" data-toggle="tab" href="#mother" role="tab">مادر</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="father-tab" data-toggle="tab" href="#father" role="tab">پدر</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                @include('admin.dogs.partials.general-info')
            </div>
            <div class="tab-pane fade" id="mother" role="tabpanel">
                @include('admin.dogs.partials.mother-info')
            </div>
            <div class="tab-pane fade" id="father" role="tabpanel">
                @include('admin.dogs.partials.father-info')
            </div>
        </div>
    </div>
</div>
@endsection