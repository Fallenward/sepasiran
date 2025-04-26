<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
class DashboardController extends Controller
{
    /**
     * نمایش داشبورد براساس نقش کاربر
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->is_admin) {
            // اگر کاربر ادمین بود
            $title = 'داشبورد مدیریت';
            $userCount = User::count();

            return view('admin.dashboard.index', compact('title', 'userCount'));
        } else {
            // اگر کاربر ادمین نبود، هدایت به صفحه لاگین
            return redirect()->route('login')->with('error', 'دسترسی غیرمجاز. لطفاً وارد شوید.');
        }
    }
}
