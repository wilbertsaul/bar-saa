# Guía de Usuario — Bar SAA (POS de Bebidas)

Esta guía explica cómo usar el sistema en el día a día, tanto para **controladores** como para **administradores**.

---

## Acceso al sistema

Abrir el navegador e ingresar a la dirección del sistema (por ejemplo `http://127.0.0.1:8000`).

Ingresar con el correo y contraseña asignados. Si la cuenta está desactivada, el sistema cerrará la sesión automáticamente.

---

## Roles de usuario

| Rol | Puede hacer |
|-----|------------|
| **Controlador** | Vender en el POS, abrir/cerrar su caja, registrar gastos y mermas, gestionar productos e inventario |
| **Administrador** | Todo lo anterior + reporte de ventas (con anulación) y auditoría |

---

## Panel principal (Dashboard)

Al ingresar se muestra el resumen del día:

- **Ventas hoy** — total en soles y cantidad de transacciones
- **Ventas del mes** — acumulado del mes en curso
- **Ticket promedio** — importe medio por venta del día
- **Stock bajo** — cantidad de productos por debajo del mínimo
- **Ventas por método de pago** — desglose del día (efectivo, Yape, Plin, tarjetas)
- **Última actividad** — últimos movimientos registrados en auditoría
- **Accesos rápidos** — botones directos al POS, cajas y productos

---

## POS — Registro de ventas

Es la pantalla principal para el controlador. Se accede desde el menú **POS** o el botón del dashboard.

### Registrar una venta

1. Buscar el producto con el buscador o filtrar por categoría (**Ron**, **Whisky**, **Cerveza**, **Sin alcohol**). Cada card muestra el stock disponible.
2. Agregar cantidades con los botones **+ / −**. El carrito se arma en el panel derecho.
3. Elegir el **tipo de pago**: Efectivo, Yape, Plin, Tarjeta de crédito o Tarjeta de débito.
   - Con **Efectivo/Yape/Plin** se aplica el **Precio Carta**.
   - Con **Tarjetas** se aplica automáticamente el **Precio Tarjeta** (el total cambia al instante).
4. Pulsar **"Confirmar venta"**.

> En móvil el carrito se abre desde el botón flotante inferior.

> El stock se descuenta automáticamente al confirmar. No se puede vender más stock del disponible.

### Anular una venta

En la sección **"Ventas de hoy"** del propio POS, pulsar **"Anular"** sobre la venta correspondiente y confirmar. El stock de los productos se restaura automáticamente. La anulación queda registrada en la auditoría.

---

## Productos e Inventario

**Agregar producto:**
1. Ir a **Administración → Productos → Nuevo**.
2. Completar nombre, categoría, **precio carta**, **precio tarjeta**, precio de costo, stock inicial y stock mínimo.
3. Indicar cuántas unidades trae cada caja (para el ingreso de stock).

**Ingresar stock (cajas):**
- En la tabla de productos, columna **Ingreso**, ingresar la cantidad de cajas y pulsar **+ Cajas**.
- El sistema convierte las cajas a unidades automáticamente y registra el movimiento en el Kardex.

**Alertas de stock bajo:**
- Los productos con stock igual o menor al mínimo se destacan en **amarillo** en la tabla.
- El dashboard también muestra la cantidad de productos en esta situación.

**Inventario/Kardex:** en **Administración → Inventario** se consulta el stock actual y todos los movimientos (ingresos por compra, ventas, mermas, ajustes).

---

## Cajas (turnos)

Cada operador trabaja con su propia caja abierta. Las ventas quedan vinculadas al turno.

### Abrir caja
1. Ir a **Administración → Cajas → Abrir caja**.
2. Ingresar el **monto inicial** (base) y confirmar.

### Durante el turno
- **Gasto**: registrar salidas de efectivo del turno (compras rápidas, etc.). Se pide descripción y monto.
- **Merma**: registrar producto dañado o vencido. Se descuenta stock automáticamente y queda en el Kardex.

### Cerrar caja
1. Ir a **Administración → Cajas → Cerrar** en el turno activo.
2. Se muestra el arqueo:
   - Monto inicial
   - Ventas del turno
   - Gastos y mermas
   - **Efectivo esperado** = inicial + ventas − gastos − mermas
3. Ingresar el **efectivo contado físico** → el sistema calcula la **diferencia** (sobrante/faltante).
4. Agregar observaciones y confirmar.

El historial de turnos con su detalle completo está disponible en **Administración → Cajas**.

---

## Reportes (solo administradores)

### Ventas
**Administración → Reportes → Ventas**. Lista filtrable por rango de fechas y tipo de pago, con detalle de productos, método de pago y controlador. Desde aquí también se pueden **anular** ventas.

---

## Auditoría

Registro de todas las acciones realizadas en el sistema. Solo visible para administradores.

Permite filtrar por:
- **Modelo** — qué tipo de registro fue modificado (Venta, Producto, etc.)
- **Acción** — crear, actualizar, eliminar, anular
- **Fecha** — día específico

Cada registro muestra el **detalle del cambio**: el estado anterior y el estado nuevo del registro afectado.

No existe forma de eliminar logs desde la interfaz.

---

## Preguntas frecuentes

**¿Qué pasa si me equivoco al registrar una venta?**
Anúlala desde "Ventas de hoy" en el mismo POS (o un administrador desde Reportes → Ventas). El stock se restaura automáticamente.

**¿Por qué cambia el total cuando elijo tarjeta?**
Los pagos con tarjeta aplican el **Precio Tarjeta** (mayor), que compensa la comisión bancaria. Efectivo, Yape y Plin aplican el Precio Carta.

**¿Necesito abrir caja para vender?**
Sí. Toda venta debe pertenecer a un turno de caja abierto. Si no tienes caja abierta, el POS te indicará abrirla primero.

**¿Puedo vender sin stock?**
No. El sistema valida el stock disponible de cada producto al confirmar la venta.

**¿Por qué no puedo acceder a los reportes o la auditoría?**
Solo los usuarios con rol **Administrador** pueden ver esas secciones. Solicita al administrador que cambie tu rol.

**¿Se puede cambiar la contraseña?**
Sí. Desde el menú superior, click en tu nombre de usuario → **Perfil**.

**¿Qué significa la diferencia de inventario en el corte de caja?**
Es la diferencia entre las unidades que salieron según el Kardex y las unidades que aparecen en las ventas registradas. Si el número es positivo, hubo más salidas de stock que ventas registradas — posible indicador de ventas no registradas o mermas sin declarar.
