<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()->latest('id');

        if ($request->filled('q')) {
            $term = '%' . trim((string) $request->q) . '%';
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('slug', 'like', $term);
            });
        }

        $services = $query->paginate(9)->appends($request->query());

        $services->getCollection()->transform(function (Service $service) {
            $service->route_link = match ($service->slug) {
                'antkhab-mrby' => route('frontend.coaches.index'),
                'thyh-sg' => route('frontend.dog.supply.index'),
                'dorh-hay-anlayn' => route('frontend.online.courses'),
                'froshgah-ha' => route('frontend.stores.index'),
                'klynyk-ha-o-bymh-hyoanat' => route('frontend.clinics.index'),
                'prorsh-dhndh' => route('frontend.breeders.index'),
                default => route('frontend.services.index'),
            };

            if ($service->image && !str_starts_with($service->image, 'http')) {
                $service->image_url = asset(ltrim($service->image, '/'));
            } else {
                $service->image_url = $service->image ?: asset('images/demo/hero/hero-02.jpg');
            }

            return $service;
        });

        $filters = [];

        return view('frontend.pages.services.index', compact('services', 'filters'));
    }
}