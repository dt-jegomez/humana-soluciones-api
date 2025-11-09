<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $consignation = $this->faker->randomElement(['rent', 'sale']);

        return [
            'title' => $this->faker->sentence(4),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'bedrooms' => $this->faker->numberBetween(1, 6),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'consignation_type' => $consignation,
            'rent_price' => $consignation === 'rent' ? $this->faker->randomFloat(2, 600000, 4000000) : null,
            'sale_price' => $consignation === 'sale' ? $this->faker->randomFloat(2, 150000000, 900000000) : null,
            'description' => $this->faker->paragraph(),
            'area' => $this->faker->randomFloat(2, 45, 250),
        ];
    }
}
