<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyImage>
 */
class PropertyImageFactory extends Factory
{
    protected $model = PropertyImage::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->imageUrl(800, 600, 'real-estate'),
            'description' => $this->faker->optional()->sentence(),
            'is_primary' => $this->faker->boolean(20),
        ];
    }
}
