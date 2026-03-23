<?php

require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/App.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');
if (function_exists('opcache_reset')) {
    opcache_reset();
}

Env::load(__DIR__ . '/../.env');
session_start();

$router = new Router();

// rutas
$router->get('/', 'HomeController@index');

$router->get('/admin', 'AdminController@index');
$router->get('/admin/products', 'AdminController@products');
$router->get('/admin/orders', 'AdminController@orders');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/inventory', 'AdminController@inventory');
$router->get('/admin/promotions', 'AdminController@promotions');

$router->get('/profile', 'ProfileController@index');

$router->get('/login', 'AuthController@loginForm');
$router->get('/logout', 'AuthController@logout');
$router->post('/login', 'AuthController@login');

$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');

$router->get('/verify-otp', 'AuthController@verifyOtpForm');
$router->post('/verify-otp', 'AuthController@verifyOtp');

$router->get('/products', 'ProductController@index');
$router->get('/tienda', 'ProductController@index');
$router->get('/product', 'ProductController@show');
$router->get('/tienda/product', 'ProductController@show');
$router->get('/cart', 'ProductController@cart');
$router->post('/cart/add', 'ProductController@addToCart');

$router->post('/cart/update', 'ProductController@updateCart');
$router->post('/cart/remove', 'ProductController@removeFromCart');
$router->post('/cart/clear', 'ProductController@clearCart');

// despachar (sin htaccess)
$router->dispatch();






