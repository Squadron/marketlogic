# Market Logic

Market Logic es una plataforma de administración de productos que permite gestionar usuarios, productos y realizar integraciones con APIs externas para poblar datos. También incluye funcionalidades para realizar cargas masivas de datos desde archivos Excel.

Web funcional de Ejemplo:

https://marketlogic.is-great.org/

==========================================================

### Sustentación: Ventajas de Usar HTMX + PHP vs ReactJS + NodeJS en el Proyecto Market Logic

El proyecto Market Logic fue desarrollado utilizando HTMX y PHP como tecnologías principales. Teniendo en cuenta que mi filosofia es hacer mas con menos, a continuación, presento una comparación detallada de las ventajas de esta elección frente a una implementación basada en ReactJS y NodeJS, considerando las características y necesidades específicas del proyecto (Spoiler: Usar ReactJS talvez se justifique en escenarios donde la aplicacion es muy compleja, como podria ser Facebook por ejemplo, pero en el 99% de los casos puedes usar HTMX en vez de React, se obtiene lo mismo pero con menor tiempo de desarrollo, menor uso de recursos, escalabilidad alta, etc).

### Resumen comparativo entre usar HTMX y ReactJS

1.- HTMX es liviano y NO tiene dependencias / ReactJS tiene dependencias hasta para un Hola Mundo

2.- HTMX es como Tailwind pero para el JS, se integra muy bien con HTML lo que permite desarrollar rapido (Con reactJS hay que tocar muchos archivos para hacer algo o modificar algo). Esto permite dar con algun error y corregirlo rapidamente, cubre el 99% de las cosas que se requieren para una aplicacion, aun asi, se puede integrar tambien con AlphineJS u otras bibliotecas para potenciarlo, y puede hacer mas cosas como manejar Estados por ejemplo

3.- Al desarrollar con HTML y PHP no se necesita desplegar nada, esta listo para usar directamente en cualquier servidor web, no se necesita hosting o servicios especiales (como Vercel por ejemplo).

4.- En general con HTMX se puede hacer implementaciones mas rapidas y cambios o correcciones al vuelo, no tocas mas de un archivo. No hay que compilar nada, ni desplegar, solo soltar y listo! Se logra lo mismo pero sin la complejidad de React que como minimo requiere que se conozca muchas conceptos como componentes, hooks, estado global y virtual DOM, entre otros.

5.- ReactJS talvez se justifique para aplicaciones del tamaño de Facebook y con esa carga de data, pero para la gran mayoria de aplicaciones, es complicarse la vida por gusto.

==========================================================

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
git clone https://github.com/Squadron/marketlogic.git
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
    
    Nota: Las imágenes no se incluyen en la carga masiva y se establecerán como NULL, Mientras que en la carga manual son opcionales
    
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

1.- En la carpeta "Fuente" tambien esta un archivo Excel para prueba con el formato adecuado y algunos datos de prueba llamado "plantilla_productos_carga_masiva_v2.xlsx"

### 5. Notas Adicionales

Seguridad:

- Todas las entradas del usuario se validan y sanitizan para evitar inyecciones SQL y XSS.
- Las contraseñas se almacenan de forma segura utilizando algoritmos de hashing.

Extensibilidad:

- El sistema está diseñado para ser fácilmente extensible, permitiendo agregar nuevas funcionalidades sin afectar las existentes.