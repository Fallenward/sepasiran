<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class PaginationAndListingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_key_frontend_list_endpoints_return_ok(): void
    {
        $this->get(route('frontend.home'))->assertOk();
        $this->get(route('frontend.dog.supply.index'))->assertOk();
        $this->get(route('frontend.coaches.index'))->assertOk();
        $this->get(route('frontend.clinics.index'))->assertOk();
        $this->get(route('frontend.breeders.index'))->assertOk();
        $this->get(route('frontend.stores.index'))->assertOk();
        $this->get(route('frontend.online.courses'))->assertOk();
    }

    public function test_admin_users_index_uses_paginator(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        // Blade may reference legacy route names; assert controller data regardless.
        $response->assertViewHas('users');
        $users = $response->viewData('users');
        $this->assertInstanceOf(LengthAwarePaginator::class, $users);
    }

    public function test_store_products_index_uses_paginator(): void
    {
        $owner = User::factory()->create();
        Product::factory()->count(2)->create([
            'user_id' => $owner->id,
            'created_by' => $owner->id,
            'updated_by' => $owner->id,
        ]);

        $response = $this->get(route('frontend.stores.index'));
        $response->assertOk();

        $products = $response->viewData('products');
        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
    }

    public function test_dog_controller_index_paginates(): void
    {
        $controller = app(\App\Http\Controllers\DogController::class);
        $view = $controller->index(request());

        $this->assertInstanceOf(LengthAwarePaginator::class, $view->getData()['dogs']);
    }
}