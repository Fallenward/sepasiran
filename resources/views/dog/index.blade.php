@extends('layouts.app')

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
                                <a href="{{ route('dogs.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Title</th>
									<th >Color</th>
									<th >Age</th>
									<th >Birth Date</th>
									<th >Cover Type</th>
									<th >Eye Color</th>
									<th >Shajare</th>
									<th >Breed Id</th>
									<th >Father Name</th>
									<th >Father Breed Id</th>
									<th >Mother Name</th>
									<th >Mother Breed Id</th>
									<th >Maternal Grandmother Name</th>
									<th >Maternal Grandmother Breed Id</th>
									<th >Maternal Grandfather Name</th>
									<th >Maternal Grandfather Breed Id</th>
									<th >Paternal Grandmother Name</th>
									<th >Paternal Grandmother Breed Id</th>
									<th >Paternal Grandfather Name</th>
									<th >Paternal Grandfather Breed Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dogs as $dog)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $dog->title }}</td>
										<td >{{ $dog->color }}</td>
										<td >{{ $dog->age }}</td>
										<td >{{ $dog->birth_date }}</td>
										<td >{{ $dog->cover_type }}</td>
										<td >{{ $dog->eye_color }}</td>
										<td >{{ $dog->shajare }}</td>
										<td >{{ $dog->breed_id }}</td>
										<td >{{ $dog->father_name }}</td>
										<td >{{ $dog->father_breed_id }}</td>
										<td >{{ $dog->mother_name }}</td>
										<td >{{ $dog->mother_breed_id }}</td>
										<td >{{ $dog->maternal_grandmother_name }}</td>
										<td >{{ $dog->maternal_grandmother_breed_id }}</td>
										<td >{{ $dog->maternal_grandfather_name }}</td>
										<td >{{ $dog->maternal_grandfather_breed_id }}</td>
										<td >{{ $dog->paternal_grandmother_name }}</td>
										<td >{{ $dog->paternal_grandmother_breed_id }}</td>
										<td >{{ $dog->paternal_grandfather_name }}</td>
										<td >{{ $dog->paternal_grandfather_breed_id }}</td>

                                            <td>
                                                <form action="{{ route('dogs.destroy', $dog->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('dogs.show', $dog->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('dogs.edit', $dog->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $dogs->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
