<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup initial data
        $this->role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::create(['category_name' => 'Electronic']);
        $this->location = Location::create(['location_name' => 'Office A']);
    }

    public function test_admin_can_view_assets_index()
    {
        $response = $this->actingAs($this->admin)->get('/assets');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_asset()
    {
        $assetData = [
            'asset_code' => 'AST-001',
            'asset_name' => 'Test Laptop',
            'category_id' => $this->category->id,
            'location_id' => $this->location->id,
            'acquisition_cost' => 15000000,
            'acquisition_date' => now()->format('Y-m-d'),
            'condition' => 'Baik Sekali',
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)->post('/assets', $assetData);

        $response->assertRedirect('/assets');
        $this->assertDatabaseHas('assets', ['asset_code' => 'AST-001']);
    }

    public function test_admin_can_permanently_delete_asset()
    {
        $asset = Asset::create([
            'asset_code' => 'DEL-001',
            'asset_name' => 'Delete Me',
            'category_id' => $this->category->id,
            'location_id' => $this->location->id,
            'acquisition_cost' => 1000,
            'acquisition_date' => now(),
            'condition' => 'Baik',
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->admin)->delete("/assets/{$asset->id}");

        $response->assertRedirect('/assets');
        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }
}
