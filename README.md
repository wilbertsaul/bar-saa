# Bar SAA — Sistema POS de Bebidas

Punto de venta (**POS**) para bar enfocado exclusivamente en la venta directa de bebidas. Sin mesas, sin anfitrionas y sin cuentas pendientes: toda venta se cobra al instante. Construido con **Laravel 12** + **MySQL** + **Tailwind CSS**.

---

## Stack tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.2, Laravel 12 |
| Base de datos | MySQL 8+ (o MariaDB 10.6+) |
| Frontend | Blade + Tailwind CSS (Vite) |
| Auth | Laravel Breeze (Blade) |
| Assets | Vite 7 + Node.js |

---

## Reglas de negocio

### Precios duales

Cada producto tiene dos precios y el sistema aplica uno u otro automáticamente según la forma de pago:

| Tipo de pago | Precio aplicado |
|--------------|-----------------|
| Efectivo | `precio_venta` (Precio Carta) |
| Yape | `precio_venta` (Precio Carta) |
| Plin | `precio_venta` (Precio Carta) |
| Tarjeta de crédito | `precio_tarjeta` |
| Tarjeta de débito | `precio_tarjeta` |

> El precio de tarjeta compensa la comisión bancaria (~5%).

### Venta directa

- Toda venta se registra y cobra en el mismo momento. No existen cuentas pendientes ni servicios a cuenta.
- Cada venta se asigna a la **caja abierta** del usuario que la registra.
- El stock se descuenta automáticamente al confirmar la venta (y se restaura al anular).

---

## Requisitos previos

- PHP 8.2+ con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- MySQL 8+ (o MariaDB 10.6+)
- Composer 2+
- Node.js 18+ y npm

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <repo-url> bar-saa
cd bar-saa

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS y compilar assets
npm install && npm run build

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de la base de datos:

```env
APP_TIMEZONE=America/Lima

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bar_saa
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 5. Crear la base de datos en MySQL
# CREATE DATABASE bar_saa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 6. Ejecutar migraciones y seeders (crea usuarios + catálogo de productos)
php artisan migrate --seed

# 7. Levantar servidor de desarrollo
php artisan serve
```

Acceder en: `http://127.0.0.1:8000`

---

## Credenciales por defecto (seeder)

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Administrador | `admin@bar.com` | `admin1234` |
| Controlador | `control@bar.com` | `control1234` |

> Cambiar las contraseñas en producción desde el perfil de usuario.

---

## Catálogo inicial (seeder)

El `ProductoSeeder` carga 13 productos con sus precios duales (carta / tarjeta):

| Categoría | Productos |
|-----------|-----------|
| Ron | Flor de Caña 7 Años, Botran 5 Años, Botran 8 Años |
| Whisky | Jack Daniel's, Chivas Regal 12, JW Black Label 12, JW Double Black, JW Gold Label Reserve |
| Cerveza | Cusqueña Dorada 310ML |
| Sin alcohol | San Mateo s/g, San Mateo c/g, Coca Cola, Red Bull |

---

## Arquitectura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php       # KPIs + ventas por método de pago
│   │   ├── PosController.php             # POS: registrar/anular venta (web + API JSON)
│   │   └── Admin/
│   │       ├── ProductoController.php    # CRUD + ingreso de stock por cajas
│   │       ├── CajaController.php        # Turnos: abrir, gastos, mermas, cerrar
│   │       ├── ReporteController.php     # Ventas e inventario/Kardex
│   │       └── AuditoriaController.php
│   └── Middleware/
│       ├── SoloAdmin.php                 # Bloquea si rol != admin (403)
│       ├── AdminOControlador.php         # Permite admin o controlador
│       └── UsuarioActivo.php             # Expulsa sesión si activo = false
├── Models/
│   ├── User.php                          # Roles: admin / controlador
│   ├── Producto.php                      # precio_venta, precio_tarjeta, stock
│   ├── Venta.php                         # TIPOS_PAGO + usaPrecioTarjeta()
│   ├── DetalleVenta.php                  # Snapshot de precio unitario
│   ├── MovimientoInventario.php          # Kardex automático
│   ├── Caja.php                          # Turno de caja con arqueo
│   ├── CorteCaja.php                     # Corte diario (agrega múltiples cajas)
│   └── AuditoriaLog.php                  # registrar() estático anti-fraude
└── Observers/
    ├── VentaObserver.php                 # Audita creación/anulación de ventas
    └── DetalleVentaObserver.php          # Descuenta/restaura stock automáticamente
```

---

## Base de datos

### Diagrama de relaciones

```
users ──< ventas, movimientos_inventario, auditoria_logs, cortes_caja, cajas

cajas ──< ventas

