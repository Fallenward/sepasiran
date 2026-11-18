<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Dog;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Support\AppCache;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'سپاسیران';

        $payload = AppCache::remember(AppCache::HOME_KEY, 600, function () {
            $sliders = Slider::query()->latest()->get();

            $services = Service::query()->get()->map(function ($service) {
                $service->route_link = match ($service->slug) {
                    'antkhab-mrby' => route('frontend.coaches.index'),
                    'thyh-sg' => route('frontend.dog.supply.index'),
                    'dorh-hay-anlayn' => route('frontend.online.courses'),
                    'froshgah-ha' => route('frontend.stores.index'),
                    'klynyk-ha-o-bymh-hyoanat' => route('frontend.clinics.index'),
                    'prorsh-dhndh' => route('frontend.breeders.index'),
                    default => route('frontend.services.index'),
                };

                return $service;
            });

            $featuredDogs = Dog::query()
                ->with(['breed', 'city'])
                ->latest()
                ->take(6)
                ->get();

            $featuredProducts = Product::query()
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get();

            $blogs = Blog::query()
                ->where('status', 'published')
                ->orderByDesc('created_at')
                ->take(3)
                ->get();

            if ($blogs->isEmpty()) {
                $blogs = Blog::query()->orderByDesc('created_at')->take(3)->get();
            }

            $coachCount = User::whereHas('roles', fn ($q) => $q->where('name', 'coach'))->count();
            $breederCount = User::whereHas('roles', fn ($q) => $q->where('name', 'breeder'))->count();
            $clinicCount = User::whereHas('roles', fn ($q) => $q->where('name', 'clinic'))->count();
            $dogCount = Dog::count();

            $statistics = [
                ['title' => 'مربی', 'counter' => $coachCount],
                ['title' => 'پرورش‌دهنده', 'counter' => $breederCount],
                ['title' => 'کلینیک', 'counter' => $clinicCount],
                ['title' => 'سگ', 'counter' => $dogCount],
            ];

            $settings = Setting::query()->first();

            $servicePillars = [
                [
                    'title' => 'مربی',
                    'text' => 'آموزش و همراهی تخصصی',
                    'href' => route('frontend.coaches.index'),
                ],
                [
                    'title' => 'کلینیک',
                    'text' => 'مراقبت و درمان',
                    'href' => route('frontend.clinics.index'),
                ],
                [
                    'title' => 'بیمه',
                    'text' => 'پوشش مطمئن حیوان خانگی',
                    'href' => route('frontend.clinic.insurance.index'),
                ],
                [
                    'title' => 'دوره',
                    'text' => 'آموزش آنلاین و مهارتی',
                    'href' => route('frontend.online.courses'),
                ],
            ];

            return compact(
                'sliders',
                'services',
                'blogs',
                'statistics',
                'featuredDogs',
                'featuredProducts',
                'settings',
                'servicePillars'
            );
        }, [AppCache::TAG_HOME]);

        return view('frontend.pages.home', array_merge($payload, compact('title')));
    }
}