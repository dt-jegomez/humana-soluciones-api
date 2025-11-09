<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="Inmobiliaria API",
 *     version="1.0.0"
 * )
 *
 * @OA\Schema(
 *     schema="PropertyImagePayload",
 *     @OA\Property(property="url", type="string", format="uri", example="https://example.com/image.jpg"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Fachada principal"),
 *     @OA\Property(property="is_primary", type="boolean", nullable=true, example=true)
 * )
 *
 * @OA\Schema(
 *     schema="PropertyPayload",
 *     required={"title","city","address","bedrooms","bathrooms","consignation_type"},
 *     @OA\Property(property="title", type="string", example="Apartamento moderno en El Poblado"),
 *     @OA\Property(property="city", type="string", example="Medellín"),
 *     @OA\Property(property="address", type="string", example="Cra 43 #10-50"),
 *     @OA\Property(property="bedrooms", type="integer", example=3),
 *     @OA\Property(property="bathrooms", type="integer", example=2),
 *     @OA\Property(property="consignation_type", type="string", enum={"rent","sale"}, example="rent"),
 *     @OA\Property(property="rent_price", type="number", format="float", nullable=true, example=2500000),
 *     @OA\Property(property="sale_price", type="number", format="float", nullable=true, example=350000000),
 *     @OA\Property(property="area", type="number", format="float", nullable=true, example=95.5),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="images", type="array", @OA\Items(ref="#/components/schemas/PropertyImagePayload"))
 * )
 */
class OpenApi
{
}
