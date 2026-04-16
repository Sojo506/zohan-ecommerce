# Manual de Instalacion

## 1. Descripcion

Este proyecto es una tienda en linea desarrollada en PHP con arquitectura MVC propia, base de datos MySQL, panel administrativo, autenticacion con OTP, carrito persistente, cupones, promociones, reportes en PDF, integracion con PayPal y subida de imagenes con Cloudinary.

## 2. Requisitos

Antes de instalar el proyecto, verifica que el entorno tenga:

- PHP 8.0 o superior
- MySQL o MariaDB
- Composer
- Node.js y npm
- Servidor web local como Apache, XAMPP, Laragon o similar

Extensiones de PHP recomendadas:

- `pdo_mysql`
- `curl`
- `mbstring`
- `openssl`
- `json`
- `iconv`
- `fileinfo`

## 3. Estructura Relevante

- `public/index.php`: punto de entrada de la aplicacion
- `config/config.php`: define la URL base del proyecto
- `.env`: variables de entorno
- `.env.example`: plantilla de variables de entorno
- `app/resources/scheme.sql`: estructura de base de datos
- `app/resources/inserts.sql`: datos base
- `app/resources/inserts_catalogo_extra.sql`: catalogo adicional opcional

## 4. Clonar o Copiar El Proyecto

Ubica el proyecto dentro del directorio publico de tu servidor local.

Ejemplo en XAMPP o Apache:

```bash
/htdocs/zohan-ecommerce
```

Ejemplo en este proyecto:

```bash
/var/www/html/zohan-ecommerce
```

## 5. Instalar Dependencias

### Dependencias PHP

Desde la raiz del proyecto ejecuta:

```bash
composer install
```

### Dependencias Frontend

Aunque el proyecto usa Bootstrap por CDN, el repositorio incluye `package.json`. Instala las dependencias por compatibilidad:

```bash
npm install
```

No hay un paso de compilacion obligatorio para levantar la app.

## 6. Configurar Variables De Entorno

Duplica el archivo de ejemplo (si tienes el repositorio, sino, abajo copias las variables):

```bash
cp .env.example .env
```

Luego edita `.env` con tus credenciales reales.

Variables necesarias:

### Base de datos

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_CHARSET`

### Correo SMTP

Se usa para:

- verificacion de cuenta por OTP
- recuperacion de contrasena
- confirmacion de compra

Variables:

- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USER`
- `MAIL_PASS`
- `MAIL_FROM`
- `MAIL_FROM_NAME`

### Cloudinary

Se usa en el panel admin para subir imagenes de productos.

Variables:

- `CLOUDINARY_CLOUD_NAME`
- `CLOUDINARY_API_KEY`
- `CLOUDINARY_API_SECRET`

### PayPal

Se usa en el checkout del carrito.

Variables:

- `PAYPAL_CLIENT_ID`
- `PAYPAL_SECRET`
- `PAYPAL_MODE`
- `PAYPAL_URL`

Para ambiente de pruebas normalmente:

```env
PAYPAL_MODE=sandbox
PAYPAL_URL=https://api-m.sandbox.paypal.com
```

## 7. Crear La Base De Datos

Crea una base de datos vacia en MySQL o MariaDB.

Nombre sugerido:

```sql
zohan_tech_store
```

## 8. Importar Scripts SQL

Importa los archivos en este orden:

1. `app/resources/scheme.sql`
2. `app/resources/inserts.sql`
3. `app/resources/inserts_catalogo_extra.sql` (opcional)

Ejemplo con MySQL:

```bash
mysql -u root -p < app/resources/scheme.sql
mysql -u root -p zohan_tech_store < app/resources/inserts.sql
mysql -u root -p zohan_tech_store < app/resources/inserts_catalogo_extra.sql
```

Notas:

- `scheme.sql` crea la base de datos y las tablas.
- `inserts.sql` carga datos base del sistema.
- `inserts_catalogo_extra.sql` amplia el catalogo y agrega mas comentarios, promociones e inventario.

## 9. Revisar La URL Base

El proyecto construye enlaces con una URL base fija en:

`config/config.php`

Valor actual:

```php
'BASE_URL' => '/zohan-ecommerce/public/index.php?url='
```

Si cambias el nombre de la carpeta del proyecto o el path publico, debes actualizar ese valor.

Ejemplos:

