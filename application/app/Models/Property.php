<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'city',
        'address',
        'bedrooms',
        'bathrooms',
        'consignation_type',
        'rent_price',
        'sale_price',
        'description',
        'area',
    ];

    protected $casts = [
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'rent_price' => 'float',
        'sale_price' => 'float',
        'area' => 'float',
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
}
