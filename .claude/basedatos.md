# Esquema de Base de Datos (Laravel Migrations) - Sistema de Bar

[cite_start]Este diseño asegura la trazabilidad completa y el control de inventario por unidad, eliminando el registro por mesas[cite: 13, 15, 116].

## 1. Tabla: `anfitrionas`
[cite_start]Almacena el recurso humano crítico del negocio[cite: 27, 135].
* `id`: Primary Key.
* `nombre`: Nombre completo.
* `codigo_interno`: Identificador para el controlador.
* [cite_start]`estado`: Enum ('disponible', 'ocupada', 'servicio_externo')[cite: 50, 51, 52, 53].
* `timestamps`.

## 2. Tabla: `productos` e `inventario`
[cite_start]Control estricto de cerveza por unidad y conversión de cajas[cite: 74, 76, 77].
* `id`: Primary Key.
* `nombre`: (Ej. Cerveza X 310ml).
* `stock_actual`: Cantidad en unidades (botellas/latas).
* `precio_venta`: Precio al público.
* [cite_start]`es_pack`: Booleano para identificar ventas mínimas (ej. pack de 2)[cite: 5].

## 3. Tabla: `ventas`
[cite_start]Registra la transacción vinculada directamente a la anfitriona[cite: 31, 120].
* `id`: Primary Key.
* [cite_start]`user_id`: ID del controlador que registró la venta (Auditoría)[cite: 92].
* [cite_start]`anfitriona_id`: ID de la anfitriona asociada[cite: 20].
* `total`: Monto total de la venta.
* `tipo_pago`: (Efectivo, Yape, Plin, etc.).
* [cite_start]`fecha_hora`: Timestamp exacto[cite: 93].

## 4. Tabla: `servicios_especiales`
[cite_start]Control de servicios privados y salidas con cronómetro[cite: 46, 81].
* `id`: Primary Key.
* `anfitriona_id`: ID de la anfitriona.
* [cite_start]`tipo_servicio`: (Privado local / Salida externa)[cite: 47, 48].
* [cite_start]`hora_inicio`: Inicio del servicio[cite: 49].
* [cite_start]`hora_fin`: Fin del servicio (permite calcular duración real)[cite: 49, 85].
* [cite_start]`tarifa_aplicada`: Costo pactado (ej. S/300/hora)[cite: 83].
* `estado`: (En curso, Finalizado, Cancelado).

## 5. Tabla: `comisiones`
[cite_start]Automatización del cálculo de ingresos para las colaboradoras[cite: 22, 72].
* `id`: Primary Key.
* `anfitriona_id`: ID de la anfitriona.
* `venta_id`: ID de la venta asociada (opcional).
* `servicio_id`: ID del servicio asociado (opcional).
* [cite_start]`monto_comision`: Resultado del cálculo automático[cite: 137].

## 6. Tabla: `auditoria_logs`
[cite_start]Seguridad anti-fraude para rastrear cada clic del controlador[cite: 88, 90, 94].
* `id`: Primary Key.
* `user_id`: Quién realizó la acción.
* `accion`: Descripción del evento (Ej. "Modificación de venta").
* `data_anterior`: JSON con los valores antes del cambio.
* `data_nueva`: JSON con los nuevos valores.
