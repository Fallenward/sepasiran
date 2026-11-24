@extends('admin.layout')

@section('title', 'رزروها')

@section('content')
@include('admin.components.page-header', [
    'title' => 'رزروها',
    'subtitle' => 'پیگیری وضعیت، پرداخت و ارائه‌دهنده.',
    'createUrl' => route('admin.bookings.create'),
    'createLabel' => 'رزرو جدید',
])

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card sp-admin-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover sp-admin-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>کد</th>
                    <th>مشتری</th>
                    <th>خدمت</th>
                    <th>ارائه‌دهنده</th>
                    <th>زمان</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>پرداخت</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    @php
                        $customer = trim(($booking->user->name ?? '').' '.($booking->user->family ?? ''));
                        $provider = trim(($booking->provider->name ?? '').' '.($booking->provider->family ?? ''));
                        $status = strtoupper((string) $booking->status);
                        $statusClass = in_array($status, ['COMPLETED','CONFIRMED'], true) ? 'is-ok' : (in_array($status, ['CANCELLED','REJECTED'], true) ? 'is-bad' : 'is-warn');
                        $pay = (string) ($booking->payment_status ?: '—');
                    @endphp
                    <tr>
                        <td class="num">{{ $booking->id }}</td>
                        <td class="num"><strong>{{ $booking->booking_code ?: '—' }}</strong></td>
                        <td>{{ $customer !== '' ? $customer : ('#'.$booking->user_id) }}</td>
                        <td>{{ $booking->service_type ?: '—' }}</td>
                        <td>{{ $provider !== '' ? $provider : ($booking->provider_id ? '#'.$booking->provider_id : '—') }}</td>
                        <td class="num">
                            @if($booking->appointment_date)
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($booking->appointment_date)->format('Y/m/d') }}
                                {{ $booking->slot ?: $booking->appointment_date->format('H:i') }}
                            @elseif($booking->preferred_date)
                                {{ \Morilog\Jalali\Jalalian::fromDateTime($booking->preferred_date)->format('Y/m/d') }}
                                {{ $booking->preferred_slot ?: '' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="num">{{ number_format((float) ($booking->total ?? $booking->price ?? 0)) }}</td>
                        <td><span class="sp-admin-status {{ $statusClass }}">{{ $booking->status ?: '—' }}</span></td>
                        <td><span class="sp-admin-status {{ $pay === 'paid' ? 'is-ok' : 'is-warn' }}">{{ $pay }}</span></td>
                        <td class="sp-admin-actions">
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.bookings.show', $booking) }}">نمایش</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.bookings.edit', $booking) }}">ویرایش</a>
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">رزروی ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->withQueryString()->links() }}
    </div>
</div>
@endsection