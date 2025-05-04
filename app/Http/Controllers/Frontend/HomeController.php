<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $title='سپاس ایران';
        $sliders = Slider::all();
        $services = Service::all();
        $blogs = Blog::all();
        $blog = Blog::first();
        $statistics = [
            ['title'=>'تعداد مربی','counter'=>'53'],
            ['title'=>'پرورش دهندگان','counter'=>'53'],
            ['title'=>'کیلینک ها','counter'=>'51'],
            ['title'=>'سگ ها','counter'=>'52'],
        ];
        $bests = User::all();

        return view('frontend.pages.home',compact('sliders','blogs','title','services','statistics','bests','blog'));
    }
}
