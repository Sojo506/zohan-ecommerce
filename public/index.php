<?php

// Front controller: toda petición web entra aquí y desde aquí se registra el autoload del proyecto.
require_once __DIR__ . '/../autoload.php';

/*  CONFIGURACIÓN DE ERRORES  */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');

if (function_exists('opcache_reset')) {
    opcache_reset();
}


/*  CARGAR VARIABLES DE ENTORNO  */
Env::load(__DIR__ . '/../.env');


/*  INICIAR SESIÓN  */
session_start();


/*  CREAR ROUTER  */
$router = new Router();

// Este archivo funciona como tabla central de rutas: cada entrada apunta a "Controlador@metodo".

/*  RUTAS PRINCIPALES  */
$router->get('/', 'HomeController@index');


/*  RUTAS ADMIN  */
$router->get('/admin', 'AdminController@index');


/*  RUTAS PRODUCTOS ADMIN  */
$router->get('/admin/products', 'ProductAdminController@index'); // alias útil
$router->get('/admin/products/list', 'ProductAdminController@index');
$router->get('/admin/products/create', 'ProductAdminController@createForm');
$router->get('/admin/products/edit/{id}', 'ProductAdminController@editForm');
$router->get('/admin/products/delete/{id}', 'ProductAdminController@delete');
$router->get('/admin/products/delete-image/{id}', 'ProductAdminController@deleteImage');

$router->post('/admin/products/create', 'ProductAdminController@create');
$router->post('/admin/products/upload-image', 'ProductAdminController@uploadImage');
$router->post('/admin/products/update', 'ProductAdminController@update');


/*  RUTAS CATEGORÍAS ADMIN  */
$router->get('/admin/categories', 'CategoryAdminController@index');
$router->get('/admin/categories/create', 'CategoryAdminController@createForm');
$router->post('/admin/categories/create', 'CategoryAdminController@create');
$router->get('/admin/categories/edit/{id}', 'CategoryAdminController@editForm');
$router->post('/admin/categories/update', 'CategoryAdminController@update');
$router->get('/admin/categories/delete/{id}', 'CategoryAdminController@delete');


/*  RUTAS MARCAS ADMIN  */
$router->get('/admin/brands', 'BrandAdminController@index');
$router->get('/admin/brands/create', 'BrandAdminController@createForm');
$router->post('/admin/brands/create', 'BrandAdminController@create');
$router->get('/admin/brands/edit/{id}', 'BrandAdminController@editForm');
$router->post('/admin/brands/update', 'BrandAdminController@update');
$router->get('/admin/brands/delete/{id}', 'BrandAdminController@delete');


/* RUTAS INVENTARIO ADMIN */
$router->get('/admin/inventory', 'InventoryAdminController@index');
$router->get('/admin/inventory/movement', 'InventoryAdminController@movementForm');
$router->get('/admin/inventory/movements', 'InventoryAdminController@movements');
$router->post('/admin/inventory/movement', 'InventoryAdminController@registerMovement');


/* RUTAS VENTAS ADMIN */
$router->get('/admin/orders', 'SaleAdminController@index'); // alias de compatibilidad
$router->get('/admin/sales', 'SaleAdminController@index');
$router->get('/admin/sales/{id}', 'SaleAdminController@detail');


/* RUTAS FACTURAS ADMIN */
$router->get('/admin/invoices', 'InvoiceAdminController@index');
$router->get('/admin/invoices/{id}', 'InvoiceAdminController@detail');
$router->get('/admin/invoices/{id}/status/{status}', 'InvoiceAdminController@changeStatus');


/* RUTAS REPORTES ADMIN */
$router->get('/admin/reports', 'ReportAdminController@index');
$router->get('/admin/reports/pdf/{type}', 'ReportAdminController@download');


/* RUTAS USUARIOS ADMIN */
$router->get('/admin/users', 'UserAdminController@index');
$router->get('/admin/users/{id}', 'UserAdminController@detail');
$router->get('/admin/users/{id}/status/{status}', 'UserAdminController@changeStatus');
$router->get('/admin/users/{id}/role/{role}', 'UserAdminController@changeRole');


