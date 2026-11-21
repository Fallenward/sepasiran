<?php

namespace Tests\Feature;

use App\Models\Breed;
use App\Models\Dog;
use App\Models\Insurance;
use App\Models\InsurancePlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InsurancePurchaseLicenseTest extends TestCase
{
    use RefreshDatabase;

    private function makeInsurerWithPlan(): array
    {
        $insurance = Insurance::query()->forceCreate([
            'name' => 'Sepas Pet Cover',
            'slug' => 'sepas-pet-'.Str::lower(Str::random(4)),
            'description' => 'Demo insurance',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $plan = InsurancePlan::query()->create([
            'insurance_id' => $insurance->id,
            'name' => '3-month',
            'duration_months' => 3,
            'price' => 500_000,
            'coverage_summary' => 'Demo cover',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return [$insurance, $plan];
    }

    public function test_purchase_creates_timed_license_on_dashboard(): void
    {
        [$insurance, $plan] = $this->makeInsurerWithPlan();
        $customer = User::factory()->create(['wallet' => 2_000_000]);
        $breed = Breed::query()->create(['name' => 'Poodle', 'description' => 'x']);
        $dog = Dog::query()->create([
            'user_id' => $customer->id,
            'title' => 'Luna',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 2,
        ]);

        $this->actingAs($customer)
            ->post(route('frontend.clinic.insurance.purchase', $insurance->id), [
                'plan_id' => $plan->id,
                'dog_id' => $dog->id,
                'payment_method' => 'wallet',
            ])
            ->assertRedirect(route('account.insurance-licenses.index'));

        $customer->refresh();
        $this->assertSame(1_500_000, (int) $customer->wallet);

        $this->assertDatabaseHas('insurance_licenses', [
            'user_id' => $customer->id,
            'insurance_id' => $insurance->id,
            'insurance_plan_id' => $plan->id,
            'dog_id' => $dog->id,
            'status' => 'active',
            'amount_paid' => 500_000,
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'title' => 'مجوز بیمه صادر شد',
        ]);

        $this->actingAs($customer)
            ->get(route('account.insurance-licenses.index'))
            ->assertOk()
            ->assertSee('Luna', false);

        $this->actingAs($customer)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('مجوزهای بیمه', false);
    }

    public function test_show_page_lists_plans(): void
    {
        [$insurance] = $this->makeInsurerWithPlan();

        $this->get(route('frontend.clinic.insurance.show', $insurance->id))
            ->assertOk()
            ->assertSee('طرح‌ها و مدت پوشش', false);
    }
}
