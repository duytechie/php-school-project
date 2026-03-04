<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../bootstrap.php';

define('APPNAME', 'CT275 Store');

session_start();

$router = new \Bramus\Router\Router();

// Auth routes
$router->post('/logout', '\App\Controllers\Auth\LoginController@destroy');
$router->get('/register', '\App\Controllers\Auth\RegisterController@create');
$router->post('/register', '\App\Controllers\Auth\RegisterController@store');
$router->get('/login', '\App\Controllers\Auth\LoginController@create');
$router->post('/login', '\App\Controllers\Auth\LoginController@store');

// About route
$router->get('/about', '\App\Controllers\AboutController@about');
// Products route
$router->get('/products', '\App\Controllers\ProductController@index');
// Index route
$router->get('/index', '\App\Controllers\IndexController@index');

// Cart routes
$router->get('/cart', '\App\Controllers\CartController@index');
$router->post('/cart/add', '\App\Controllers\CartController@add');
$router->post('/cart/update', '\App\Controllers\CartController@update');
$router->post('/cart/remove', '\App\Controllers\CartController@remove');
$router->get('/cart/clear', '\App\Controllers\CartController@clear');
$router->get('/cart/count', '\App\Controllers\CartController@count');
$router->get('/cart/checkout', '\App\Controllers\CartController@checkout');
$router->post('/cart/process-checkout', '\App\Controllers\CartController@processCheckout');

// Admin Product routes
$router->get('/admin/products', '\App\Controllers\Admin\ProductController@index');
$router->get('/admin/products/create', '\App\Controllers\Admin\ProductController@create');
$router->post('/admin/products', '\App\Controllers\Admin\ProductController@store');
$router->get('/admin/products/([a-zA-Z0-9]+)/edit', '\App\Controllers\Admin\ProductController@edit');
$router->post('/admin/products/([a-zA-Z0-9]+)/update', '\App\Controllers\Admin\ProductController@update');
$router->post('/admin/products/([a-zA-Z0-9]+)/delete', '\App\Controllers\Admin\ProductController@destroy');

// Admin User routes
$router->get('/admin/users', '\App\Controllers\Admin\UserController@index');
$router->get('/admin/users/create', '\App\Controllers\Admin\UserController@create');
$router->post('/admin/users/store', '\App\Controllers\Admin\UserController@store');
$router->get('/admin/users/([a-zA-Z0-9]+)/edit', '\App\Controllers\Admin\UserController@edit');
$router->post('/admin/users/([a-zA-Z0-9]+)/update', '\App\Controllers\Admin\UserController@update');
$router->post('/admin/users/([a-zA-Z0-9]+)/delete', '\App\Controllers\Admin\UserController@destroy');

// Admin Category routes
$router->get('/admin/categories', '\App\Controllers\Admin\CategoryController@index');
$router->get('/admin/categories/create', '\App\Controllers\Admin\CategoryController@create');
$router->post('/admin/categories/store', '\App\Controllers\Admin\CategoryController@store');
$router->get('/admin/categories/([a-zA-Z0-9]+)/edit', '\App\Controllers\Admin\CategoryController@edit');
$router->post('/admin/categories/([a-zA-Z0-9]+)/update', '\App\Controllers\Admin\CategoryController@update');
$router->post('/admin/categories/([a-zA-Z0-9]+)/delete', '\App\Controllers\Admin\CategoryController@destroy');

// Admin Statistics route
$router->get('/admin/statistics', '\App\Controllers\Admin\StatisticsController@index');

// Admin Dashboard route
$router->get('/admin', '\App\Controllers\Admin\AdminController@index');

// Home route - serve static HTML files
// $router->get('/', function () {

// Serve the main index.html
//     $viewsPath = ROOTDIR . 'app/views/';
//     if (file_exists($viewsPath . 'index.html')) {
//         readfile($viewsPath . 'index.html');
//     } else {
//         echo '<h1>Welcome to CT275 Store</h1>';
//         echo '<p><a href="/login">Login</a> | <a href="/register">Register</a></p>';
//     }
// });
// Home route - use dynamic IndexController
$router->get('/', '\\App\\Controllers\\IndexController@index');

// Static HTML pages route
$router->get('/views/{page}', function ($page) {
    $viewsPath = ROOTDIR . 'app/views/';
    $file = $viewsPath . $page;

    if (file_exists($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $contentTypes = [
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'otf' => 'font/otf'
        ];

        if (isset($contentTypes[$ext])) {
            header('Content-Type: ' . $contentTypes[$ext]);
        }

        readfile($file);
    } else {
        http_response_code(404);
        echo 'File not found';
    }
});

$router->run();
