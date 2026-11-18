<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Dog;
use App\Models\Service;
use App\Models\Slider;
use App\Models\User;
use App\Support\AppCache;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'سپاس ایران';

        $payload = AppCache::remember(AppCache::HOME_KEY, 600, function () {
            $sliders = Slider::all();

            $services = Service::all()->map(function ($service) {
                switch ($service->slug) {
                    case 'antkhab-mrby':
                        $service->route_link = route('frontend.coaches.index');
                        break;
                    case 'thyh-sg':
                        $service->route_link = route('frontend.dog.supply.index');
                        break;
                    case 'dorh-hay-anlayn':
                        $service->route_link = route('frontend.online.courses');
                        break;
                    case 'froshgah-ha':
                        $service->route_link = route('frontend.stores.index');
                        break;
                    case 'klynyk-ha-o-bymh-hyoanat':
                        $service->route_link = route('frontend.clinics.index');
                        break;
                    case 'prorsh-dhndh':
                        $service->route_link = route('frontend.breeders.index');
                        break;
                    default:
                        $service->route_link = route('frontend.coaches.index');
                        break;
                }

                return $service;
            });

            $blogs = Blog::orderBy('created_at', 'desc')->take(3)->get();

            $coachCount = User::whereHas('roles', fn ($q) => $q->where('name', 'coach'))->count();
            $breederCount = User::whereHas('roles', fn ($q) => $q->where('name', 'breeder'))->count();
            $clinicCount = User::whereHas('roles', fn ($q) => $q->where('name', 'clinic'))->count();
            $dogCount = Dog::count();

            $statistics = [
                ['title' => 'تعداد مربی', 'counter' => $coachCount],
                ['title' => 'پرورش دهندگان', 'counter' => $breederCount],
                ['title' => 'کلینیک ها', 'counter' => $clinicCount],
                ['title' => 'سگ ها', 'counter' => $dogCount],
            ];

            $bestCoach = User::whereHas('roles', fn ($q) => $q->where('name', 'coach'))->latest()->first();
            $bestBreeder = User::whereHas('roles', fn ($q) => $q->where('name', 'breeder'))->latest()->first();
            $bestClinic = User::whereHas('roles', fn ($q) => $q->where('name', 'clinic'))->latest()->first();

            $bests = collect([$bestCoach, $bestBreeder, $bestClinic])->filter()->values();

            return compact('sliders', 'services', 'blogs', 'statistics', 'bests');
        }, [AppCache::TAG_HOME]);

        return view('frontend.pages.home', array_merge($payload, compact('title')));
    }
}
