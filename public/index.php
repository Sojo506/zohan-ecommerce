<?php

require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/App.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

Env::load(__DIR__ . '/../.env');
session_start();

$router = new Router();

// rutas
$router->get('/', 'HomeController@index');

$router->get('/login', 'AuthController@loginForm');
$router->get('/logout', 'AuthController@logout');
$router->post('/login', 'AuthController@login');

$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');

$router->get('/verify-otp', 'AuthController@verifyOtpForm');
$router->post('/verify-otp', 'AuthController@verifyOtp');

// despachar (sin htaccess)
$router->dispatch();