/* RUTAS CUENTAS ADMIN */
$router->get('/admin/accounts', 'AccountAdminController@index');
$router->get('/admin/accounts/create', 'AccountAdminController@createForm');
$router->post('/admin/accounts/create', 'AccountAdminController@create');
$router->get('/admin/accounts/edit/{id}', 'AccountAdminController@editForm');
$router->post('/admin/accounts/update', 'AccountAdminController@update');
$router->get('/admin/accounts/delete/{id}', 'AccountAdminController@delete');


/* RUTAS CUPONES ADMIN */
$router->get('/admin/coupons', 'CouponAdminController@index');
$router->get('/admin/coupons/create', 'CouponAdminController@createForm');
$router->post('/admin/coupons/create', 'CouponAdminController@create');
$router->get('/admin/coupons/edit/{id}', 'CouponAdminController@editForm');
$router->post('/admin/coupons/update', 'CouponAdminController@update');
$router->get('/admin/coupons/delete/{id}', 'CouponAdminController@delete');


/* RUTAS PROMOCIONES ADMIN */
$router->get('/admin/promotions', 'PromotionAdminController@index');
$router->get('/admin/promotions/create', 'PromotionAdminController@createForm');
$router->post('/admin/promotions/create', 'PromotionAdminController@create');
$router->get('/admin/promotions/edit/{id}', 'PromotionAdminController@editForm');
$router->post('/admin/promotions/update', 'PromotionAdminController@update');
$router->get('/admin/promotions/delete/{id}', 'PromotionAdminController@delete');
$router->post('/admin/promotions/assign', 'PromotionAdminController@assignProduct');
$router->get('/admin/promotions/remove-product/{promo}/{product}', 'PromotionAdminController@removeProduct');


/* RUTAS COMMENTS ADMIN */
$router->get('/admin/comments', 'CommentAdminController@index');
$router->get('/admin/comments/approve/{id}', 'CommentAdminController@approve');
$router->get('/admin/comments/hide/{id}', 'CommentAdminController@hide');
$router->get('/admin/comments/delete/{id}', 'CommentAdminController@delete');


/* RUTAS AUDITORÍA ADMIN */
$router->get('/admin/audit', 'AuditAdminController@index');


/* RUTAS PERFIL  */
$router->get('/profile', 'ProfileController@index');
$router->get('/editProfile', 'ProfileController@edit');
$router->post('/update', 'ProfileController@update');
$router->get('/sendPasswordOtp', 'ProfileController@sendPasswordOtp');
$router->get('/changePassword', 'ProfileController@changePassword');
$router->post('/changePassword', 'ProfileController@updatePassword');
$router->get('/invoiceDetail/{id}', 'ProfileController@invoiceDetail');
$router->post('/invoiceDetail/comment', 'ProfileController@commentProduct');


/* RUTAS AUTENTICACIÓN  */
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/forgot-password', 'AuthController@forgotPasswordForm');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password', 'AuthController@resetPasswordForm');
$router->post('/reset-password', 'AuthController@resetPassword');
$router->get('/logout', 'AuthController@logout');


/* RUTAS REGISTRO  */
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');


/* RUTAS VERIFICACIÓN OTP  */
$router->get('/verify-otp', 'AuthController@verifyOtpForm');
$router->post('/verify-otp', 'AuthController@verifyOtp');


/* RUTAS CATÁLOGO TIENDA */
$router->get('/products', 'ProductController@index');
$router->get('/tienda', 'ProductController@index');
$router->get('/product', 'ProductController@show');
$router->get('/tienda/product', 'ProductController@show');


/* RUTAS CARRITO */
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/clear', 'CartController@clear');


/* CUPONES API */
$router->post('/api/coupon/validate', 'CouponController@validate');
$router->post('/api/coupon/remove', 'CouponController@remove');


/* PAGOS  */
$router->post('/api/payment/capture', 'PaymentController@capture');


// Con todas las rutas declaradas, se resuelve la petición actual y se ejecuta su controlador.
$router->dispatch();
