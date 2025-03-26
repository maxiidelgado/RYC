proyecto es un sistema de delivery que permite a los usuarios ver un menú del día y realizar pedidos en línea. Está desarrollado en PHP y utiliza una base de datos para gestionar los menús y los pedidos.

## Características

- Visualización de menús disponibles con imágenes, precios y stock.
- Posibilidad de realizar pedidos a través de un formulario.
- Sistema de paginación para la gestión de tipos de documentos.
- Generación de comprobantes de pedido en formato PDF.
- Validación de formularios en el lado del cliente.

## Requisitos

- PHP 5.5 o superior.
- Servidor web compatible con PHP (por ejemplo, Apache).
- Base de datos MySQL.
- Composer para la gestión de dependencias.

## Instalación

1. Clona este repositorio en tu servidor local o remoto.
   ```bash
   git clone https://github.com/tu-usuario/RYC.git
   ```

2. Configura la base de datos:
   - Crea una base de datos en MySQL.
   - Importa el archivo `delivery.sql` para crear las tablas necesarias.

3. Configura la conexión a la base de datos en `database/db.php`.

4. Instala las dependencias de PHP utilizando Composer:
   ```bash
   composer install
   ```

## Estructura del Proyecto

- `index.php`: Página principal que muestra el menú del día.
- `modulos/`: Contiene módulos para la gestión de diferentes entidades como tipos de documentos, menús, etc.
- `ordenar/`: Contiene la lógica para realizar pedidos.
- `reportes/`: Contiene scripts para generar reportes y comprobantes en PDF.
- `css/`: Archivos de estilos CSS.
- `js/`: Archivos JavaScript para validaciones y scripts del cliente.
- `mail/`: Contiene la biblioteca PHPMailer para el envío de correos electrónicos.

## Uso

- Accede a la página principal (`index.php`) para ver los menús disponibles.
- Haz clic en "Ordenar" para realizar un pedido.
- Los administradores pueden gestionar tipos de documentos y otros módulos desde las páginas correspondientes en `modulos/`.


Características Principales:
- Visualización de menús disponibles con imágenes, precios y stock.
- Posibilidad de realizar pedidos a través de un formulario.
- Sistema de paginación para la gestión de tipos de documentos.
- Generación de comprobantes de pedido en formato PDF.
- Validación de formularios en el lado del cliente.

Requisitos:
- PHP 5.5 o superior.
- Servidor web compatible con PHP (por ejemplo, Apache).
- Base de datos MySQL.
- Composer para la gestión de dependencias.

Instalación:
1. Clona este repositorio en tu servidor local o remoto.
2. Configura la base de datos:
   - Crea una base de datos en MySQL.
   - Importa el archivo `delivery.sql` para crear las tablas necesarias.
3. Configura la conexión a la base de datos en `database/db.php`.
4. Instala las dependencias de PHP utilizando Composer.

Estructura de la Base de Datos:
- `banner`: Almacena información sobre los banners publicitarios.
- `barrio`: Contiene los barrios asociados a localidades.
- `categoria`: Define las categorías de productos.
- `contabilidad`: Registra las transacciones financieras.
- `contacto`: Almacena los contactos de las personas.
- `direccion`: Contiene las direcciones de las personas.
- `documento`: Almacena los documentos de identidad de las personas.
- `feedback`: Registra los comentarios de los usuarios.
- `inventario`: Gestiona el stock de productos.
- `localidad`: Contiene las localidades asociadas a provincias.
- `menu`: Almacena los menús disponibles para pedidos.
- `metodo_pago`: Define los métodos de pago aceptados.
- `modulo`: Almacena los módulos del sistema.
- `moduloperfil`: Relaciona módulos con perfiles de usuario.
- `pais`: Contiene los países disponibles.
- `pedido`: Registra los pedidos realizados por los usuarios.
- `pedidodetalle`: Almacena los detalles de cada pedido.
- `perfil`: Define los perfiles de usuario.
- `persona`: Almacena la información personal de los usuarios.
- `producto`: Contiene los productos disponibles en el inventario.
- `provincia`: Almacena las provincias asociadas a países.
- `tipocontacto`: Define los tipos de contacto disponibles.
- `tipodocumento`: Almacena los tipos de documentos de identidad.

Uso:
- Accede a la página principal (`index.php`) para ver los menús disponibles.
- Haz clic en "Ordenar" para realizar un pedido.
- Los administradores pueden gestionar tipos de documentos y otros módulos desde las páginas correspondientes en `modulos/`.

