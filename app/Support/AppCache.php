<?php

namespace App\Support;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

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
}
