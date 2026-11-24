@extends('admin.layout')

@section('template_title')
    Discounts
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Discounts') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('discounts.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Code</th>
									<th >Role Id</th>
									<th >Course Ids</th>
									<th >User Ids</th>
									<th >Percentage</th>
									<th >Use Count</th>
									<th >Status</th>
									<th >Start Date</th>
									<th >End Date</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($discounts as $discount)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $discount->name }}</td>
										<td >{{ $discount->code }}</td>
										<td >{{ $discount->role_id }}</td>
										<td >{{ $discount->course_ids }}</td>
										<td >{{ $discount->user_ids }}</td>
										<td >{{ $discount->percentage }}</td>
										<td >{{ $discount->use_count }}</td>
										<td >{{ $discount->status }}</td>
										<td >{{ $discount->start_date }}</td>
										<td >{{ $discount->end_date }}</td>

                                            <td>
                                                <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('discounts.show', $discount->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('discounts.edit', $discount->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $discounts->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