- Si el proyecto vive en `/zohan-ecommerce`, puedes dejarlo igual.
- Si el proyecto vive en `/tienda`, cambia la URL base a `/tienda/public/index.php?url=`.

## 10. Levantar La Aplicacion

Si ya tienes Apache o XAMPP corriendo, abre en el navegador:

```text
http://localhost/zohan-ecommerce/public/
```

O tambien:

```text
http://localhost/zohan-ecommerce/public/index.php?url=/
```

## 11. Modulos Que Deben Probarse Despues De Instalar

### Publico

- Inicio
- Catalogo
- Detalle de producto
- Carrito

### Autenticacion

- Registro
- Login
- Recuperacion de contrasena

### Admin

- Productos
- Inventario
- Marcas
- Categorias
- Promociones
- Cupones
- Reportes

## 12. Integraciones Externas

### Correo

Si no configuras SMTP correctamente:

- no funcionara el registro con OTP
- no funcionara la recuperacion de contrasena
- no se enviara el correo de compra

### Cloudinary

Si no configuras Cloudinary:

- el panel admin no podra subir imagenes de productos

### PayPal

Si no configuras PayPal:

- el checkout del carrito no podra capturar pagos

## 13. Problemas Comunes

### Error de conexion a base de datos

Revisa:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- que MySQL este encendido

### Pantallas con rutas rotas

Revisa:

- `config/config.php`
- la carpeta donde publicaste el proyecto

### Composer falla

Verifica:

- version de PHP compatible
- extensiones necesarias habilitadas

### No llegan correos

Revisa:

- credenciales SMTP
- puerto SMTP
- si el proveedor exige app password

### PayPal no procesa pagos

Revisa:

- `PAYPAL_CLIENT_ID`
- `PAYPAL_SECRET`
- `PAYPAL_URL`
- que estes usando credenciales sandbox si pruebas en sandbox

## 14. Recomendaciones De Seguridad

- No subas `.env` al repositorio.
- Usa `.env.example` como plantilla.
- Cambia todas las credenciales si el archivo `.env` tuvo valores reales compartidos.
- En produccion, desactiva `display_errors`.
- Usa HTTPS en produccion.

## 15. Instalacion Minima Recomendada Para Desarrollo

Si solo quieres ver la aplicacion funcionando localmente:

1. Instala Composer y MySQL.
2. Ejecuta `composer install`.
3. Copia `.env.example` a `.env`.
4. Configura al menos la conexion a base de datos.
5. Importa `scheme.sql` e `inserts.sql`.
6. Ajusta `config/config.php` si cambiaste el nombre de la carpeta.
7. Abre `http://localhost/zohan-ecommerce/public/`.

## 16. Observaciones Tecnicas

- El proyecto no depende de Laravel, Symfony ni otro framework grande.
- El enrutamiento es manual y vive en `public/index.php`.
- El layout publico y el admin usan vistas diferentes.
- La app usa `ID_ESTADO` para controlar activos e inactivos en muchas tablas.
- El sistema de auditoria principal esta modelado por columnas en cada tabla y triggers SQL.

## 17. Archivos Recomendados Para Revisar Si Vas A Mantener El Proyecto

- `public/index.php`
- `autoload.php`
- `app/core/Router.php`
- `app/core/Database.php`
- `app/models/ProductModel.php`
- `app/models/PaymentModel.php`
- `app/controllers/AuthController.php`



## .env.example

- DB_HOST=127.0.0.1
- DB_NAME=zohan_tech_store
- DB_USER=root
- DB_PASS=coloca_aqui_tu_password
- DB_CHARSET=utf8mb4

- MAIL_HOST=smtp.gmail.com
- MAIL_PORT=587
- MAIL_USER=tu_correo@ejemplo.com
- MAIL_PASS=tu_password_o_app_password
- MAIL_FROM=tu_correo@ejemplo.com
- MAIL_FROM_NAME=Zohan Tech Store 

- CLOUDINARY_CLOUD_NAME=tu_cloud_name
- CLOUDINARY_API_KEY=tu_api_key
- CLOUDINARY_API_SECRET=tu_api_secret

- PAYPAL_CLIENT_ID=tu_paypal_client_id
- PAYPAL_SECRET=tu_paypal_secret
- PAYPAL_MODE=sandbox
- PAYPAL_URL=https://api-m.sandbox.paypal.com


