<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnlineCourseController extends Controller
{
    public function index()
    {
        return view('online-courses.index'); // اینجا به نمای آنلاین کورس‌ها ارجاع می‌دهیم
    }


}
