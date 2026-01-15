# INFORME DE ESTADO ACTUAL: Skin Cupid Theme & Site Kit

**Fecha:** 24 Octubre 2023
**Auditor:** Jules (AI Software Engineer)
**Versión del Sistema:** Theme v1.0.0 / Site Kit v2.1.0

---

## 1. OBJETIVO DEL INFORME

Este documento detalla el estado técnico, funcional y arquitectónico del ecosistema "Skin Cupid", compuesto por el tema `skincare-theme` y su plugin satélite `skincare-site-kit`. El objetivo es proporcionar una radiografía exacta de los procesos, automatizaciones y flujos de datos existentes, sin proponer mejoras ni emitir juicios de valor.

## 2. ALCANCE DEL ANÁLISIS

El análisis cubre la totalidad del código fuente activo en el repositorio, incluyendo:
- **Theme:** `skincare-theme` (Funciones, estilos, templates, integración Woo).
- **Plugin:** `skincare-site-kit` (Lógica de negocio, widgets, API REST, Admin).
- **Integraciones:** WooCommerce, Elementor, WP Cron, REST API.
- **Frontend:** Interactividad JS, AJAX, Estilos.

---

## 3. CONTENIDO DEL INFORME

### 3.1 Visión General del Theme / App

El sistema funciona como una **Suite de eCommerce Especializada** construida sobre WordPress. No es un tema multipropósito; es una solución "turn-key" (llave en mano) diseñada específicamente para la marca Skin Cupid.

*   **Rol del Theme:** Actúa como la capa de presentación base ("Shell"). Provee la estructura HTML mínima, los estilos globales (Tokens CSS) y la compatibilidad básica con WooCommerce. Su función principal es asegurar que el sitio sea visible incluso si los plugins fallan ("Safe Mode").
*   **Rol del Site Kit:** Es el cerebro del sistema. Contiene toda la lógica de negocio, widgets de Elementor, integraciones API, sistema de puntos (Rewards), tracking de pedidos y herramientas de administración.
*   **Valor del Sistema:** El valor reside en la **desacoplación** de la lógica. El theme es "tonto" (solo pinta), mientras que el plugin inyecta funcionalidades complejas (Tracking, Rewards, Filtros) vía Shortcodes y Widgets, permitiendo actualizaciones de lógica sin romper el diseño visual.

### 3.2 Arquitectura Funcional

La arquitectura sigue un patrón modular estricto (PSR-4). La relación es de dependencia jerárquica:

1.  **WordPress Core** carga el Theme.
2.  **Theme** define estilos base y áreas de menú.
3.  **Site Kit** se inicializa, registrando CPTs (`sk_template`), Endpoints REST y Widgets.
4.  **Theme Builder** (módulo del Site Kit) intercepta la renderización de páginas clave (Header, Footer, Producto) e inyecta plantillas de Elementor si existen.
5.  **Frontend** consume la API REST (`skincare/v1`) para interactividad sin recargas.

**Diagrama de Flujo de Renderizado:**
`Petición HTTP` → `WP Init` → `Theme Functions` → `Site Kit Loader` → `Theme Builder (Intercepta)` → `¿Existe Plantilla? (Sí)` → `Renderiza Elementor` → `Widgets Custom` → `Salida HTML`.

**Mecanismo de Fallback (Safe Mode):**
Si Site Kit se desactiva o falla, el Theme renderiza sus propios archivos PHP (`header.php`, `footer.php`) que contienen un HTML estático "de emergencia", asegurando que el sitio nunca muestre una página en blanco.

### 3.3 Proceso de Importación / Inicialización (Seeder)

La inicialización es **híbrida: automática y manual**.

*   **El "Seeder" (Sembrador):** Es una clase (`Skincare\SiteKit\Modules\Seeder`) que orquesta la creación de contenido.
*   **Disparadores:**
    1.  **Automático:** Al detectar un cambio de versión en la constante `SEED_VERSION` (actualmente `5`).
    2.  **Manual:** Vía el asistente "Onboarding" o parámetro GET `?sk_seed_content=true`.
