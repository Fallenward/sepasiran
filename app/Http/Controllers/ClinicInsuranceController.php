<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Breed;
use App\Models\Insurance;  // assuming you have an Insurance model
use Illuminate\Http\Request;

class ClinicInsuranceController extends Controller
{
    public function index(Request $request)
    {
        // Load filter data
        $cities    = City::orderBy('name')->get();
        $breeds    = Breed::orderBy('name')->get();
        // If you have a durations list, define it here:
        $durations = [
            '6'  => '۶ ماهه',
            '12' => '۱ ساله',
            '24' => '۲ ساله',
        ];

        // Build your insurance query (apply filters if you want)
        $query = Insurance::query();

        if ($request->filled('city')) {
            $query->whereIn('city_id', $request->input('city'));
        }
        // etc...

        $insurances = $query->paginate(6);
        $filters = [
            'city'     => $cities,
            'duration' => collect(array_keys($durations)), // چون durationها به صورت key => value هستند
        ];

        // Pass everything into the view
        return view('clinic-insurance.index', compact(
            'cities',
            'breeds',
            'durations',
            'insurances',
            'filters'
        ));
    }
}
