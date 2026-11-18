<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::query()
            ->with(['user:id,name,family', 'provider:id,name,family', 'breed:id,name', 'city:id,name'])
            ->latest()
            ->paginate(20);

        return view('booking.index', compact('bookings'))
            ->with('i', ($request->input('page', 1) - 1) * $bookings->perPage());
    }

    public function create(): View
    {
        $booking = new Booking;

        return view('booking.create', compact('booking'));
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        Booking::create($request->validated());

        return Redirect::route('admin.bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function show($id): View
    {
        $booking = Booking::with(['user', 'provider', 'breed', 'city'])->findOrFail($id);

        return view('booking.show', [
            'booking' => $booking,
            'appointmentJalali' => $booking->appointment_date
                ? Jalalian::fromCarbon($booking->appointment_date)->format('Y/m/d H:i')
                : null,
        ]);
    }

    public function edit($id): View
    {
        $booking = Booking::findOrFail($id);

        return view('booking.edit', compact('booking'));
    }

    public function update(BookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return Redirect::route('admin.bookings.index')
            ->with('success', 'Booking updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Booking::findOrFail($id)->delete();

        return Redirect::route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully');
    }
}
