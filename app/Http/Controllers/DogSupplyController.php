<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use Illuminate\Http\Request;

class DogSupplyController extends Controller
{
    public function index(Request $request)
    {
        $query = Dog::query()->with([
            'owner:id,name',
            'city:id,name',
            'breed:id,name',
        ]);

        if ($request->filled('q')) {
            $term = trim((string) $request->q);
            $query->where(function ($builder) use ($term) {
                $like = '%' . $term . '%';
                $builder->where('title', 'like', $like)
                    ->orWhere('color', 'like', $like)
                    ->orWhere('breed', 'like', $like)
                    ->orWhereHas('breed', fn ($q) => $q->where('name', 'like', $like))
                    ->orWhereHas('city', fn ($q) => $q->where('name', 'like', $like));
            });
        }

        if ($request->filled('city')) {
            $query->whereIn('city_id', (array) $request->city);
        }

        if ($request->filled('breed')) {
            $query->whereIn('breed_id', (array) $request->breed);
        }

        if ($request->filled('size')) {
            $query->whereIn('size', (array) $request->size);
        }

        $dogs = $query->latest('id')->paginate(9)->appends($request->query());

        $filters = [
            'city' => City::query()->select('id', 'name')->orderBy('name')->get(),
            'breed' => Breed::query()->select('id', 'name')->orderBy('name')->get(),
            'size' => [
                ['id' => 'small', 'name' => 'کوچک'],
                ['id' => 'medium', 'name' => 'متوسط'],
                ['id' => 'large', 'name' => 'بزرگ'],
            ],
        ];

        if ($request->ajax()) {
            $html = '';
            foreach ($dogs as $dog) {
                $html .= '<div class="sp-listing-card">' . view('components.dog-card', ['item' => $dog])->render() . '</div>';
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $dogs->hasMorePages(),
                'nextPage' => $dogs->currentPage() + 1,
                'currentPage' => $dogs->currentPage(),
                'lastPage' => $dogs->lastPage(),
                'total' => $dogs->total(),
                'from' => $dogs->firstItem(),
                'to' => $dogs->lastItem(),
            ]);
        }

        return view('dog-supply.index', compact('dogs', 'filters'));
    }

    public function suggest(Request $request)
    {
        $type = $request->string('type')->toString();
        $term = trim((string) $request->get('q', ''));
        $limit = 8;

        if ($type === 'city') {
            $rows = City::query()
                ->when($term !== '', fn ($q) => $q->where('name', 'like', '%' . $term . '%'))
                ->orderBy('name')
                ->limit($limit)
                ->get(['id', 'name']);
        } elseif ($type === 'breed') {
            $rows = Breed::query()
                ->when($term !== '', fn ($q) => $q->where('name', 'like', '%' . $term . '%'))
                ->orderBy('name')
                ->limit($limit)
                ->get(['id', 'name']);
        } elseif ($type === 'dog') {
            $rows = Dog::query()
                ->with(['breed:id,name', 'city:id,name'])
                ->when($term !== '', function ($q) use ($term) {
                    $like = '%' . $term . '%';
                    $q->where(function ($builder) use ($like) {
                        $builder->where('title', 'like', $like)
                            ->orWhere('color', 'like', $like)
                            ->orWhere('breed', 'like', $like)
                            ->orWhereHas('breed', fn ($b) => $b->where('name', 'like', $like))
                            ->orWhereHas('city', fn ($c) => $c->where('name', 'like', $like));
                    });
                })
                ->latest('id')
                ->limit($limit)
                ->get()
                ->map(fn (Dog $dog) => [
                    'id' => $dog->id,
                    'name' => $dog->title,
                    'meta' => trim(($dog->breed->name ?? $dog->breed ?? '') . ' · ' . ($dog->city->name ?? '')),
                ]);

            return response()->json(['results' => $rows]);
        } else {
            return response()->json(['results' => []]);
        }

        return response()->json([
            'results' => $rows->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
            ])->values(),
        ]);
    }

    public function show($id)
    {
        $dog = Dog::with([
            'owner:id,name,family,phone,bio,profile_picture,photo,city_id,is_verified',
            'owner.city:id,name',
            'owner.speciality',
            'city:id,name',
            'breed:id,name',
            'fatherBreed:id,name',
            'motherBreed:id,name',
            'maternalGrandmotherBreed:id,name',
            'maternalGrandfatherBreed:id,name',
            'paternalGrandmotherBreed:id,name',
            'paternalGrandfatherBreed:id,name',
            'media',
        ])->findOrFail($id);

        $owner = $dog->owner;
        $ownerProfile = $owner
            ? \App\Support\ProviderPublicProfile::summarize($owner)
            : null;

        $pendingOrder = null;
        if (auth()->check()) {
            $pendingOrder = \App\Models\DogOrder::query()
                ->where('dog_id', $dog->id)
                ->where('buyer_id', auth()->id())
                ->where('status', \App\Models\DogOrder::STATUS_PENDING)
                ->latest('id')
                ->first();
        }

        $forSale = $dog->isForSale() || ($pendingOrder && $dog->status === 'waiting');
        $isOwner = auth()->check() && (int) $dog->user_id === (int) auth()->id();

        return view('dogOrder.index', [
            'dog' => $dog,
            'owner' => $owner,
            'ownerProfile' => $ownerProfile,
            'pendingOrder' => $pendingOrder,
            'forSale' => $forSale,
            'isOwner' => $isOwner,
        ]);
    }
}