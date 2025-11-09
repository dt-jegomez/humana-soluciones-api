# Humana Soluciones - Inmobiliaria API

Proyecto base para la gestión de inmuebles en Laravel 10 usando PostgreSQL y Docker. El repositorio incluye un conjunto de scripts y la aplicación completa dentro de `application/`, lista para montarse en los contenedores.

## Requisitos

- Docker y Docker Compose
- Acceso a internet (para descargar dependencias vía Composer y exponer la API externa de ciudades)

## Puesta en marcha rápida

```bash
# Construir contenedores y preparar el proyecto (instala dependencias, ejecuta migraciones y genera Swagger)
docker compose up --build
```

El comando anterior levantará los servicios:

- **app**: contenedor PHP-FPM con Laravel.
- **web**: Nginx sirviendo la aplicación.
- **db**: PostgreSQL 15.

Una vez listos los contenedores, la API estará disponible en `http://localhost:8000`.

## Estructura relevante

- `docker-compose.yml`: orquestación de servicios.
- `Dockerfile`: imagen PHP con script de bootstrap.
- `scripts/setup.sh`: crea el proyecto base de Laravel, instala dependencias adicionales y aplica la superposición.
- `application/`: proyecto Laravel versionado que se monta como volumen dentro del contenedor y contiene todo el código de dominio.

## Migraciones y seeders

El script de arranque ejecuta automáticamente las migraciones. Para lanzar seeders o nuevos comandos puedes usar:

```bash
docker compose exec app php artisan migrate --seed
```

## Documentación con Swagger

La integración se realiza con **L5 Swagger**. Después de iniciar la aplicación se genera automáticamente el archivo `storage/api-docs/api-docs.json`. Puedes regenerarlo manualmente con:

```bash
docker compose exec app php artisan l5-swagger:generate
```

La UI estará disponible en `http://localhost:8000/api/documentation`.

## Endpoints principales

| Método | Ruta | Descripción |
| --- | --- | --- |
| GET | `/api/properties` | Lista inmuebles con filtros por ciudad, rango de precio, número de habitaciones y tipo de consignación. |
| GET | `/api/properties/{id}` | Devuelve el detalle de un inmueble y sus imágenes. |
| POST | `/api/properties` | Crea un inmueble con validaciones y soporta múltiples imágenes (URL). |
| PUT | `/api/properties/{id}` | Actualiza los datos e imágenes de un inmueble existente. |
| DELETE | `/api/properties/{id}` | Elimina un inmueble (y sus imágenes) de forma permanente. |

### Parámetros de filtrado destacados

- `city`: coincidencia parcial y sin distinción de mayúsculas/minúsculas.
- `min_price` y/o `max_price`: aplica sobre los valores de arriendo y venta disponibles.
- `bedrooms`: acepta formato `bedrooms[]=1&bedrooms[]=2` o `bedrooms=1,2`.
- `consignation_type`: `rent` o `sale`.
- `per_page`: controla la paginación (1-50).

## Variables de entorno

Puedes copiar `.env.example` dentro de `application/` (una vez generado el proyecto) para ajustar credenciales personalizadas. Las variables por defecto funcionan con los contenedores definidos en `docker-compose.yml`.

## Ejecutar pruebas

```bash
docker compose exec app php artisan test
```

## Estructura de datos

El modelo principal es `Property` con los siguientes campos:

- `city`
- `address`
- `bedrooms`
- `bathrooms`
- `consignation_type` (enum: `rent`, `sale`)
- `rent_price`
- `sale_price`
- `description`

Relaciona múltiples imágenes (`PropertyImage`) y expone endpoints CRUD con filtros por ciudad, rango de precio y número de habitaciones.

## Limpieza

```bash
docker compose down -v
```
