<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * OTP/SMS entry points — rate-limited via throttle:otp on routes.
 * Codes live in cache (works for database/file/redis CACHE_STORE).
 */
class OtpController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:15'],
        ]);

        $phone = $validated['phone'];
        $code = (string) random_int(100000, 999999);

        Cache::put($this->cacheKey($phone), Hash::make($code), now()->addMinutes(5));

        Log::info('OTP generated for phone', [
            'phone' => Str::mask($phone, '*', 3),
        ]);

        $payload = ['message' => 'کد تایید ارسال شد.'];

        if (app()->environment(['local', 'testing'])) {
            $payload['debug_code'] = $code;
        }

        return response()->json($payload);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'code' => ['required', 'string'],
        ]);

        $hashed = Cache::get($this->cacheKey($validated['phone']));

        if (! $hashed || ! Hash::check($validated['code'], $hashed)) {
            return response()->json(['message' => 'کد نامعتبر یا منقضی است.'], 422);
        }

        Cache::forget($this->cacheKey($validated['phone']));

        return response()->json(['message' => 'تایید شد.']);
    }

    private function cacheKey(string $phone): string
    {
        return 'otp:code:'.$phone;
    }
}
