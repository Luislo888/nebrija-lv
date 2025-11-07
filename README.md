# Sistema de Gestión Académica

API REST desarrollada con Laravel 11 y frontend en Vue.js 3 para la gestión de estudios, asignaturas, alumnos y matriculaciones.

## Requisitos

- PHP 8.2 o superior
- Composer
- SQLite (por defecto) o MySQL (producción)
- Node.js y npm (opcional, para desarrollo frontend)

## Instalación

1. Clonar el repositorio y acceder al directorio:
```bash
cd nebrija-lv
```

2. Instalar dependencias de PHP:
```bash
composer install
```

3. Crear archivo de configuración:
```bash
copy .env.example .env
```

4. Generar clave de aplicación:
```bash
php artisan key:generate
```

5. Crear base de datos SQLite:
```bash
type nul > database\database.sqlite
```

6. Ejecutar migraciones y seeders:
```bash
php artisan migrate --seed
```

7. Iniciar servidor de desarrollo:
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/Api/    # Controladores de la API
│   ├── Requests/           # Validación de peticiones
│   ├── Resources/          # Transformación de respuestas JSON
│   └── Middleware/         # Middlewares personalizados
├── Models/                 # Modelos Eloquent
database/
├── migrations/             # Migraciones de base de datos
├── seeders/               # Datos de prueba
└── factories/             # Factories para testing
public/
├── js/                    # Aplicación Vue.js
├── css/                   # Estilos
└── index.html            # SPA frontend
routes/
└── api.php               # Rutas de la API
tests/
└── Feature/              # Tests funcionales
```

## API Endpoints

### Estudios
```
GET /api/estudios
```

### Asignaturas
```
GET /api/asignaturas?idEstudio={id}&sortBy=nombre&sortOrder=ASC
```

Parámetros:
- `idEstudio` (requerido): ID del estudio
- `sortBy` (opcional): id, nombre, idEstudio
- `sortOrder` (opcional): ASC, DESC

### Alumnos
```
GET /api/alumnos?page=1&limit=10&sortBy=nombre&sortOrder=ASC
```

Parámetros:
- `page` (opcional): Número de página (por defecto: 1)
- `limit` (opcional): Resultados por página, máximo 100 (por defecto: 10)
- `sortBy` (opcional): id, nombre
- `sortOrder` (opcional): ASC, DESC

### Matriculaciones
```
GET /api/alumnos-asignaturas?page=1&limit=10&sortBy=alumnoNombre&sortOrder=ASC
```

Parámetros:
- `page` (opcional): Número de página (por defecto: 1)
- `limit` (opcional): Resultados por página, máximo 100 (por defecto: 10)
- `sortBy` (opcional): id, alumnoId, alumnoNombre, asignaturaId, asignaturaNombre, estudioId, estudioNombre
- `sortOrder` (opcional): ASC, DESC

## Comandos Útiles

Ejecutar tests:
```bash
php artisan test
```

Limpiar cachés (desarrollo):
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

Optimizar para producción:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Regenerar base de datos:
```bash
php artisan migrate:fresh --seed
```

## Seguridad

- Rate limiting: 60 peticiones por minuto por IP
- Validación de entrada en todos los endpoints
- Headers de seguridad: X-Content-Type-Options, X-Frame-Options, X-XSS-Protection
- Sanitización de parámetros de ordenación
- Logging estructurado de errores

## Caché

Los estudios se cachean durante 1 hora por defecto. Para invalidar la caché:
```bash
php artisan cache:clear
```

## Base de Datos

El proyecto usa SQLite por defecto para desarrollo. Para producción, modificar `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nebrija_lv
DB_USERNAME=root
DB_PASSWORD=tu_password
```

## Testing

31 tests funcionales cubren todos los endpoints de la API:
- Validación de parámetros
- Estructura de respuestas JSON
- Paginación y ordenación
- Manejo de errores

## Notas Técnicas

- Query Builder con JOINs para prevenir N+1 queries en matriculaciones
- Índices en foreign keys y campos de ordenación
- API Resources para transformación consistente de datos
- Form Requests para validación centralizada
