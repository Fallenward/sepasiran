@extends('admin.layout')

@section('template_title')
    Bookings
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Bookings') }}</span>
                            <div class="float-right">
                                <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm float-right">
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
                                        <th>Code</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Provider</th>
                                        <th>Appointment</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td class="num">{{ $booking->booking_code }}</td>
                                            <td>{{ trim(($booking->user->name ?? '').' '.($booking->user->family ?? '')) ?: $booking->user_id }}</td>
                                            <td>{{ $booking->service_type }}</td>
                                            <td>{{ trim(($booking->provider->name ?? '').' '.($booking->provider->family ?? '')) ?: $booking->provider_id }}</td>
                                            <td class="num">
                                                @if($booking->appointment_date)
                                                    {{ \Morilog\Jalali\Jalalian::fromCarbon($booking->appointment_date)->format('Y/m/d') }}
                                                    {{ $booking->slot ?: $booking->appointment_date->format('H:i') }}
                                                @endif
                                            </td>
                                            <td class="num">{{ number_format((float) ($booking->total ?? $booking->price)) }}</td>
                                            <td>{{ $booking->status }}</td>
                                            <td>
                                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.bookings.show', $booking->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('admin.bookings.edit', $booking->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $bookings->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
