<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    // Show the Dashboard Page
    
    // Show the Proposals Page
    public function proposals()
    {
        return view('frontend.pages.proposals');
    }

    // Show the Select Time Page
    public function selectTime()
    {
        return view('frontend.pages.select-time');
    }

    // Show the Select Breed Page
    public function selectBreed()
    {
        return view('frontend.pages.select-breed');
    }

    // Show the Select Trainer Type Page
    public function selectTrainerType()
    {
        return view('frontend.pages.select-trainer-type');
    }

    // Show the Summary Page
    public function summary()
    {
        return view('frontend.pages.summary');
    }

        public function myOrder()
    {
        // You can pass real data here if needed
        return view('frontend.pages.my-order');
    }
}
