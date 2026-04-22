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
        
        // Setup Roles and Permissions
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Staff', 'slug' => 'staff']);
        
        \App\Models\RolePermission::create(['role' => 'admin', 'permission' => 'loan.manage']);
        \App\Models\RolePermission::create(['role' => 'staff', 'permission' => 'loan.view']);
        \App\Models\RolePermission::create(['role' => 'staff', 'permission' => 'loan.create']);
        
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

    public function test_admin_can_request_loan()
    {
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

    public function test_staff_can_request_loan()
    {
        $response = $this->actingAs($this->user)->post('/loans', [
            'asset_id' => $this->asset->id,
            'notes' => 'Staff request'
        ]);

        $response->assertRedirect('/loans');
        $this->assertDatabaseHas('asset_loans', [
            'asset_id' => $this->asset->id,
            'user_id' => $this->user->id,
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

    public function test_staff_cannot_approve_loan()
    {
        $loan = AssetLoan::create([
            'asset_id' => $this->asset->id,
            'user_id' => $this->user->id,
            'loan_date' => now(),
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user)->post("/loans/{$loan->id}/approve");

        $response->assertStatus(403);
        $this->assertEquals('pending', $loan->fresh()->status);
    }

    public function test_staff_can_return_their_own_loan()
    {
        $loan = AssetLoan::create([
            'asset_id' => $this->asset->id,
            'user_id' => $this->user->id,
            'loan_date' => now(),
            'status' => 'borrowed'
        ]);
        $this->asset->update(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->post("/loans/{$loan->id}/return");

        $response->assertStatus(302);
        $this->assertEquals('returned', $loan->fresh()->status);
        $this->assertNull($this->asset->fresh()->user_id);
    }
    public function test_staff_can_cancel_own_pending_loan()
    {
        $loan = AssetLoan::create([
            'asset_id' => $this->asset->id,
            'user_id' => $this->user->id,
            'loan_date' => now(),
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user)->post("/loans/{$loan->id}/cancel");

        $response->assertStatus(302);
        
        $this->assertDatabaseMissing('asset_loans', [
            'id' => $loan->id
        ]);
    }
}
