<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoachSelectionController extends Controller
{
    public function index()
    {
        // اطلاعات مورد نیاز را از مدل‌ها یا دیتابیس می‌توانید بارگذاری کنید
        // مثلاً اگر از مدل Coach استفاده می‌کنید:
        // $coaches = Coach::all();

        return view('coach-selection.index');
    }
}
