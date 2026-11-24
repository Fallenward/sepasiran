<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $tickets = collect();
        $unread = 0;
        $openCount = 0;

        if ($user) {
            $tickets = SupportTicket::query()
                ->where('user_id', $user->id)
                ->latest('last_message_at')
                ->latest('id')
                ->limit(12)
                ->get();

            $unread = (int) SupportTicket::query()
                ->where('user_id', $user->id)
                ->sum('unread_for_user');

            $openCount = (int) SupportTicket::query()
                ->where('user_id', $user->id)
                ->whereIn('status', ['open', 'answered'])
                ->count();
        }

        return view('support.index', [
            'title' => 'پشتیبانی',
            'isAuth' => (bool) $user,
            'loginUrl' => route('login', ['redirect' => route('frontend.support.index')]),
            'tickets' => $tickets,
            'unread' => $unread,
            'openCount' => $openCount,
            'statusLabels' => [
                'open' => 'باز',
                'answered' => 'پاسخ داده شد',
                'closed' => 'بسته',
            ],
            'topics' => [
                [
                    'icon' => 'steps',
                    'title' => 'رزرو و جلسات',
                    'hint' => 'لغو، زمان‌بندی، پیشنهاد مربی',
                    'subject' => 'مشکل رزرو / جلسه',
                    'body' => 'سلام، درباره رزرو یا جلسه سؤال دارم:',
                ],
                [
                    'icon' => 'store',
                    'title' => 'پرداخت و کیف پول',
                    'hint' => 'تراکنش، برگشت وجه، شارژ',
                    'subject' => 'پرداخت / کیف پول',
                    'body' => 'سلام، درباره پرداخت یا کیف پول سؤال دارم:',
                ],
                [
                    'icon' => 'course',
                    'title' => 'دوره و ثبت‌نام',
                    'hint' => 'دسترسی درس، گواهی، خرید',
                    'subject' => 'دوره آنلاین',
                    'body' => 'سلام، درباره دوره سؤال دارم:',
                ],
                [
                    'icon' => 'insurance',
                    'title' => 'بیمه حیوان',
                    'hint' => 'فعال‌سازی، تمدید، پوشش',
                    'subject' => 'بیمه',
                    'body' => 'سلام، درباره بیمه سؤال دارم:',
                ],
                [
                    'icon' => 'dogs',
                    'title' => 'آگهی سگ و سفارش',
                    'hint' => 'آگهی، خرید، وضعیت سفارش',
                    'subject' => 'آگهی / سفارش سگ',
                    'body' => 'سلام، درباره آگهی یا سفارش سؤال دارم:',
                ],
                [
                    'icon' => 'trust',
                    'title' => 'حساب و امنیت',
                    'hint' => 'ورود، پروفایل، دسترسی نقش',
                    'subject' => 'حساب کاربری',
                    'body' => 'سلام، درباره حساب کاربری سؤال دارم:',
                ],
            ],
        ]);
    }
}