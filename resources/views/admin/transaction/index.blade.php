@extends('admin.layout')

@section('template_title')
    Transactions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Transactions') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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

									<th >User Id</th>
									<th >Transaction Id</th>
									<th >Cart Ids</th>
									<th >Description</th>
									<th >Card Number</th>
									<th >Ref Id</th>
									<th >Status</th>
									<th >Amount</th>
									<th >Cart Price</th>
									<th >Tax</th>
									<th >Discount</th>
									<th >Payment Method</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>

										<td >{{ $transaction->user_id }}</td>
										<td >{{ $transaction->transaction_id }}</td>
										<td >{{ $transaction->cart_ids }}</td>
										<td >{{ $transaction->description }}</td>
										<td >{{ $transaction->card_number }}</td>
										<td >{{ $transaction->ref_id }}</td>
										<td >{{ $transaction->status }}</td>
										<td >{{ $transaction->amount }}</td>
										<td >{{ $transaction->cart_price }}</td>
										<td >{{ $transaction->tax }}</td>
										<td >{{ $transaction->discount }}</td>
										<td >{{ $transaction->payment_method }}</td>

                                            <td>
                                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('admin.transactions.show', $transaction->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('admin.transactions.edit', $transaction->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                <div class="d-flex justify-content-center mt-4">
                    {!! $transactions->withQueryString()->links() !!}
                </div>

            </div>
        </div>
    </div>
@endsection
