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
    $routes->group('products', ['filter' => 'role:admin,staff'], static function ($routes) {
        $routes->get('create', 'ProductsController::createForm');
        $routes->post('create', 'ProductsController::create');
        $routes->get('edit/(:num)', 'ProductsController::edit/$1');
        $routes->post('update/(:num)', 'ProductsController::update/$1');
    });
    $routes->group('products', ['filter' => 'role:admin'], static function ($routes) {
        $routes->post('delete/(:num)', 'ProductsController::delete/$1');
    });

    /*
    |--------------------------------------------------------------------------
    | Customer management
    |--------------------------------------------------------------------------
    */
    $routes->get('customers', 'CustomersController::index');
    $routes->group('customers', ['filter' => 'role:admin,staff'], static function ($routes) {
        $routes->get('create', 'CustomersController::createForm');
        $routes->post('create', 'CustomersController::create');
    });
    $routes->group('customers', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('edit/(:num)', 'CustomersController::edit/$1');
        $routes->post('update/(:num)', 'CustomersController::update/$1');
        $routes->post('delete/(:num)', 'CustomersController::delete/$1');
    });

    /*
    |--------------------------------------------------------------------------
    | Staff management
    |--------------------------------------------------------------------------
    */
    $routes->group('staff', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('/', 'UsersController::index');
        $routes->get('create', 'UsersController::createForm');
        $routes->post('create', 'UsersController::create');
        $routes->get('edit/(:num)', 'UsersController::edit/$1');
        $routes->post('update/(:num)', 'UsersController::update/$1');
        $routes->post('delete/(:num)', 'UsersController::delete/$1');
    });

    /*
    |--------------------------------------------------------------------------
    | Sales and sales history
    |--------------------------------------------------------------------------
    */
    $routes->get('sales', 'SalesController::history');
    $routes->get('sales/create', 'SalesController::create');
    $routes->post('sales/store', 'SalesController::store');
    $routes->get('sales/edit/(:num)', 'SalesController::edit/$1');
    $routes->post('sales/update/(:num)', 'SalesController::update/$1');
    $routes->get('sales/receipt/(:segment)', 'SalesController::receipt/$1');
    $routes->get('sales/history', 'SalesController::history');
});
