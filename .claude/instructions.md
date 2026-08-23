# Especificaciones Técnicas: Sistema de Gestión Operativa y Control de Bar

## [cite_start]1. Descripción General [cite: 1, 2]
El sistema es una solución de gestión comercial y de control diseñada para un bar con un modelo de **atención personalizada**. [cite_start]A diferencia de los sistemas POS tradicionales basados en mesas, este sistema centra toda la operatividad en la interacción entre el Cliente y la Anfitriona[cite: 4, 6, 119].

### Stack Tecnológico Requerido
* **Framework:** Laravel (PHP)
* **Base de Datos:** MySQL
* [cite_start]**Interfaz:** Diseño oscuro (ambiente nocturno), ultra rápida y optimizada para 1-2 clics máximo[cite: 122, 123, 124].

---

## [cite_start]2. Modelo Operativo del Sistema [cite: 25]
[cite_start]El núcleo del negocio es el recurso humano (anfitrionas) y el sistema debe responder siempre a: ¿Quién vendió?, ¿Qué se vendió?, ¿Cuándo? y ¿Cuánto se generó?[cite: 38, 135].

* [cite_start]**Eje 1: Control por anfitriona:** Toda venta de cerveza o servicio especial se registra a nombre de la anfitriona[cite: 27, 31].
* [cite_start]**Eje 2: Controlador como punto de registro:** El usuario "Controlador" es el encargado de validar y registrar cada movimiento en tiempo real[cite: 9, 32, 33].
* [cite_start]**Eje 3: Trazabilidad completa:** Registro de logs para evitar fraudes, subregistros o manipulación de datos[cite: 16, 37, 90, 138].

---

## 3. Módulos Funcionales

### [cite_start]🔴 1. Módulo de Control Operativo (Interfaz POS) [cite: 40]
* [cite_start]Selección rápida de anfitriona por perfil[cite: 43].
* [cite_start]Registro de venta mínima obligatoria (Ej. Pack de cervezas)[cite: 5, 44].
* [cite_start]Registro de consumo adicional de licores[cite: 45].
* [cite_start]Control de tiempos para servicios privados (dentro del local) o salidas[cite: 47, 48, 49].
* [cite_start]Gestión de estados: Disponible, Ocupada o En servicio externo[cite: 50, 51, 52, 53].

### [cite_start]🟡 2. Gestión de Anfitrionas [cite: 55]
* [cite_start]Registro y historial de actividad de cada colaboradora[cite: 57, 59].
* [cite_start]Métricas de rendimiento: Cervezas vendidas e ingresos generados[cite: 61, 62].

### [cite_start]🟢 3. Administración y Finanzas [cite: 64]
* [cite_start]Dashboard con ingresos diarios y ranking de productividad[cite: 65, 66, 67].
* [cite_start]Cálculo automático de comisiones basadas en ventas y servicios[cite: 22, 72].
* [cite_start]Corte de caja diario obligatorio[cite: 73, 98].

### [cite_start]🔵 4. Inventario de Bebidas [cite: 74]
* [cite_start]Control estricto por unidad (botellas/latas)[cite: 76].
* [cite_start]Conversión de cajas a unidades individuales[cite: 77].
* [cite_start]Descuento automático del stock tras cada venta[cite: 78].
* [cite_start]Kardex y alertas por diferencias entre consumo real vs. registrado[cite: 80, 97, 100].

---

## [cite_start]4. Requerimientos de Datos (MySQL) [cite: 101]
Las entidades principales para el desarrollo en Laravel son:
* [cite_start]`users` (Administradores y Controladores)[cite: 103].
* [cite_start]`anfitrionas` (Perfiles y estados)[cite: 104].
* [cite_start]`productos` y `inventario` (Cervezas y licores)[cite: 105, 106].
* [cite_start]`ventas` y `detalle_ventas` (Asociadas a la anfitriona)[cite: 108, 109].
* [cite_start]`servicios_especiales` (Tarifas por hora, inicio/fin de servicios)[cite: 110, 111, 112].
* [cite_start]`comisiones` (Reglas de cálculo)[cite: 113].

---

## [cite_start]5. Auditoría y Seguridad (Diferencial) [cite: 88, 89]
* [cite_start]Registro de quién realizó cada venta y la hora exacta[cite: 92, 93].
* [cite_start]Logs anti-fraude para detectar modificaciones posteriores al registro[cite: 94].
* [cite_start]Reporte automático de diferencias de inventario al cierre[cite: 99, 130].
