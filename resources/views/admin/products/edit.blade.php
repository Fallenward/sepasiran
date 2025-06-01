@extends('admin.layout')

@section('template_title', 'ویرایش محصول')

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">ویرایش محصول</span>
                </div>

                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.products.form', ['product' => $product])
                        <button type="submit" class="btn btn-primary mt-3">ذخیره تغییرات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
