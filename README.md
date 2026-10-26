# Sorbetería Sarita · Catálogo de helados (Laravel + MySQL)

Cliente 1 de **Kubo PaaS** (plan básico). CRUD completo del catálogo de helados (conos, paletas, sorbetes,
bombas, pasteles y malteadas) con **foto por producto**, búsqueda, filtro por categoría y paginación.

> Proyecto académico de Informática en la Nube: caso simulado, sin relación oficial con Helados Sarita.
> El logo y los colores se usan solo para ambientar al cliente ficticio.

| | |
|---|---|
| Stack | PHP 8.4 · Laravel 12 · Apache 2.4 · MySQL 8.4 (base administrada por Kubo) |
| URL pública | El subdominio que asigne Kubo (por ejemplo `https://sarita.kubo.dpdns.org`) |
| Despliegue | Kubo vigila la rama `main`: cada `git push` construye la imagen con este `Dockerfile` y la despliega en menos de 1 minuto de detectarlo (sin configurar nada en GitHub) |

## Variables de entorno

Ninguna credencial está en el código ni en el repositorio.

| Variable | Quién la define | Dónde |
|---|---|---|
| `APP_KEY`, `APP_NAME`, `APP_URL` | El cliente | Panel de Kubo → *Variables de entorno* |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Kubo | Secret de la base de datos administrada, inyectado en el pod |
| `KUBO_COMMIT`, `KUBO_POD` | Kubo | Commit desplegado y réplica que atiende (se ven al pie de la página) |

`APP_KEY` se genera con `php artisan key:generate --show` (o `echo "base64:$(head -c 32 /dev/urandom | base64)"`).

## Rutas

| Método | Ruta | Acción |
|---|---|---|
| GET | `/helados?buscar=&categoria=` | Catálogo con búsqueda, filtro y paginación |
| GET/POST | `/helados/create`, `/helados` | Crear (formulario `multipart/form-data` con la foto) |
| GET/PUT | `/helados/{id}/edit`, `/helados/{id}` | Editar, cambiar o quitar la foto |
| DELETE | `/helados/{id}` | Eliminar |
| GET | `/helados/{id}/imagen` | Foto del producto (sale de MySQL) |
| GET | `/salud` | Estado y versiones (PHP, Laravel, Apache, MySQL) |

## Fotos

Las fotos (JPG, PNG o WebP, hasta 2 MB) se guardan en la columna `MEDIUMBLOB` de la tabla `helados`, dentro de la base
administrada. Así no dependen del disco del pod y sobreviven a cada despliegue. El catálogo inicial trae ilustraciones
SVG de ejemplo que se reemplazan desde *Editar*.

Al arrancar, el contenedor espera a MySQL, aplica las migraciones y carga el catálogo inicial si la tabla está vacía.
La imagen corre sin root (UID 33) y Apache escucha en el puerto 8080.
