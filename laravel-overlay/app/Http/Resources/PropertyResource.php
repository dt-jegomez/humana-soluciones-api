<?php

namespace App\Http\Resources;

use App\Http\Resources\PropertyImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'city' => $this->city,
            'address' => $this->address,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'consignation_type' => $this->consignation_type,
            'rent_price' => $this->rent_price,
            'sale_price' => $this->sale_price,
            'description' => $this->description,
            'area' => $this->area,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
