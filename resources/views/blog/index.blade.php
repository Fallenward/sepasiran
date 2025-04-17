@extends('layouts.app')

@section('template_title')
    Blogs
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Blogs') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('blogs.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Slug</th>
									<th >Content</th>
									<th >Description</th>
									<th >Image</th>
									<th >Category Id</th>
									<th >Author Id</th>
									<th >Rating</th>
									<th >Views</th>
									<th >Likes</th>
									<th >Status</th>
									<th >Comments Enabled</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($blogs as $blog)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $blog->title }}</td>
										<td >{{ $blog->slug }}</td>
										<td >{{ $blog->content }}</td>
										<td >{{ $blog->description }}</td>
										<td >{{ $blog->image }}</td>
										<td >{{ $blog->category_id }}</td>
										<td >{{ $blog->author_id }}</td>
										<td >{{ $blog->rating }}</td>
										<td >{{ $blog->views }}</td>
										<td >{{ $blog->likes }}</td>
										<td >{{ $blog->status }}</td>
										<td >{{ $blog->comments_enabled }}</td>

                                            <td>
                                                <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('blogs.show', $blog->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('blogs.edit', $blog->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $blogs->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
