<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyImage>
 */
class PropertyImageFactory extends Factory
{
    private const IMAGE_URLS = [
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.690eafdd90446_infocdn__gr-venta-consultorio-unicentro-1742388558-8863-8628jpeg.jpeg',
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.690eafde74ca0_infocdn__gr-venta-consultorio-unicentro-1742388560-9137-4322jpeg.jpeg',
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.690eafe00cc7a_infocdn__gr-venta-consultorio-unicentro-1742388567-8902-660jpeg.jpeg',
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.690eafe0d74f4_infocdn__gr-venta-consultorio-unicentro-1742388570-2765-8517jpeg.jpeg',
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.6910c2c7049b3_infocdn__gr187793420251109105555jpg.jpg',
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.6910c2c797fbb_infocdn__gr187793820251109105558jpg.jpg',
        'https://cdn4.fincaraiz.com.co/repo/img/th.outside800x600.6910c2c8467f5_infocdn__gr1877931020251109105600jpg.jpg',
        'https://s3.amazonaws.com/imagenesprof.fincaraiz.com.co/OVFR_COL/2022/9/16/3891813_678_1.jpg',
        'https://s3.amazonaws.com/imagenesprof.fincaraiz.com.co/OVFR_COL/2022/9/16/3891813_666_2.jpg',
    ];

    protected $model = PropertyImage::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->randomElement(self::IMAGE_URLS),
            'description' => $this->faker->optional()->sentence(),
            'is_primary' => $this->faker->boolean(20),
        ];
    }
}
