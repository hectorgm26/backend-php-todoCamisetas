# Backend TodoCamisetas API

API backend en PHP nativo para la empresa TodoCamisetas, un proveedor mayorista especializado en camisetas de fútbol de clubes nacionales e internacionales. La API gestiona inventario de camisetas, clientes B2B y sistema de precios dinámicos con descuentos por categoría. Proyecto para evaluación de desarrollo backend.

## Descripción

Este proyecto proporciona la lógica backend para TodoCamisetas, empresa fundada en 2023 con sede en Santiago, Chile. La API REST maneja operaciones CRUD para camisetas y clientes, con un sistema de precios dinámicos que calcula descuentos según la categoría del cliente (Regular o Preferencial).

## Estructura del proyecto

```
/api-todoCamisetas/
│
├── /api/
│   └── /TodoCamisetas/
│       ├── /swagger-ui/        # Interfaz Swagger UI para testeo de endpoints
│       ├── .htaccess           # Configuraciones del servidor (URL rewrite, etc.)
│       ├── index.php           # Punto de entrada y enrutado de la API
│       ├── swagger.yaml        # Archivo de especificación Swagger de la API
│       ├── docs.php            # Redirige a Swagger UI cargando swagger.yaml
│       └── /v1/                # Versión 1 de la API
│           ├── /Config/        # Configuración de base de datos
│           ├── /Models/        # Modelos PHP que representan las entidades
│           ├── /Controller/    # Controladores para las operaciones CRUD
│           └── /Docs/          # Documentación adicional
│               └── ex_back.sql     # Respaldo completo de la base de datos MySQL
```

## Acceso a la documentación

Para acceder a la interfaz Swagger UI y probar la API, abre en tu navegador:

```
http://localhost/api-todoCamisetas/api/TodoCamisetas/docs.php
```

Desde ahí podrás enviar solicitudes a los endpoints CRUD, ver ejemplos y respuestas.

## Uso

- Este proyecto es solo backend, no incluye vistas ni frontend.
- La API se prueba usando Swagger UI o herramientas como Postman.
- Asegúrate de tener PHP configurado con soporte para `.htaccess` y URL rewriting.
- Ejecuta el servidor en la raíz del proyecto para que el enrutado funcione correctamente.
- Utiliza el token Bearer 'ipss' para autorización en todas las peticiones.

## Requisitos

- PHP 8.0 o superior
- Servidor web compatible con `.htaccess` (Apache recomendado)
- Base de datos MySQL/MariaDB
- Navegador para Swagger UI o cliente HTTP como Postman

## Configuración de la Base de Datos

El proyecto incluye un archivo `ex_back.sql` en la carpeta `/v1/Docs/` que contiene:

- **Estructura completa de la base de datos**: Todas las tablas necesarias (camisetas, clientes, tallas, camiseta_talla, etc.)
- **Datos de ejemplo**: Registros de prueba para camisetas y clientes B2B
- **Relaciones y constraints**: Claves foráneas y restricciones de integridad
- **Configuración inicial**: Usuarios y permisos básicos

### Importar la base de datos:

1. **Mediante phpMyAdmin**:
   - Accede a phpMyAdmin
   - Crea una nueva base de datos (ej: `todocamisetas_db`)
   - Selecciona la base de datos creada
   - Ve a la pestaña "Importar"
   - Selecciona el archivo `ex_back.sql`
   - Haz clic en "Continuar"

2. **Mediante línea de comandos**:
   ```bash
   mysql -u tu_usuario -p nombre_base_datos < /ruta/al/proyecto/v1/Docs/ex_back.sql
   ```

3. **Mediante herramientas como MySQL Workbench**:
   - Abre MySQL Workbench
   - Conecta a tu servidor
   - Ve a "Server" → "Data Import"
   - Selecciona "Import from Self-Contained File"
   - Elige el archivo `ex_back.sql`
   - Ejecuta la importación

## Cómo iniciar

1. **Clona este repositorio:**
   ```bash
   git clone https://github.com/tu_usuario/backend-todocamisetas.git
   ```

2. **Configura tu servidor PHP** (por ejemplo, XAMPP, WAMP, o PHP built-in server).

3. **Importa la base de datos:**
   - Localiza el archivo `ex_back.sql` en `/v1/Docs/`
   - Importa el respaldo completo a tu servidor MySQL/MariaDB
   - Esto creará automáticamente todas las tablas y datos de ejemplo

