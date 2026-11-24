<?php

namespace Database\Seeders;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use App\Support\SupportTicketService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupportTicketDemoSeeder extends Seeder
{
    public const PASSWORD = 'password123';

    public function run(): void
    {
        $admin = User::query()->where('is_admin', true)->first()
            ?? User::query()->updateOrCreate(
                ['email' => 'admin@sepasiran.test'],
                [
                    'name' => 'Admin',
                    'family' => 'Sepasiran',
                    'password' => Hash::make(self::PASSWORD),
                    'is_admin' => true,
                    'phone' => '09120000001',
                ]
            );

        if (! $admin->is_admin) {
            $admin->forceFill(['is_admin' => true])->save();
        }

        $customer = User::query()->updateOrCreate(
            ['email' => 'support.customer@sepasiran.test'],
            [
                'name' => 'Support',
                'family' => 'Customer',
                'password' => Hash::make(self::PASSWORD),
                'is_admin' => false,
                'phone' => '09121112233',
                'wallet' => 1_000_000,
            ]
        );

        $customer2 = User::query()->updateOrCreate(
            ['email' => 'support.customer2@sepasiran.test'],
            [
                'name' => 'Second',
                'family' => 'Customer',
                'password' => Hash::make(self::PASSWORD),
                'is_admin' => false,
                'phone' => '09123334455',
            ]
        );

        // Wipe prior demo tickets for these users so re-seed is idempotent.
        SupportTicket::query()
            ->whereIn('user_id', [$customer->id, $customer2->id])
            ->where('code', 'like', 'TK-DEMO%')
            ->each(function (SupportTicket $ticket) {
                $ticket->messages()->delete();
                $ticket->delete();
            });

        $svc = app(SupportTicketService::class);

        $open = $svc->createTicket(
            $customer,
            'مشکل در رزرو مربی',
            "سلام، رزرو مربی برای فردا ثبت شد ولی وضعیت هنوز PENDING است.\nلطفاً بررسی کنید."
        );
        $open->forceFill(['code' => 'TK-DEMOOPEN1'])->save();

        $answered = $svc->createTicket(
            $customer,
            'کیف پول شارژ نشد',
            'پرداخت انجام شد اما موجودی کیف پول تغییر نکرد.'
        );
        $answered->forceFill(['code' => 'TK-DEMOANS01'])->save();
        $svc->reply($answered, $admin, 'admin', 'درخواست شما را دیدیم؛ تراکنش تا یک ساعت دیگر اعمال می‌شود.');
        $svc->reply($answered, $customer, 'user', 'متشکرم، منتظر می‌مانم.');

        $closedStyle = $svc->createTicket(
            $customer2,
            'سوال درباره بیمه',
            'آیا پوشش بیمه برای توله هم فعال می‌شود؟'
        );
        $closedStyle->forceFill(['code' => 'TK-DEMOCL001'])->save();
        $svc->reply($closedStyle, $admin, 'admin', 'بله، هنگام خرید می‌توانید سگ را انتخاب کنید.');
        $closedStyle->forceFill(['status' => 'closed', 'unread_for_admin' => 0, 'unread_for_user' => 0])->save();

        // Extra waiting ticket for admin alarm badge.
        $waiting = SupportTicket::query()->create([
            'user_id' => $customer2->id,
            'code' => 'TK-DEMOWAIT1',
            'subject' => 'آپلود تصویر پروفایل خطا می‌دهد',
            'status' => 'open',
            'unread_for_admin' => 1,
            'unread_for_user' => 0,
            'last_message_at' => now(config('app.timezone', 'Asia/Tehran')),
        ]);
        SupportTicketMessage::query()->create([
            'support_ticket_id' => $waiting->id,
            'user_id' => $customer2->id,
            'sender_type' => 'user',
            'body' => 'فرمت PNG هم قبول نمی‌شود. لطفاً راهنمایی کنید.',
        ]);

        $this->command?->info('SupportTicketDemoSeeder ready.');
        $this->command?->info('Customer: support.customer@sepasiran.test / '.self::PASSWORD);
        $this->command?->info('Customer2: support.customer2@sepasiran.test / '.self::PASSWORD);
        $this->command?->info('Admin: '.$admin->email.' / '.self::PASSWORD.' (or admin@example.com)');
        $this->command?->info('Tickets: TK-DEMOOPEN1, TK-DEMOANS01, TK-DEMOCL001, TK-DEMOWAIT1');
    }
}