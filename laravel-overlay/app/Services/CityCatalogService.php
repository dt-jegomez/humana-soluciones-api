<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CityCatalogService
{
    private const BASE_URL = 'https://www.datos.gov.co/resource/xdk5-pm3f.json';

    public function search(?string $department, ?string $search, int $limit = 10): array
    {
        try {
            $client = Http::acceptJson()
                ->timeout(10);

            if ($token = config('services.datos_gov.token')) {
                $client = $client->withHeaders(['X-App-Token' => $token]);
            }

            $response = $client->get(self::BASE_URL, array_filter([
                    '$limit' => min(max($limit, 1), 100),
                    'departamento' => $department,
                    '$q' => $search,
                    '$select' => 'municipio,departamento,codigo_dane_del_municipio',
                    '$order' => 'municipio',
                ]));
        } catch (\Throwable $exception) {
            return [];
        }

        if ($response->failed()) {
            return [];
        }

        return $response->json() ?? [];
    }
}
