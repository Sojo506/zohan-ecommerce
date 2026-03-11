<?php

require_once __DIR__ . '/../vendor/autoload.php';

/*  CARGA DE CLASES CORE  */

require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/App.php';


/*  CONFIGURACIÓN DE ERRORES  */

error_reporting(E_ALL);
ini_set('display_errors', 1);


/*  CARGAR VARIABLES DE ENTORNO  */

Env::load(__DIR__ . '/../.env');


/*  INICIAR SESIÓN  */

session_start();


/*  CREAR ROUTER  */

$router = new Router();


/*  RUTAS PRINCIPALES  */

$router->get('/', 'HomeController@index');


/*  RUTAS ADMIN  */

$router->get('/admin', 'AdminController@index');

$router->get('/admin/products', 'AdminController@products');
$router->get('/admin/orders', 'AdminController@orders');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/inventory', 'AdminController@inventory');
$router->get('/admin/promotions', 'AdminController@promotions');


/*  CRUD PRODUCTOS ADMIN  */

$router->get('/admin/products/list', 'ProductAdminController@index');
$router->get('/admin/products/create', 'ProductAdminController@createForm');
$router->get('/admin/products/edit/{id}', 'ProductAdminController@editForm');
$router->get('/admin/products/delete/{id}', 'ProductAdminController@delete');
$router->get('/admin/products/delete-image/{id}', 'ProductAdminController@deleteImage');

$router->post('/admin/products/create', 'ProductAdminController@create');
$router->post('/admin/products/upload-image', 'ProductAdminController@uploadImage');
$router->post('/admin/products/update', 'ProductAdminController@update');

/*  CRUD CATEGORÍAS ADMIN  */
$router->get('/admin/categories', 'CategoryAdminController@index');
$router->get('/admin/categories/create', 'CategoryAdminController@createForm');
$router->post('/admin/categories/create', 'CategoryAdminController@create');

$router->get('/admin/categories/edit/{id}', 'CategoryAdminController@editForm');
$router->post('/admin/categories/update', 'CategoryAdminController@update');

$router->get('/admin/categories/delete/{id}', 'CategoryAdminController@delete');

/* CRUD INVENTARIO ADMIN */
$router->get('/admin/inventory', 'InventoryAdminController@index');
$router->get('/admin/inventory/movement', 'InventoryAdminController@movementForm');
$router->get('/admin/inventory/movements', 'InventoryAdminController@movements');

$router->post('/admin/inventory/movement', 'InventoryAdminController@registerMovement');

/* CRUD VENTAS ADMIN */
$router->get('/admin/sales', 'SaleAdminController@index');

/* CRUD USUARIOS ADMIN  */
$router->get('/admin/users', 'UserAdminController@index');
$router->get('/admin/users/{id}', 'UserAdminController@detail');
$router->get('/admin/users/{id}/status/{status}', 'UserAdminController@changeStatus');
$router->get('/admin/users/{id}/role/{role}', 'UserAdminController@changeRole');
$router->get('/admin/sales/{id}', 'SaleAdminController@detail');

/* CRUD CUPONES ADMIN  */
$router->get('/admin/coupons', 'CouponAdminController@index');

$router->get('/admin/coupons/create', 'CouponAdminController@createForm');
$router->post('/admin/coupons/create', 'CouponAdminController@create');

$router->get('/admin/coupons/edit/{id}', 'CouponAdminController@editForm');
$router->post('/admin/coupons/update', 'CouponAdminController@update');

$router->get('/admin/coupons/delete/{id}', 'CouponAdminController@delete');

/* CRUD PROMOCIONES ADMIN  */
$router->get('/admin/promotions', 'PromotionAdminController@index');

$router->get('/admin/promotions/create', 'PromotionAdminController@createForm');
$router->post('/admin/promotions/create', 'PromotionAdminController@create');

$router->get('/admin/promotions/edit/{id}', 'PromotionAdminController@editForm');

$router->post('/admin/promotions/update', 'PromotionAdminController@update');

$router->get('/admin/promotions/delete/{id}', 'PromotionAdminController@delete');

$router->post('/admin/promotions/assign', 'PromotionAdminController@assignProduct');

$router->get(
    '/admin/promotions/remove-product/{promo}/{product}',
    'PromotionAdminController@removeProduct'
);

/* CRUD COMMENTS ADMIN  */
$router->get('/admin/comments', 'CommentAdminController@index');
$router->get('/admin/comments/approve/{id}', 'CommentAdminController@approve');
$router->get('/admin/comments/hide/{id}', 'CommentAdminController@hide');
$router->get('/admin/comments/delete/{id}', 'CommentAdminController@delete');

/*  PERFIL  */

$router->get('/profile', 'ProfileController@index');


/*  AUTENTICACIÓN  */

$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');


/*  REGISTRO  */

$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');


/*  VERIFICACIÓN OTP  */

$router->get('/verify-otp', 'AuthController@verifyOtpForm');
$router->post('/verify-otp', 'AuthController@verifyOtp');


/*  EJECUTAR ROUTER  */

$router->dispatch();
