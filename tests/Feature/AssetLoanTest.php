<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\Category;
use App\Models\Location;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetLoanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'staff']);
        
        $this->category = Category::create(['category_name' => 'Electronic']);
        $this->location = Location::create(['location_name' => 'Office A']);
        
        $this->asset = Asset::create([
            'asset_code' => 'AST-001',
            'asset_name' => 'Test Laptop',
            'category_id' => $this->category->id,
            'location_id' => $this->location->id,
            'acquisition_cost' => 15000000,
            'acquisition_date' => now(),
            'condition' => 'Baik Sekali',
            'status' => 'active'
        ]);
    }

    public function test_user_can_request_loan()
    {
        // Give permission manually or via seeder if needed
        // Assuming Admin role has all permissions from hasPermission() logic
        
        $response = $this->actingAs($this->admin)->post('/loans', [
            'asset_id' => $this->asset->id,
            'notes' => 'Need for presentation'
        ]);

        $response->assertRedirect('/loans');
        $this->assertDatabaseHas('asset_loans', [
            'asset_id' => $this->asset->id,
            'status' => 'pending'
        ]);
    }

    public function test_admin_can_approve_loan()
    {
        $loan = AssetLoan::create([
            'asset_id' => $this->asset->id,
            'user_id' => $this->user->id,
            'loan_date' => now(),
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)->post("/loans/{$loan->id}/approve");

        $response->assertStatus(302);
        $this->assertEquals('borrowed', $loan->fresh()->status);
        $this->assertEquals($this->user->id, $this->asset->fresh()->user_id);
    }
}
