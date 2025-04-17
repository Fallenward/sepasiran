@extends('layouts.app')

@section('template_title')
    Insurances
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Insurances') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('insurances.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
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
                                        
									<th >Name</th>
									<th >Slug</th>
									<th >Description</th>
									<th >Logo</th>
									<th >Contact Phone</th>
									<th >Contact Email</th>
									<th >Website</th>
									<th >Is Active</th>
									<th >Is Featured</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($insurances as $insurance)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $insurance->name }}</td>
										<td >{{ $insurance->slug }}</td>
										<td >{{ $insurance->description }}</td>
										<td >{{ $insurance->logo }}</td>
										<td >{{ $insurance->contact_phone }}</td>
										<td >{{ $insurance->contact_email }}</td>
										<td >{{ $insurance->website }}</td>
										<td >{{ $insurance->is_active }}</td>
										<td >{{ $insurance->is_featured }}</td>

                                            <td>
                                                <form action="{{ route('insurances.destroy', $insurance->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('insurances.show', $insurance->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('insurances.edit', $insurance->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $insurances->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
