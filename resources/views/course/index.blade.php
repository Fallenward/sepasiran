@extends('layouts.app')

@section('template_title')
    Courses
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Courses') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Short Description</th>
									<th >Description</th>
									<th >Thumbnail</th>
									<th >Instructor Id</th>
									<th >Category Id</th>
									<th >Level Id</th>
									<th >Price</th>
									<th >Discount Price</th>
									<th >Status</th>
									<th >Is Featured</th>
									<th >Has Certificate</th>
									<th >Language</th>
									<th >Video Demo Url</th>
									<th >Duration</th>
									<th >View Count</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $course->title }}</td>
										<td >{{ $course->slug }}</td>
										<td >{{ $course->short_description }}</td>
										<td >{{ $course->description }}</td>
										<td >{{ $course->thumbnail }}</td>
										<td >{{ $course->instructor_id }}</td>
										<td >{{ $course->category_id }}</td>
										<td >{{ $course->level_id }}</td>
										<td >{{ $course->price }}</td>
										<td >{{ $course->discount_price }}</td>
										<td >{{ $course->status }}</td>
										<td >{{ $course->is_featured }}</td>
										<td >{{ $course->has_certificate }}</td>
										<td >{{ $course->language }}</td>
										<td >{{ $course->video_demo_url }}</td>
										<td >{{ $course->duration }}</td>
										<td >{{ $course->view_count }}</td>

                                            <td>
                                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('courses.show', $course->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('courses.edit', $course->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $courses->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
