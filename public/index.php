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

 = new Router();

// rutas
->get('/', 'HomeController@index');

->get('/admin', 'AdminController@index');
->get('/admin/products', 'AdminController@products');
->get('/admin/orders', 'AdminController@orders');
->get('/admin/users', 'AdminController@users');
->get('/admin/inventory', 'AdminController@inventory');
->get('/admin/promotions', 'AdminController@promotions');

->get('/profile', 'ProfileController@index');

->get('/login', 'AuthController@loginForm');
->get('/logout', 'AuthController@logout');
->post('/login', 'AuthController@login');

->get('/register', 'AuthController@registerForm');
->post('/register', 'AuthController@register');

->get('/verify-otp', 'AuthController@verifyOtpForm');
->post('/verify-otp', 'AuthController@verifyOtp');

->get('/products', 'ProductController@index');
->get('/product', 'ProductController@show');
->get('/cart', 'ProductController@cart');
->post('/cart/add', 'ProductController@addToCart');

->post('/cart/update', 'ProductController@updateCart');
->post('/cart/remove', 'ProductController@removeFromCart');
->post('/cart/clear', 'ProductController@clearCart');

// despachar (sin htaccess)
->dispatch();




