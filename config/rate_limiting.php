<?php

/**
 * Named rate limiters registered in AppServiceProvider.
 * Applied via Route middleware: throttle:login, throttle:otp, etc.
 */
return [

    'login' => [
        'max_attempts' => (int) env('RATE_LIMIT_LOGIN', 5),
        'decay_seconds' => (int) env('RATE_LIMIT_LOGIN_DECAY', 60),
    ],

    'otp' => [
        'max_attempts' => (int) env('RATE_LIMIT_OTP', 3),
        'decay_seconds' => (int) env('RATE_LIMIT_OTP_DECAY', 60),
    ],

    'payment' => [
        'max_attempts' => (int) env('RATE_LIMIT_PAYMENT', 10),
        'decay_seconds' => (int) env('RATE_LIMIT_PAYMENT_DECAY', 60),
    ],

    'admin_mutations' => [
        'max_attempts' => (int) env('RATE_LIMIT_ADMIN_MUTATIONS', 30),
        'decay_seconds' => (int) env('RATE_LIMIT_ADMIN_MUTATIONS_DECAY', 60),
    ],

];
