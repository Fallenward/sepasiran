    @extends('admin.layout')

    @section('template_title', 'ایجاد محصول جدید')

    @section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">ایجاد محصول جدید</span>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('admin.products.store') }}">
                            @csrf
                            @include('admin.products.form', ['product' => null])
                            <button type="submit" class="btn btn-primary mt-3">ذخیره</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