*   **Qué crea exactamente:**
    *   **Páginas Core:** Home, Shop, Account, Rewards, etc. Inyecta HTML con shortcodes complejos (ej. `[sk_rewards_dashboard]`).
    *   **Productos Demo:** Crea productos con precios, categorías y atributos.
    *   **Imágenes:** Descarga ("Sideload") imágenes placeholder desde URLs externas si faltan.
    *   **Taxonomías:** Crea categorías jerárquicas (ej. Limpieza > Aceite).
    *   **Theme Parts:** Crea posts del tipo `sk_template` para Header, Footer y Archivos, con contenido predefinido.
    *   **Menús:** Crea y asigna menús "Primary" y "Footer".
*   **Feedback:** El admin ve un Wizard visual (barra de progreso) que ejecuta cada paso vía AJAX para evitar timeouts.

### 3.4 Dashboard / Panel de Control

El control se centraliza en el menú `Skin Cupid` del admin de WordPress.

1.  **Control Center (Dashboard):** Muestra tarjetas de estado de los módulos.
2.  **Asistente (Onboarding):** Interfaz paso a paso para ejecutar el Seeder.
3.  **Rewards Master:** Panel dedicado a la gestión de puntos. Permite ver el libro mayor (`ledger`), ajustar puntos manualmente y configurar reglas de canje.
4.  **Tools (Herramientas):** Accesos directos para limpiar cachés o forzar re-seeding.

**Procesos en Segundo Plano:**
*   Cron Jobs diarios para limpieza de puntos expirados y detección de carritos abandonados.

### 3.5 Módulos del Sistema (Detalle Técnico)

| Módulo | Tipo | Función Principal | Dependencia |
| :--- | :--- | :--- | :--- |
| **Theme Builder** | Core | Reemplaza templates de WooCommerce con diseños de Elementor (CPT `sk_template`). | Elementor |
| **Rewards System** | Core (Backend) | Gestiona puntos en tabla custom `wp_sk_points_ledger`. Lógica de ganar/gastar/expirar. | WooCommerce |
| **Tracking Manager** | Core (Backend) | Abstrae la lógica de seguimiento. Usa `Native_Manual_Provider` para leer meta fields (`_sk_tracking_number`, `_sk_packing_status`). | WooCommerce |
| **Account Dashboard** | Frontend (Widget) | Widget monolítico que renderiza pestañas de Pedidos, Puntos y Direcciones. Consume `Tracking_Manager`. | Tracking, Rewards |
| **Cart Drawer** | Frontend (Módulo) | Carrito lateral AJAX. Usa endpoints propios para cupones y actualizaciones rápidas. | WooCommerce |
| **Filter Handler** | Mixto | Filtra productos vía AJAX (`sk_filter_products`). Devuelve HTML renderizado (Server-Side Rendering parcial). | WooCommerce |
| **Marketing Events** | Backend | Cron jobs para detectar carritos abandonados e inyectar tracking en emails transaccionales. | WP Cron |
| **Rest Controller** | Core (API) | Expone endpoints `skincare/v1` para Rewards, Contacto, Search y Stock. | WP REST API |
| **Seeder** | Soporte | Crea/Repara contenido base. Auto-actualizable por versión. | Ninguna |

### 3.6 Flujos del Usuario Final

1.  **Navegación:** El usuario entra a la Home. Ve sliders y grids renderizados por Elementor widgets (`Sk_Product_Grid`).
2.  **Interacción:**
    *   **Búsqueda:** Al escribir > 3 caracteres, `site-kit.js` llama a `skincare/v1/search`.
    *   **Filtros:** En Shop, los checkboxes disparan `sk_filter_products` (AJAX) para recargar el grid sin refrescar la página.
    *   **Quick View:** Carga modal con datos simulados (en la demo actual).
3.  **Compra:**
    *   Agrega al carrito (AJAX). Se abre el `Cart Drawer`.
    *   Puede aplicar cupón en el Drawer (`skincare/v1/cart/coupon`).
    *   Checkout estándar de WooCommerce (estilizado).
4.  **Post-Compra (Rewards):**
    *   Al pasar el pedido a "Entregado" (`sk-delivered`), el sistema calcula puntos (Total * Ratio) e inserta en `sk_points_ledger`.
5.  **Mi Cuenta:**
    *   Ve un Dashboard custom (`[sk_account_dashboard]`).
    *   **Tracking Visual:** Ve una línea de tiempo (Stepper) de 4 pasos. El paso activo depende del estado del pedido y meta fields.
    *   **Canje:** Si tiene puntos suficientes (>500), ve botón "Canjear". Al clicar, JS llama a `skincare/v1/rewards/redeem`, el backend genera un cupón WC real y devuelve el código.

