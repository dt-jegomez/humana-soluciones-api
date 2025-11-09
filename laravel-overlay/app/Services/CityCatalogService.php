<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CityCatalogService
{
    private const CITY_URL = 'https://api-colombia.com/api/v1/City';
    private const DEPARTMENT_URL = 'https://api-colombia.com/api/v1/Department';

    public function search(?string $department, ?string $search, int $limit = 10): array
    {
        $limit = min(max((int) $limit, 1), 100);

        try {
            $citiesResponse = Http::acceptJson()
                ->timeout(10)
                ->get(self::CITY_URL);
        } catch (\Throwable $exception) {
            return [];
        }

        if ($citiesResponse->failed()) {
            return [];
        }

        $cities = $citiesResponse->json() ?? [];

        // Optional department filter: accepts department id or name (partial)
        $departmentFilter = trim((string) ($department ?? ''));
        if ($departmentFilter !== '') {
            $deptIds = [];
            if (is_numeric($departmentFilter)) {
                $deptIds[] = (int) $departmentFilter;
            } else {
                try {
                    $departmentsResponse = Http::acceptJson()->timeout(10)->get(self::DEPARTMENT_URL);
                    if ($departmentsResponse->ok()) {
                        $needle = strtolower($departmentFilter);
                        $deptIds = collect($departmentsResponse->json() ?? [])
                            ->filter(function ($dept) use ($needle) {
                                $name = isset($dept['name']) ? strtolower((string) $dept['name']) : '';
                                return $name !== '' && (strpos($name, $needle) !== false);
                            })
                            ->pluck('id')
                            ->values()
                            ->all();
                    }
                } catch (\Throwable $e) {
                    // Ignore and leave $deptIds empty
                }
            }

            if (! empty($deptIds)) {
                $cities = array_values(array_filter($cities, function ($city) use ($deptIds) {
                    return isset($city['departmentId']) && in_array((int) $city['departmentId'], $deptIds, true);
                }));
            }
        }

        // Optional name search filter
        $searchFilter = trim((string) ($search ?? ''));
        if ($searchFilter !== '') {
            $needle = strtolower($searchFilter);
            $cities = array_values(array_filter($cities, function ($city) use ($needle) {
                $name = isset($city['name']) ? strtolower((string) $city['name']) : '';
                return $name !== '' && (strpos($name, $needle) !== false);
            }));
        }

        // Order by name asc
        usort($cities, function ($a, $b) {
            return strcmp((string) ($a['name'] ?? ''), (string) ($b['name'] ?? ''));
        });

        // Apply limit
        return array_slice($cities, 0, $limit);
    }
}
