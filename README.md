# Market Logic

Market Logic es una plataforma de administración de productos que permite gestionar usuarios, productos y realizar integraciones con APIs externas para poblar datos. También incluye funcionalidades para realizar cargas masivas de datos desde archivos Excel.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes componentes:

- **Servidor Web**: Apache o Nginx.
- **PHP**: Versión 7.4 o superior.
- **Base de Datos**: MySQL o MariaDB.
- **Composer**: Para gestionar dependencias de PHP. (En este caso se requiere para poder cargar e interactuar con archivos de Excel)
- **Extensiones de PHP**:
  - `pdo_mysql`
  - `fileinfo`
  - `mbstring`
  - `json`

## Instalación

Sigue estos pasos para configurar y ejecutar el proyecto:

### 1. Clonar el Repositorio

Clona este repositorio en tu servidor local:

```bash
git clone https://github.com/tu-usuario/market-logic.git
cd market-logic
```

### 2. Configuracion de datos de acceso a la DB

Los datos de configuracion para la DB estan en el archivo "conex.php"

'type' => 'mysql',
'host' => 'localhost',
'database' => 'marketlogic',
'username' => 'root',
'password' => '',

### 3. Instalacion de Dependencias de PHP (Gracias a Excel)

Ejecutar el comando:

```bash
composer install
```

### 4. Instalacion de la DB de Prueba (Contiene la estructura y algunos datos de prueba)

1.- Crea una base de datos en tu servidor MySQL llamada marketlogic.
2.- Importa el archivo SQL proporcionado (marketlogic.sql) para crear las tablas y datos iniciales:

```bash
mysql -u root -p marketlogic < DB.sql
```

NOTA: El archivo.sql (DB.sql) para restaurar esta en la carpeta "Fuente".

### 5. Puesta en marcha

1.- No requiere despliegue, simplemente se pone los archivos en el servidor, se restaura la DB, se configura los accesos a la DB y wuala! Listo para usar.

2.- verificar los permisos de escritura:

Asegúrate de que las carpetas que requieren escritura (como uploads para imágenes o archivos temporales) tengan los permisos adecuados en el servidor

### 6. Credenciales de acceso

1.- Se pueden Crear cuentas Nuevas pero tambien se proporcionan 3 cuentas de prueba, todas con la misma clave de acceso: "123456"

Cuentas de prueba pre-instaladas:

    - arkweb@gmail.com
    - arkweb1@gmail.com
    - gohanweb@hotmail.com

2.- No se ha implementado soporte para distintos niveles de acceso o administracion por cuenta debido a que no fue solicitado, pero no habria problema en hacerlo posteriormente.

### 7. Funcionalidades Principales

1. Gestión de Productos
    - Crear, editar y eliminar productos.
    - Realizar búsquedas avanzadas por nombre, categoría o estado.
    - Subir imágenes de productos.
2. Poblar Productos desde una API Externa
    
    El sistema permite poblar productos desde una API externa gratuita como Fake Store API.

    Pasos para Poblar Productos:
    - Accede a la sección Productos.
    - Haz clic en el botón "Poblar desde una API".
    - Configura la URL de la API (por defecto: https://fakestoreapi.com/products) y la cantidad de productos a importar.
    - Haz clic en "Iniciar Importación".
    - Los productos serán importados y mostrados en la lista.
3. Carga Masiva de Productos desde Excel
    
    El sistema permite cargar productos desde un archivo Excel.

    Pasos para la Carga Masiva:
    - Accede a la sección Productos.
    - Haz clic en el botón "Carga Masiva".
    - Sube un archivo Excel con el formato requerido:
    
    Columnas Requeridas:
    - nombre: Nombre del producto.
    - descripcion: Descripción del producto.
    - precio: Precio del producto.
    - stock: Cantidad en inventario.
    - categoria: Categoría del producto.
    - estado: Estado del producto (activo, inactivo, agotado).
    
    Nota: Las imágenes no se incluyen en la carga masiva y se establecerán como NULL.
    
    Haz clic en "Cargar" para procesar el archivo.
4. Gestión de Usuarios

    - Crear, editar y eliminar usuarios.
    - Buscar usuarios por nombre o correo electrónico.

### 8. Tecnologias usadas

    - HTML
    - CSS (Tailwind)
    - PHP
    - Medoo
    - HTMX
    - AlphineJS
    - JS

### 9. Pasos para "Desplegar" en Produccion (Resumen)

1.- Copia todos los archivos del proyecto al servidor de producción, incluyendo:

    - Archivos del proyecto (index.php, productos, usuarios/, etc.).
    - Carpeta vendor (con todas las dependencias instaladas por Composer).
    - Carpeta uploads (si ya contiene imágenes o archivos relevantes).

2.- Restaura la base de datos inicial desde el archivo SQL (marketlogic.sql).

3.- Configura el acceso a la base de datos en conex.php

4.- Verifica que las extensiones de PHP requeridas estén habilitadas en el servidor.

5.- Asegúrate de que las carpetas que requieren escritura (como uploads) tengan permisos adecuados.

### 10. Archivo Excel de prueba con el formato adecuado

1.- En la carpeta "Fuente" tambien esta un arrchivo Excel para prueba con el formato adecuado y algunos datos de prueba llamado "plantilla_productos_carga_masiva_v2.xlsx"