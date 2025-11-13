# Sistema de Gestión Académica

API REST desarrollada con **Laravel 12** y frontend en **Vue.js 3** para la gestión de estudios, asignaturas, alumnos y matriculaciones.

## Tecnologías

### Backend
- Laravel 12
- PHP 8.3
- MySQL 8.0
- Apache 2.4

### Frontend
- Vue.js 3
- Vue Router 4
- Vite 7
- Bootstrap 5 (vía CDN)

## Requisitos

- PHP 8.3 o superior
- Composer
- MySQL 8.0
- Apache 2.4
- Node.js 18+ y npm

## Instalación

### 1. Backend (Laravel)

```bash
# Clonar el repositorio
cd nebrija-lv

# Instalar dependencias de PHP
composer install

# Configurar entorno
copy .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# DB_CONNECTION=mysql
# DB_DATABASE=gestion_academica
# DB_USERNAME=root
# DB_PASSWORD=1234

# Ejecutar migraciones y seeders
php artisan migrate --seed
```

### 2. Frontend (Vue.js)

```bash
# Instalar dependencias npm
npm install

# Compilar para producción
npm run build

# O iniciar en modo desarrollo (con hot reload)
npm run dev
```

### 3. Configurar servidor web

La aplicación está configurada para ejecutarse con **Apache 2.4**.

**Configuración del VirtualHost:**
```apache
<VirtualHost *:80>
    ServerName nebrija-lv.local
    DocumentRoot "C:/Apache24/htdocs/nebrija-lv/public"

    <Directory "C:/Apache24/htdocs/nebrija-lv/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Archivo hosts (C:\Windows\System32\drivers\etc\hosts):**
```
127.0.0.1 nebrija-lv.local
```

**Iniciar Apache:**
```bash
# Windows
net start Apache2.4

# Acceder a la aplicación
http://nebrija-lv.local
```

## Estructura del Proyecto

### Backend (Laravel)
```
app/
├── Http/
│   ├── Controllers/Api/    # Controladores de la API REST
│   ├── Requests/           # Validación de peticiones
│   ├── Resources/          # Transformación de respuestas JSON
│   └── Middleware/         # Middlewares
│       ├── ForceJsonResponse.php
│       └── SecurityHeaders.php
├── Models/                 # Modelos Eloquent
database/
├── migrations/             # Migraciones de base de datos
└── seeders/               # Datos de prueba
routes/
├── api.php                # Rutas de la API REST
└── web.php                # Ruta para servir el SPA
```

### Frontend (Vue.js)
```
resources/vue/src/
├── components/             # Componentes reutilizables .vue
│   ├── Navbar.vue
│   ├── Pagination.vue
│   └── LoadingOverlay.vue
├── views/                  # Vistas principales .vue
│   ├── EstudiosView.vue
│   ├── AsignaturasView.vue
│   ├── AlumnosView.vue
│   └── MatriculacionesView.vue
├── services/               # Lógica de API
│   └── api.js
├── composables/            # Lógica reutilizable (Composition API)
│   ├── usePagination.js
│   └── useSort.js
├── router/                 # Vue Router
│   └── index.js
├── App.vue                 # Componente raíz
└── main.js                 # Entry point
```

## API Endpoints

Todos los endpoints están bajo el prefijo `/api` y tienen rate limiting de 60 peticiones/minuto.

### Estudios
```
GET /api/estudios
```

Respuesta:
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Ingeniería Informática"
    }
  ]
}
```

### Asignaturas
```
GET /api/asignaturas?idEstudio={id}&sortBy=nombre&sortOrder=ASC
```

Parámetros:
- `idEstudio` (requerido): ID del estudio
- `sortBy` (opcional): id, nombre, idEstudio
- `sortOrder` (opcional): ASC, DESC

Respuesta:
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Programación I",
      "idEstudio": 1
    }
  ]
}
```

### Alumnos
```
GET /api/alumnos?page=1&limit=10&sortBy=nombre&sortOrder=ASC
```

Parámetros:
- `page` (opcional): Número de página (defecto: 1)
- `limit` (opcional): Resultados por página, máximo 100 (defecto: 10)
- `sortBy` (opcional): id, nombre
- `sortOrder` (opcional): ASC, DESC

Respuesta:
```json
{
  "data": [...],
  "page": 1,
  "limit": 10,
  "total": 50,
  "totalPages": 5,
  "hasNext": true,
  "hasPrev": false
}
```

### Matriculaciones
```
GET /api/alumnos-asignaturas?page=1&limit=10&sortBy=alumnoNombre&sortOrder=ASC
```

Parámetros:
- `page` (opcional): Número de página
- `limit` (opcional): Resultados por página, máximo 100
- `sortBy` (opcional): id, alumnoNombre, asignaturaNombre, estudioNombre
- `sortOrder` (opcional): ASC, DESC

## Desarrollo

### Comandos útiles

**Desarrollo frontend con hot reload:**
```bash
npm run dev
# Mantener esta terminal abierta
# Acceder a http://nebrija-lv.local
```

**Compilar frontend para producción:**
```bash
npm run build
```

**Ejecutar tests:**
```bash
php artisan test
```

**Limpiar cachés:**
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

**Optimizar para producción:**
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Regenerar base de datos:**
```bash
php artisan migrate:fresh --seed
```

## Características de Seguridad

- **Rate limiting**: 60 peticiones por minuto por IP
- **Validación de entrada**: Form Requests en todos los endpoints
- **Headers de seguridad**:
  - X-Content-Type-Options: nosniff
  - X-Frame-Options: DENY
  - X-XSS-Protection: 1; mode=block
  - Referrer-Policy: strict-origin-when-cross-origin
- **CORS configurado**: Permite acceso desde frontend
- **Sanitización**: Whitelist de campos para ordenación
- **Logging**: Log estructurado de errores sin exponer datos sensibles

## Optimizaciones

- **Caché**: Los estudios se cachean 1 hora
- **Query optimization**: JOIN en lugar de relaciones Eloquent en matriculaciones (previene N+1)
- **Índices**: Foreign keys y campos de ordenación indexados
- **Build optimization**: Vue compilado con Vite
- **Code splitting**: Carga bajo demanda de rutas

## Arquitectura Frontend

### Vue Router (SPA)
- `/` - Listado de estudios
- `/estudio/:id/asignaturas` - Asignaturas por estudio
- `/alumnos` - Listado de alumnos con paginación
- `/matriculaciones` - Matriculaciones con paginación

### Composables
- `usePagination`: Lógica de paginación reutilizable
- `useSort`: Lógica de ordenación reutilizable

### Services
- `api.js`: Centraliza todas las llamadas al API REST

## Testing

Tests funcionales que cubren:
- Validación de parámetros requeridos
- Estructura de respuestas JSON
- Paginación y límites
- Ordenación por diferentes campos
- Manejo de errores