<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\City;
use App\Models\Specialty;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all cities and specialties for filters
        $cities = City::all();
        $specialties = Specialty::all();

        // Build the base query with relationships
        $query = Clinic::with(['city', 'specialty']);

        // Apply city filter
        if ($request->has('city') && is_array($request->input('city'))) {
            $query->whereIn('city_id', $request->input('city'));
        }

        // Apply specialty filter
        if ($request->has('specialty') && is_array($request->input('specialty'))) {
            $query->whereIn('specialty_id', $request->input('specialty'));
        }

        // Paginate results
        $clinics = $query->paginate(9);

        // Append filters to pagination links to retain them
        $clinics->appends($request->only(['city', 'specialty']));

        // Prepare filters array for component
        $filters = [
            'city' => $cities,
            'specialty' => $specialties,
        ];

        // Return the view with clinics and filters
        return view('clinics.index', compact('clinics', 'filters'));
    }

}
