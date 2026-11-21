<?php

namespace Tests\Feature;

use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\DogOrder;
use App\Models\Role;
use App\Models\User;
use App\Support\DogPurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DogPurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['payment.default' => 'local']);
    }

    private function roleUser(string $role, array $attrs = []): User
    {
        Role::query()->firstOrCreate(['name' => $role]);
        if ($role !== 'user') {
            Role::query()->firstOrCreate(['name' => 'user']);
        }
        $user = User::factory()->create(array_merge(['wallet' => 0], $attrs));
        $user->roles()->attach(Role::query()->where('name', $role)->first()->id);

        return $user->fresh();
    }

    private function makeCity(): City
    {
        $owner = User::factory()->create();

        return City::query()->forceCreate([
            'user_id' => $owner->id,
            'name' => 'Tehran',
        ]);
    }

    private function makeDog(User $owner, array $attrs = []): Dog
    {
        $breed = Breed::query()->firstOrCreate(
            ['name' => 'Husky'],
            ['description' => 'x']
        );
        $city = $this->makeCity();

        return Dog::query()->create(array_merge([
            'user_id' => $owner->id,
            'title' => 'Rocky Sale',
            'description' => 'Healthy dog for sale',
            'breed_id' => $breed->id,
            'city_id' => $city->id,
            'status' => 'available',
            'price' => 2_000_000,
            'age' => 8,
            'color' => 'white',
        ], $attrs));
    }

    public function test_dog_show_page_displays_dog_and_owner_details(): void
    {
        $seller = $this->roleUser('breeder', [
            'name' => 'Ali',
            'family' => 'Breeder',
            'bio' => 'Professional breeder bio',
            'is_verified' => true,
        ]);
        $dog = $this->makeDog($seller, ['title' => 'Nala']);

        $this->get(route('frontend.dog-supply.show', $dog->id))
            ->assertOk()
            ->assertSee('Nala', false)
            ->assertSee('Ali Breeder', false)
            ->assertSee('اطلاعات مالک', false)
            ->assertSee('مشخصات سگ', false)
            ->assertSee('برای فروش', false)
            ->assertSee('ورود برای خرید', false);

        $buyer = $this->roleUser('user');
        $this->actingAs($buyer)
            ->get(route('frontend.dog-supply.show', $dog->id))
            ->assertOk()
            ->assertSee('ثبت سفارش خرید', false);
    }

    public function test_buyer_can_create_order_only_when_dog_is_for_sale(): void
    {
        $seller = $this->roleUser('breeder');
        $buyer = $this->roleUser('user', ['wallet' => 5_000_000]);
        $forSale = $this->makeDog($seller, ['status' => 'available', 'price' => 1_500_000]);
        $notForSale = $this->makeDog($seller, [
            'title' => 'Owned Pup',
            'status' => 'owned',
            'price' => 1_500_000,
        ]);

        $this->actingAs($buyer)
            ->post(route('frontend.dog.order.store', $forSale->id))
            ->assertRedirect(route('frontend.dog-supply.show', $forSale->id));

        $this->assertDatabaseHas('dog_orders', [
            'dog_id' => $forSale->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'price' => 1_500_000,
            'status' => DogOrder::STATUS_PENDING,
        ]);
        $this->assertSame('waiting', $forSale->fresh()->status);

        $this->actingAs($buyer)
            ->post(route('frontend.dog.order.store', $notForSale->id))
            ->assertSessionHasErrors('dog');

        $this->assertDatabaseMissing('dog_orders', [
            'dog_id' => $notForSale->id,
            'buyer_id' => $buyer->id,
        ]);
    }

    public function test_payment_completion_transfers_dog_ownership(): void
    {
        $seller = $this->roleUser('breeder', ['wallet' => 100_000]);
        $buyer = $this->roleUser('user', ['wallet' => 3_000_000]);
        $dog = $this->makeDog($seller, ['status' => 'available', 'price' => 2_000_000]);

        $service = app(DogPurchaseService::class);
        $order = $service->createOrder($buyer, $dog);

        $this->assertSame($seller->id, $dog->fresh()->user_id);
        $this->assertSame('waiting', $dog->fresh()->status);

        $this->actingAs($buyer)
            ->post(route('frontend.dog.order.pay', [$dog->id, $order->id]), [
                'payment_method' => 'wallet',
            ])
            ->assertRedirect(route('frontend.dog-supply.show', $dog->id));

        $dog->refresh();
        $order->refresh();
        $buyer->refresh();
        $seller->refresh();

        $this->assertSame(DogOrder::STATUS_PAID, $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertSame($buyer->id, $dog->user_id);
        $this->assertSame('owned', $dog->status);
        $this->assertSame(1_000_000, (int) $buyer->wallet);
        $this->assertSame(2_100_000, (int) $seller->wallet);
    }

    public function test_payment_fails_when_wallet_insufficient_and_owner_unchanged(): void
    {
        $seller = $this->roleUser('breeder', ['wallet' => 0]);
        $buyer = $this->roleUser('user', ['wallet' => 100_000]);
        $dog = $this->makeDog($seller, ['price' => 2_000_000]);

        $order = app(DogPurchaseService::class)->createOrder($buyer, $dog);

        $this->actingAs($buyer)
            ->post(route('frontend.dog.order.pay', [$dog->id, $order->id]), [
                'payment_method' => 'wallet',
            ])
            ->assertSessionHasErrors('wallet');

        $this->assertSame($seller->id, $dog->fresh()->user_id);
        $this->assertSame(DogOrder::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_gateway_payment_callback_transfers_ownership_without_wallet_debit(): void
    {
        $seller = $this->roleUser('breeder', ['wallet' => 50_000]);
        $buyer = $this->roleUser('user', ['wallet' => 500_000]);
        $dog = $this->makeDog($seller, ['price' => 2_000_000]);

        $service = app(DogPurchaseService::class);
        $order = $service->createOrder($buyer, $dog);

        $this->actingAs($buyer)
            ->post(route('frontend.dog.order.pay', [$dog->id, $order->id]), [
                'payment_method' => 'gateway',
            ])
            ->assertOk()
            ->assertSee('پرداخت موفق', false);

        $order->refresh();
        $this->assertSame('gateway', $order->payment_method);
        $this->assertNotEmpty($order->gateway_transaction_id);
        $this->assertSame($seller->id, $dog->fresh()->user_id);

        $this->actingAs($buyer)
            ->get(route('frontend.dog.order.callback', $order->id).'?transactionId='.$order->gateway_transaction_id)
            ->assertRedirect(route('frontend.dog-supply.show', $dog->id));

        $dog->refresh();
        $order->refresh();
        $buyer->refresh();
        $seller->refresh();

        $this->assertSame(DogOrder::STATUS_PAID, $order->status);
        $this->assertSame($buyer->id, $dog->user_id);
        $this->assertSame('owned', $dog->status);
        // Gateway: buyer wallet unchanged, seller still settled.
        $this->assertSame(500_000, (int) $buyer->wallet);
        $this->assertSame(2_050_000, (int) $seller->wallet);
    }

    public function test_pending_order_page_offers_wallet_and_gateway_payment(): void
    {
        $seller = $this->roleUser('breeder');
        $buyer = $this->roleUser('user', ['wallet' => 5_000_000]);
        $dog = $this->makeDog($seller);
        app(DogPurchaseService::class)->createOrder($buyer, $dog);

        $this->actingAs($buyer)
            ->get(route('frontend.dog-supply.show', $dog->id))
            ->assertOk()
            ->assertSee('پرداخت از کیف پول', false)
            ->assertSee('پرداخت از درگاه بانکی', false);
    }
}