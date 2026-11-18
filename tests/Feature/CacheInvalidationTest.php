<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Setting;
use App\Models\Slider;
use App\Support\AppCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheInvalidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_cache_is_invalidated_on_update(): void
    {
        $setting = Setting::query()->create([
            'color_setting_primary' => '#111111',
            'color_setting_secondary' => '#222222',
            'color_setting_third' => '#333333',
            'color_setting_fourth' => '#444444',
            'comment_settings_type' => 'realtime',
        ]);

        $cached = AppCache::settings();
        $this->assertSame('#111111', $cached->color_setting_primary);
        $this->assertTrue(Cache::has(AppCache::SETTINGS_KEY));

        $setting->update(['color_setting_primary' => '#ABCDEF']);
        AppCache::forgetSettings();

        $fresh = AppCache::settings();
        $this->assertSame('#ABCDEF', $fresh->color_setting_primary);
    }

    public function test_menu_cache_is_invalidated_on_write(): void
    {
        Menu::query()->create(['name' => 'Home', 'link' => '/', 'parent_id' => null]);

        $menus = AppCache::menus();
        $this->assertCount(1, $menus);
        $this->assertTrue(Cache::has(AppCache::MENUS_KEY));

        Menu::query()->create(['name' => 'About', 'link' => '/about', 'parent_id' => null]);
        AppCache::forgetMenus();

        $this->assertCount(2, AppCache::menus());
    }

    public function test_home_cache_is_forgotten_when_slider_changes(): void
    {
        Slider::query()->create([
            'title' => 'One',
            'image' => 'sliders/one.jpg',
            'link' => '/',
        ]);

        $this->get(route('frontend.home'))->assertOk();
        $this->assertTrue(Cache::has(AppCache::HOME_KEY));

        AppCache::forgetHome();
        $this->assertFalse(Cache::has(AppCache::HOME_KEY));
    }
}