### 3.7 Flujos del Administrador

1.  **Gestión de Pedidos:**
    *   El admin entra al pedido en WooCommerce.
    *   Llena campos custom: `_sk_tracking_number`, `_sk_carrier`.
    *   Cambia estado a "En camino" (`sk-on-the-way`) o "Entregado" (`sk-delivered`).
2.  **Gestión de Rewards:**
    *   Puede ir a `Rewards Master` para ver quién tiene puntos.
    *   Puede ajustar puntos manualmente (ej. "Bono por error") que se registra como transacción en el Ledger.
3.  **Mantenimiento:**
    *   Si actualiza el plugin, el Seeder puede correr automáticamente para actualizar páginas.

### 3.8 Automatizaciones Existentes

| Automatización | Disparador | Tipo | Visibilidad |
| :--- | :--- | :--- | :--- |
| **Asignación de Puntos** | Cambio de estado de pedido a "Entregado" | Automática (Hook) | Visible (Ledger) |
| **Expiración de Puntos** | Cron Diario (`sk_rewards_expire_points`) | Automática | Silenciosa (DB) |
| **Email Tracking** | Envío de email transaccional WC | Automática (Filtro) | Visible (Email Cliente) |
| **Carritos Abandonados** | Cron Diario (`sk_daily_cron_job`) | Semi-Automática | Silenciosa (Log) |
| **Inicialización de Contenido** | Activación Plugin / Upgrade Versión | Automática | Visible (Admin Notice) |

### 3.9 Tracking de Pedidos

El sistema construye un "Stepper Visual" de 4 pasos:
1.  **Pedido Confirmado:** Estado `processing`, `on-hold`.
2.  **Empaque:** Estado `processing` + Meta `_sk_packing_status`.
3.  **En Camino:** Estado `sk-on-the-way` O presencia de Tracking Number.
4.  **Entregado:** Estado `completed` o `sk-delivered`.

**Datos:** Se leen directamente de `post_meta` del pedido. Si faltan datos, el stepper se queda en el paso anterior. No hay conexión a APIs externas de paquetería; es 100% manual/nativo.

### 3.10 Emails y Comunicaciones

*   **WooCommerce Estándar:** El sistema usa los emails nativos.
*   **Inyección:** La clase `Marketing_Events` inyecta un bloque HTML "Sigue tu pedido" con botón al Account Dashboard en los correos de "Procesando" y "Completado".
*   **Formularios:** Los formularios de contacto (`Forms`) envían emails simples vía `wp_mail()` al admin, sin guardar en base de datos (solo email efímero).

### 3.11 Dependencias y Riesgos Operativos

1.  **WooCommerce:** Crítica. Sin Woo, el 90% del Site Kit falla (Rewards, Tracking, Cart).
2.  **Elementor:** Crítica para la visualización. Sin Elementor, el Theme Builder cae al fallback de shortcodes, perdiendo el diseño grid/visual.
3.  **Base de Datos Custom (`sk_points_ledger`):** Si esta tabla se corrompe o borra, se pierde el saldo de puntos de todos los usuarios. Es el "Single Source of Truth" financiero del sistema de lealtad.
4.  **Cron de WP:** Si los crons no corren, los puntos nunca expiran y los carritos abandonados no se detectan.

### 3.12 Tabla de Estado del Theme / App

| Módulo | Estado Actual | Tipo | Admin Visible | Nivel Automatización |
| :--- | :--- | :--- | :--- | :--- |
| **Theme Base** | Activo (v1.0.0) | Core | No | N/A |
| **Theme Builder** | Activo | Core | Sí (CPT) | Automático (Inyección) |
| **Seeder** | Activo (v5) | Soporte | Sí (Wizard) | Semi-Automático |
| **Rewards (Ledger)** | Activo | Negocio | Sí (Master) | Automático |
| **Tracking System** | Activo (Manual) | Negocio | Campos Meta | Manual (Data Entry) |
| **Cart Drawer** | Activo | Frontend | No | Automático |
| **Filtros AJAX** | Activo | Frontend | No | Automático |
| **Marketing Auto** | Activo | Negocio | No | Automático (Cron) |
| **API REST** | Activo | Core | No | Automático |
