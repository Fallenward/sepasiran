<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $title='سپاس ایران';
        $sliders = Slider::all();

        return view('frontend.pages.home',compact('sliders','title'));
    }
}
