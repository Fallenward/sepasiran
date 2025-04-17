@extends('layouts.app')

@section('template_title')
    Comment Answers
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Comment Answers') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('comment-answers.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Blog Id</th>
									<th >Course Id</th>
									<th >Comment Id</th>
									<th >Content</th>
									<th >Status</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commentAnswers as $commentAnswer)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $commentAnswer->user_id }}</td>
										<td >{{ $commentAnswer->blog_id }}</td>
										<td >{{ $commentAnswer->course_id }}</td>
										<td >{{ $commentAnswer->comment_id }}</td>
										<td >{{ $commentAnswer->content }}</td>
										<td >{{ $commentAnswer->status }}</td>

                                            <td>
                                                <form action="{{ route('comment-answers.destroy', $commentAnswer->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('comment-answers.show', $commentAnswer->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('comment-answers.edit', $commentAnswer->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $commentAnswers->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