4. **Configura la conexión a la base de datos:**
   - Edita el archivo `/v1/Config/Conexion.php`
   - Ajusta los parámetros de conexión (host, usuario, contraseña, nombre de BD)

5. **Inicia el servidor:**
   - Ejecuta tu servidor web o usa el servidor built-in de PHP
   - Asegúrate de que el proyecto esté accesible desde la raíz configurada

6. **Prueba la API:**
   - Accede a la documentación Swagger:
     ```
     http://localhost/api-todoCamisetas/api/TodoCamisetas/docs.php
     ```
   - Utiliza Swagger UI o Postman para probar las operaciones CRUD
   - Usa el token Bearer 'ipss' para autorización

## Arquitectura del Sistema

### Enrutado Principal (index.php)

El archivo `index.php` actúa como el punto de entrada principal de la API y maneja:

- **Configuración CORS**: Permite solicitudes desde cualquier origen y métodos HTTP estándar
- **Autenticación**: Valida el token Bearer 'ipss' en el header Authorization
- **Enrutado**: Procesa las rutas usando `PATH_INFO` para identificar recursos e IDs
- **Delegación**: Redirige las peticiones a los controladores correspondientes

**Estructura de rutas soportadas:**
- `/camiseta` - Gestión de camisetas (stock e inventario)
- `/cliente` - Gestión de clientes B2B
- `/camiseta/{id}` - Operaciones sobre camiseta específica
- `/cliente/{id}` - Operaciones sobre cliente específico

### Controladores

Los controladores actúan como intermediarios entre las rutas y los modelos:

**camisetasController.php**
- `getallCamisetas()`: Obtiene todas las camisetas del inventario
- `getCamisetaById()`: Obtiene una camiseta específica
- `postCamiseta()`: Crea una nueva camiseta con tallas asociadas
- `putCamiseta()`: Actualiza completamente una camiseta
- `patchCamiseta()`: Actualiza campos específicos (precio, stock)
- `deleteCamiseta()`: Elimina lógicamente una camiseta
- `getCamisetaPrecioFinal()`: Calcula precio final según categoría del cliente

**clientesController.php**
- `getallClientes()`: Obtiene todos los clientes B2B activos
- `getClienteById()`: Obtiene un cliente específico
- `postCliente()`: Registra un nuevo cliente B2B
- `putCliente()`: Actualiza completamente un cliente
- `patchCliente()`: Actualiza campos específicos (dirección, categoría)
- `deleteCliente()`: Elimina lógicamente un cliente

### Modelos

Los modelos manejan la interacción directa con la base de datos:

**CamisetasModel.php**
- Gestiona la tabla `camisetas` con campos: título, club, país, tipo, color, precio, tallas, detalles, código SKU
- Maneja relación muchos a muchos con tallas (`camiseta_talla`)
- Implementa lógica de precios dinámicos según categoría del cliente
- Calcula descuentos para clientes preferenciales vs. regulares

**ClientesModel.php**
- Gestiona la tabla `clientes` con campos: nombre comercial, RUT, dirección, categoría, contacto, porcentaje de oferta
- Diferencia entre clientes "Regular" y "Preferencial"
- Implementa validaciones para evitar eliminar clientes con camisetas asociadas

### Lógica de Negocio Especializada

**Sistema de Precios Dinámicos**
- Clientes categoría "Preferencial" (ej: 90minutos): Reciben `precio_oferta` si está definido
- Clientes categoría "Regular" (ej: tdeportes): Reciben `precio` base
- Cálculo automático de `precio_final` según `cliente_id` en consultas

**Gestión de Inventario**
- Control de stock por tallas (S, M, L, XL)
- Códigos SKU únicos para cada camiseta
- Clasificación por club, país y tipo (Local, Visita, 3era, Femenino, Niño)

### Características Técnicas

- **Eliminación Lógica**: Los registros no se eliminan físicamente, solo se marca como inactivos
- **Consultas Preparadas**: Todas las consultas usan parámetros bind para prevenir inyección SQL
- **Gestión de Conexiones**: Cierre automático de conexiones en bloques `finally`
- **Manejo de Errores**: Try-catch en todas las operaciones de BD
- **Códigos HTTP**: Respuestas apropiadas según el resultado de la operación
- **Validaciones de Negocio**: Previene eliminar clientes con camisetas asociadas

## Contacto

Para consultas o colaboraciones, puedes contactarme a través de GitHub.
