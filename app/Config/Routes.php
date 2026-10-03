<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

$routes->get('/', static function () {
    return redirect()->to('/login');
});

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

/*
|--------------------------------------------------------------------------
| Protected routes
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'auth'], static function ($routes) {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    $routes->get('dashboard', 'DashboardController::index');

    /*
    |--------------------------------------------------------------------------
    | Product management
    |--------------------------------------------------------------------------
    */
    $routes->get('products', 'ProductsController::index');
    $routes->get('products/create', 'ProductsController::createForm');
    $routes->post('products/create', 'ProductsController::create');
    $routes->get('products/edit/(:num)', 'ProductsController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductsController::update/$1');
    $routes->post('products/delete/(:num)', 'ProductsController::delete/$1');

    /*
    |--------------------------------------------------------------------------
    | Customer management
    |--------------------------------------------------------------------------
    */
    $routes->get('customers', 'CustomersController::index');
    $routes->get('customers/create', 'CustomersController::createForm');
    $routes->post('customers/create', 'CustomersController::create');
    $routes->get('customers/edit/(:num)', 'CustomersController::edit/$1');
    $routes->post('customers/update/(:num)', 'CustomersController::update/$1');
    $routes->post('customers/delete/(:num)', 'CustomersController::delete/$1');

    /*
    |--------------------------------------------------------------------------
    | Staff management
    |--------------------------------------------------------------------------
    */
    $routes->get('staff', 'UsersController::index');
    $routes->get('staff/create', 'UsersController::createForm');
    $routes->post('staff/create', 'UsersController::create');
    $routes->get('staff/edit/(:num)', 'UsersController::edit/$1');
    $routes->post('staff/update/(:num)', 'UsersController::update/$1');
    $routes->post('staff/delete/(:num)', 'UsersController::delete/$1');

    /*
    |--------------------------------------------------------------------------
    | Sales and sales history
    |--------------------------------------------------------------------------
    */
    $routes->get('sales', 'SalesController::history');
    $routes->get('sales/create', 'SalesController::create');
    $routes->post('sales/store', 'SalesController::store');
    $routes->get('sales/history', 'SalesController::history');
});