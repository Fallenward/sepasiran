<?php

namespace App\Support;

use App\Models\Menu;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Tag-safe cache helpers. Redis/Memcached use tags; database/file/array forget keys.
 */
class AppCache
{
    public const SETTINGS_KEY = 'app.settings.first';

    public const MENUS_KEY = 'app.menus.roots';

    public const HOME_KEY = 'app.home.payload';

    public const TAG_SETTINGS = 'settings';

    public const TAG_MENUS = 'menus';

    public const TAG_HOME = 'home';

    public static function supportsTags(): bool
    {
        return in_array(config('cache.default'), ['redis', 'memcached'], true);
    }

    public static function remember(string $key, int $ttl, callable $callback, array $tags = []): mixed
    {
        if ($tags !== [] && self::supportsTags()) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    public static function forgetKeys(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    public static function flushTags(array $tags, array $fallbackKeys = []): void
    {
        if ($tags !== [] && self::supportsTags()) {
            Cache::tags($tags)->flush();

            return;
        }

        self::forgetKeys($fallbackKeys);
    }

    public static function settings(): ?Setting
    {
        return self::remember(self::SETTINGS_KEY, 3600, fn () => Setting::query()->first(), [self::TAG_SETTINGS]);
    }

    public static function forgetSettings(): void
    {
        self::flushTags([self::TAG_SETTINGS], [self::SETTINGS_KEY]);
    }

    public static function menus()
    {
        return self::remember(
            self::MENUS_KEY,
            3600,
            fn () => Menu::query()->with('children')->whereNull('parent_id')->orderBy('id')->get(),
            [self::TAG_MENUS]
        );
    }

    public static function forgetMenus(): void
    {
        self::flushTags([self::TAG_MENUS], [self::MENUS_KEY]);
    }

    public static function forgetHome(): void
    {
        self::flushTags([self::TAG_HOME], [self::HOME_KEY]);
    }

    public static function userHeaderKey(int $userId): string
    {
        return 'app.user.header.'.$userId;
    }

    /**
     * Cached shell payload for the site header: avatar, display name, unread + recent notifications.
     *
     * @return array{display_name: string, avatar_url: ?string, unread: int, notifications: list<array{id:int,title:string,message:string,read_at:?string,url:string}>}
     */
    public static function userHeader(User $user): array
    {
        return self::remember(self::userHeaderKey((int) $user->id), 300, function () use ($user) {
            $fresh = User::query()
                ->select(['id', 'name', 'family', 'profile_picture', 'photo', 'updated_at'])
                ->find($user->id) ?? $user;

            $notes = Notification::query()
                ->where('user_id', $fresh->id)
                ->latest()
                ->limit(6)
                ->get(['id', 'title', 'message', 'read_at', 'created_at']);

            $unread = Notification::query()
                ->where('user_id', $fresh->id)
                ->whereNull('read_at')
                ->count();

            return [
                'display_name' => trim(($fresh->name ?? '').' '.($fresh->family ?? '')) ?: 'کاربر',
                'avatar_url' => $fresh->avatarUrl(),
                'unread' => $unread,
                'notifications' => $notes->map(static function (Notification $note) {
                    return [
                        'id' => (int) $note->id,
                        'title' => (string) ($note->title ?: 'اعلان'),
                        'message' => Str::limit((string) $note->message, 72),
                        'read_at' => optional($note->read_at)?->toIso8601String(),
                        'url' => route('account.notifications.index', ['focus' => $note->id]).'#notification-'.$note->id,
                    ];
                })->all(),
            ];
        });
    }

    public static function forgetUserHeader(int $userId): void
    {
        self::forgetKeys([self::userHeaderKey($userId)]);
    }
}
