<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_properties_with_filters(): void
    {
        Property::factory()->count(2)->create(['city' => 'Bogotá', 'bedrooms' => 2]);
        Property::factory()->create(['city' => 'Medellín', 'bedrooms' => 3]);

        $response = $this->getJson('/api/properties?city=Bogotá&bedrooms[]=2');

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_can_create_property_with_images(): void
    {
        $payload = [
            'title' => 'Casa campestre',
            'city' => 'Cali',
            'address' => 'Av. las Palmas 123',
            'bedrooms' => 4,
            'bathrooms' => 3,
            'consignation_type' => 'sale',
            'sale_price' => 320000000,
            'images' => [
                ['url' => 'https://example.com/front.jpg', 'is_primary' => true],
                ['url' => 'https://example.com/living.jpg'],
            ],
        ];

        $response = $this->postJson('/api/properties', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Casa campestre')
            ->assertJsonCount(2, 'data.images');

        $this->assertDatabaseHas('properties', ['title' => 'Casa campestre']);
        $this->assertDatabaseCount('property_images', 2);
    }
}
