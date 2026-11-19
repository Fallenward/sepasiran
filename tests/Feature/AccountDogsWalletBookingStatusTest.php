<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Dog;
use App\Models\DogColor;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BreedSeeder;
use Database\Seeders\DogAttributeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountDogsWalletBookingStatusTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $roleName): User
    {
        Role::query()->firstOrCreate(['name' => $roleName]);
        if ($roleName !== 'user') {
            Role::query()->firstOrCreate(['name' => 'user']);
        }
        $user = User::factory()->create(['wallet' => 0]);
        $user->roles()->attach(Role::query()->where('name', $roleName)->first()->id);

        return $user->fresh();
    }

    public function test_single_dashboard_link_in_header(): void
    {
        $user = $this->userWithRole('coach');

        $html = $this->actingAs($user)
            ->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('داشبورد', false)
            ->getContent();

        $this->assertStringNotContainsString('داشبورد من', $html);
        $this->assertStringNotContainsString('داشبورد مربی', $html);
        $this->assertStringNotContainsString('داشبورد کلینیک', $html);
        $this->assertStringNotContainsString('داشبورد پرورش‌دهنده', $html);
    }

    public function test_wallet_deposit_increases_balance(): void
    {
        $user = $this->userWithRole('user');

        $this->actingAs($user)
            ->get(route('wallet.index'))
            ->assertOk()
            ->assertSee('افزایش موجودی', false);

        $this->actingAs($user)
            ->post(route('wallet.deposit'), ['amount' => 150000])
            ->assertRedirect(route('wallet.index'));

        $this->assertSame(150000, (int) $user->fresh()->wallet);
    }

    public function test_dog_crud_uses_db_attributes_and_media(): void
    {
        Storage::fake('public');
        $this->seed(BreedSeeder::class);
        $this->seed(DogAttributeSeeder::class);
        $user = $this->userWithRole('user');
        $color = DogColor::query()->first();
        $this->assertNotNull($color);

        $this->actingAs($user)
            ->get(route('account.dogs.create'))
            ->assertOk()
            ->assertSee('تاریخ تولد', false)
            ->assertSee('شجره‌نامه دارد', false)
            ->assertSee($color->name, false);

        $this->actingAs($user)
            ->post(route('account.dogs.store'), [
                'title' => 'راکی',
                'color' => $color->name,
                'eye_color' => 'قهوه‌ای',
                'cover_type' => 'کوتاه',
                'birth_year' => 1400,
                'birth_month' => 1,
                'birth_day' => 15,
                'shajare' => 1,
                'images' => [UploadedFile::fake()->image('dog.jpg')],
            ])
            ->assertRedirect(route('account.dogs.index'));

        $dog = Dog::query()->where('title', 'راکی')->first();
        $this->assertNotNull($dog);
        $this->assertSame($color->name, $dog->color);
        $this->assertTrue((bool) $dog->shajare);
        $this->assertNotNull($dog->birth_date);
        $this->assertGreaterThan(0, $dog->media()->count());

        $this->actingAs($user)
            ->get(route('account.dogs.edit', $dog))
            ->assertOk()
            ->assertSee('ویرایش سگ', false);

        $this->actingAs($user)
            ->put(route('account.dogs.update', $dog), [
                'title' => 'راکی ۲',
                'color' => $color->name,
                'birth_year' => 1400,
                'birth_month' => 1,
                'birth_day' => 15,
            ])
            ->assertRedirect(route('account.dogs.index'));

        $this->assertSame('راکی ۲', $dog->fresh()->title);

        $this->actingAs($user)
            ->delete(route('account.dogs.destroy', $dog))
            ->assertRedirect(route('account.dogs.index'));

        $this->assertNull(Dog::query()->find($dog->id));
    }

    public function test_booking_hub_only_allows_accept_or_reject(): void
    {
        $customer = $this->userWithRole('user');
        $coach = $this->userWithRole('coach');

        $booking = Booking::query()->create([
            'user_id' => $customer->id,
            'provider_id' => $coach->id,
            'booking_code' => 'BK-TEST-1',
            'service_type' => 'coach',
            'fulfillment_mode' => 'direct',
            'status' => 'PENDING',
            'price' => 100000,
            'total' => 100000,
        ]);

        $this->actingAs($coach)
            ->get(route('account.bookings.index', ['tab' => 'inbox']))
            ->assertOk()
            ->assertSee('پذیرش', false)
            ->assertSee('رد', false)
            ->assertDontSee('<select', false)
            ->assertDontSee('>باز</option>', false)
            ->assertDontSee('>پیشنهاد شده</option>', false);

        $this->actingAs($coach)
            ->patch(route('account.bookings.status', $booking), [
                'status' => 'OPEN',
                'tab' => 'inbox',
            ])
            ->assertSessionHasErrors('status');

        $this->actingAs($coach)
            ->patch(route('account.bookings.status', $booking), [
                'status' => 'ACCEPTED',
                'tab' => 'inbox',
            ])
            ->assertRedirect(route('account.bookings.index', ['tab' => 'inbox']));

        $this->assertSame('ACCEPTED', $booking->fresh()->status);

        $this->actingAs($coach)
            ->patch(route('account.bookings.status', $booking), [
                'status' => 'CANCELLED',
                'tab' => 'inbox',
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame('ACCEPTED', $booking->fresh()->status);
    }
}