<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Properties", description="Gestión de inmuebles")
 */
class PropertyController extends Controller
{
    /** */

    /**
     * @OA\Get(
     *     path="/api/properties",
     *     summary="Listado de inmuebles",
     *     @OA\Parameter(name="city", in="query", description="Ciudad", @OA\Schema(type="string")),
     *     @OA\Parameter(name="min_price", in="query", description="Precio mínimo", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="max_price", in="query", description="Precio máximo", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="bedrooms[]", in="query", description="Número de habitaciones (búsqueda múltiple)", @OA\Schema(type="array", @OA\Items(type="integer"))),
     *     @OA\Parameter(name="consignation_type", in="query", @OA\Schema(type="string", enum={"rent","sale"})),
     *     @OA\Parameter(name="per_page", in="query", description="Cantidad de resultados por página (1-50)", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Listado paginado")
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $query = Property::query()->with('images');

        if ($city = request('city')) {
            $operator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where('city', $operator, "%" . $city . "%");
        }

        $bedroomsInput = request()->input('bedrooms', []);
        $bedrooms = collect(is_array($bedroomsInput) ? $bedroomsInput : explode(',', (string) $bedroomsInput))
            ->filter()
            ->map(fn ($value) => (int) $value);
        if ($bedrooms->isNotEmpty()) {
            $query->whereIn('bedrooms', $bedrooms);
        }

        $minPrice = request('min_price');
        $maxPrice = request('max_price');
        if ($minPrice !== null || $maxPrice !== null) {
            $query->where(function ($sub) use ($minPrice, $maxPrice) {
                if ($minPrice !== null) {
                    $sub->where(function ($inner) use ($minPrice) {
                        $inner->whereNotNull('rent_price')->where('rent_price', '>=', $minPrice)
                            ->orWhere(function ($saleQuery) use ($minPrice) {
                                $saleQuery->whereNotNull('sale_price')->where('sale_price', '>=', $minPrice);
                            });
                    });
                }
                if ($maxPrice !== null) {
                    $sub->where(function ($inner) use ($maxPrice) {
                        $inner->whereNotNull('rent_price')->where('rent_price', '<=', $maxPrice)
                            ->orWhere(function ($saleQuery) use ($maxPrice) {
                                $saleQuery->whereNotNull('sale_price')->where('sale_price', '<=', $maxPrice);
                            });
                    });
                }
            });
        }

        if ($type = request('consignation_type')) {
            $query->where('consignation_type', strtolower($type));
        }

        $perPage = (int) request()->input('per_page', 15);
        $perPage = max(1, min($perPage, 50));

        return PropertyResource::collection(
            $query->paginate($perPage)->withQueryString()
        );
    }

    /**
     * @OA\Get(
     *     path="/api/properties/{property}",
     *     summary="Detalle de un inmueble",
     *     @OA\Parameter(name="property", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalle del inmueble"),
     *     @OA\Response(response=404, description="No encontrado")
     * )
     */
    public function show(Property $property): PropertyResource
    {
        $property->load('images');

        return PropertyResource::make($property);
    }

    /**
     * @OA\Post(
     *     path="/api/properties",
     *     summary="Crear un inmueble",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/PropertyPayload")),
     *     @OA\Response(response=201, description="Inmueble creado")
     * )
     */
    public function store(StorePropertyRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $property = DB::transaction(function () use ($payload) {
            $images = $payload['images'] ?? [];
            unset($payload['images']);

            $property = Property::create($payload);
            if (! empty($images)) {
                $property->images()->createMany($images);
            }

            return $property->load('images');
        });

        return PropertyResource::make($property)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Put(
     *     path="/api/properties/{property}",
     *     summary="Actualizar un inmueble",
     *     @OA\Parameter(name="property", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/PropertyPayload")),
     *     @OA\Response(response=200, description="Inmueble actualizado")
     * )
     */
    public function update(UpdatePropertyRequest $request, Property $property): PropertyResource
    {
        $payload = $request->validated();

        DB::transaction(function () use ($property, $payload) {
            $images = $payload['images'] ?? null;
            unset($payload['images']);

            $property->update($payload);

            if (is_array($images)) {
                $property->images()->delete();
                $property->images()->createMany($images);
            }
        });

        return PropertyResource::make($property->fresh('images'));
    }

    /**
     * @OA\Delete(
     *     path="/api/properties/{property}",
     *     summary="Eliminar un inmueble",
     *     @OA\Parameter(name="property", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Eliminado")
     * )
     */
    public function destroy(Property $property): JsonResponse
    {
        $property->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
