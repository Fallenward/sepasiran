@extends('admin.layout')

@section('template_title')
    Dogs
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Dogs') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('admin.dogs.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                    {{ __('Create New Dog') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>City</th>
                                    <th>Breed</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1; // مقداردهی اولیه به $i
                                @endphp
                                @foreach ($dogs as $dog)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $dog->name }}</td>
                                        <td>{{ optional($dog->city)->name ?? 'No city' }}</td> <!-- استفاده از optional برای جلوگیری از خطا -->
                                        <td>{{ optional($dog->breed)->name ?? 'No breed' }}</td> <!-- استفاده از optional برای جلوگیری از خطا -->
                                        <td>{{ $dog->size }}</td>
                                        <td>
                                            <form action="{{ route('admin.dogs.destroy', $dog->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary" href="{{ route('admin.dogs.show', $dog->id) }}">
                                                    <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('admin.dogs.edit', $dog->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $dogs->withQueryString()->links() !!} <!-- Paginate dogs -->
            </div>
        </div>
    </div>
@endsection