ventas ──< detalle_ventas
detalle_ventas >── productos ──< movimientos_inventario
```

### Tabla `$table` explícita requerida

Los modelos con nombre compuesto en español deben declarar `$table` manualmente porque el pluralizador de Laravel no funciona en español:

| Modelo | `$table` |
|--------|---------|
| `MovimientoInventario` | `movimientos_inventario` |
| `DetalleVenta` | `detalle_ventas` |
| `CorteCaja` | `cortes_caja` |
| `AuditoriaLog` | `auditoria_logs` |

---

## Flujo de negocio

### Registro de venta (POS)
1. Se abre el POS → cuadrícula de productos con búsqueda y filtros por categoría.
2. Se agregan cantidades con **+ / −** y se elige el **tipo de pago** (el total cambia entre precio carta y precio tarjeta en tiempo real).
3. `PosController@registrarVentaApi` crea `Venta` + `DetalleVenta[]` dentro de una transacción:
   - Precio unitario según tipo de pago (`Venta::usaPrecioTarjeta()`).
   - Snapshot del precio en cada detalle.
4. **`DetalleVentaObserver`** descuenta stock y escribe en `movimientos_inventario`.
5. **`VentaObserver`** escribe en `auditoria_logs`.

### Anulación de venta
- Disponible desde el propio POS (lista "Ventas de hoy") y desde el reporte de ventas.
- Restaura stock y queda registrada en auditoría.

### Cajas (turnos)
1. **Abrir caja**: cada operador abre su turno con un monto inicial.
2. Todas las ventas que registra quedan vinculadas a su caja (`venta.caja_id`).
3. Durante el turno puede registrar **gastos** y **mermas**.
4. **Cerrar caja**: arqueo con esperado = inicial + ventas − gastos − mermas, vs. contado físico → diferencia.

### Corte de caja diario
- Solo se permite **un corte por día** (validado en controller).
- Agrega las ventas de todas las cajas abiertas ese día (`caja_ids` JSON).
- Registra `diferencia_inventario = salidas_kardex − unidades_vendidas` (alerta de subregistro).

---

## Auditoría anti-fraude

`AuditoriaLog::registrar($modelo, $accion, $anterior, $nueva)` es llamado desde los Observers. Guarda:

- **Quién**: `user_id` + `ip_address`
- **Qué**: `modelo` + `modelo_id`
- **Cuándo**: `realizado_en` (timestamp)
- **Cómo cambió**: `data_anterior` y `data_nueva` en JSON

No existe endpoint de eliminación de logs. Son de solo lectura desde la interfaz.

> Si `auth()->id()` es null (comandos Artisan, seeders), el log se omite silenciosamente.

---

## Timezone

**Importante:** el timezone debe coincidir entre PHP/Laravel y MySQL para que los filtros `whereDate` funcionen correctamente.

```php
// config/app.php
'timezone' => 'America/Lima',
```

MySQL usa el timezone del sistema operativo. Si el servidor está en UTC-5 (Lima), la configuración anterior es la correcta. Ajustar según la región.

---

## Rutas principales

| Método | URI | Acceso | Descripción |
|--------|-----|--------|-------------|
| GET | `/` | auth | Dashboard con KPIs |
| GET | `/pos` | auth | Interfaz POS principal |
| POST | `/pos/venta` | auth | Registrar venta (form tradicional) |
| POST | `/pos/venta/api` | auth | Registrar venta (JSON desde el POS) |
| PATCH | `/pos/venta/{id}/anular` | auth | Anular venta |
| GET/POST | `/admin/productos` | admin/control | CRUD productos |
| POST | `/admin/productos/{id}/stock` | admin/control | Ingreso de stock por cajas |
| GET | `/admin/inventario` | admin/control | Stock actual + Kardex |
| GET | `/admin/cajas` | admin/control | Historial de cajas |
| GET/POST | `/admin/cajas/abrir` | admin/control | Abrir turno de caja |
| GET/POST | `/admin/cajas/{id}/cerrar` | admin/control | Cerrar turno (arqueo) |
| POST | `/admin/cajas/gasto` | admin/control | Registrar gasto del turno |
| GET/POST | `/admin/cajas/merma` | admin/control | Registrar merma (descuenta stock) |
| DELETE | `/admin/cajas/{id}` | admin/control | Eliminar caja |
| GET | `/admin/reportes/ventas` | admin | Ventas por período (+ anular) |
| GET | `/admin/auditoria` | admin | Logs con filtros |

---

## Comandos útiles

```bash
# Limpiar todas las cachés
php artisan optimize:clear

# Recompilar assets para producción
npm run build

# Ejecutar la suite de tests
php artisan test

# Resetear base de datos con catálogo inicial
php artisan migrate:fresh --seed
```

---

## Consideraciones para producción

- `APP_ENV=production` y `APP_DEBUG=false` en `.env`
- Servidor web (Nginx/Apache) apuntando al directorio `/public`
- `npm run build` y `php artisan optimize` para cachear rutas y configuración
- Permisos de escritura en `storage/` y `bootstrap/cache/`
- Configurar backup automático de la base de datos
