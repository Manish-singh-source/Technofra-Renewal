<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user for testing
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function user_can_view_vendors_index()
    {
        $vendor = Vendor::factory()->create();
        
        $response = $this->get(route('vendors.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('vendor1');
        $response->assertViewHas('vendors');
        $response->assertSee($vendor->name);
    }

    /** @test */
    public function user_can_view_create_vendor_form()
    {
        $response = $this->get(route('vendors.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('add-vendor');
    }

    /** @test */
    public function user_can_create_vendor_with_valid_data()
    {
        $vendorData = [
            'name' => 'Test Vendor',
            'email' => 'test@vendor.com',
            'phone' => '1234567890',
            'address' => 'Test Address'
        ];

        $response = $this->post(route('vendors.store'), $vendorData);

        $response->assertRedirect(route('vendors.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('vendors', [
            'name' => 'Test Vendor',
            'email' => 'test@vendor.com',
            'phone' => '1234567890'
        ]);
    }

    /** @test */
    public function user_cannot_create_vendor_with_invalid_data()
    {
        $response = $this->post(route('vendors.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'phone' => 'abc123'
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone']);
        $this->assertDatabaseCount('vendors', 0);
    }

    /** @test */
    public function user_can_view_vendor_details()
    {
        $vendor = Vendor::factory()->create();
        
        $response = $this->get(route('vendors.show', $vendor->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('vendor-details');
        $response->assertViewHas('vendor');
        $response->assertSee($vendor->name);
    }

    /** @test */
    public function user_can_view_edit_vendor_form()
    {
        $vendor = Vendor::factory()->create();
        
        $response = $this->get(route('vendors.edit', $vendor->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('add-vendor');
        $response->assertViewHas('vendor');
    }

    /** @test */
    public function user_can_update_vendor()
    {
        $vendor = Vendor::factory()->create();
        
        $updateData = [
            'name' => 'Updated Vendor',
            'email' => 'updated@vendor.com',
            'phone' => '9876543210',
            'address' => 'Updated Address'
        ];

        $response = $this->put(route('vendors.update', $vendor->id), $updateData);

        $response->assertRedirect(route('vendors.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('vendors', [
            'id' => $vendor->id,
            'name' => 'Updated Vendor',
            'email' => 'updated@vendor.com'
        ]);
    }

    /** @test */
    public function user_can_delete_vendor()
    {
        $vendor = Vendor::factory()->create();
        
        $response = $this->delete(route('vendors.destroy', $vendor->id));
        
        $response->assertRedirect(route('vendors.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
    }

    /** @test */
    public function email_must_be_unique_when_creating_vendor()
    {
        $existingVendor = Vendor::factory()->create(['email' => 'test@vendor.com']);
        
        $response = $this->post(route('vendors.store'), [
            'name' => 'Another Vendor',
            'email' => 'test@vendor.com',
            'phone' => '1234567890'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function email_can_be_same_when_updating_same_vendor()
    {
        $vendor = Vendor::factory()->create(['email' => 'test@vendor.com']);
        
        $response = $this->put(route('vendors.update', $vendor->id), [
            'name' => 'Updated Name',
            'email' => 'test@vendor.com', // Same email
            'phone' => '1234567890'
        ]);

        $response->assertRedirect(route('vendors.index'));
        $response->assertSessionHas('success');
    }
}
