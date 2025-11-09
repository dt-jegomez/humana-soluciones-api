<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::factory()
            ->count(10)
            ->create()
            ->each(function (Property $property) {
                $images = PropertyImage::factory()->count(rand(1, 4))->make();
                $property->images()->saveMany($images);
            });
    }
}
