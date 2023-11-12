<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->resource('ticket', ['controller' => 'TicketController', 'except' => ['new', 'edit']]);
$routes->resource('office', ['controller' => 'OfficeController', 'except' => ['new', 'edit']]);
$routes->resource('categories', ['controller' => 'CategoriesController', 'except' => ['new', 'edit']]);
$routes->resource('user', ['controller' => 'UserController', 'except' => ['new', 'edit']]);

// datatable routes
$routes->post('office/list', 'OfficeController::list');
$routes->post('categories/list', 'CategoriesController::list');
$routes->post('ticket/list', 'TicketController::list');
$routes->post('user/list', 'UserController::list');
// end of datatable routes

// dropdown data routes
$routes->post('office/officelist', 'OfficeController::showOffices');
$routes->post('categories/categorylist', 'CategoriesController::showCategories');
// end of dropdown data routes



// dashboard controller
$routes->post('dashboard/statistic', 'DashboardController::index');
service('auth')->routes($routes);

// API Routes
$routes->group("api", ["namespace" => "App\Controllers"], function ($routes) {
    $routes->get("invalid-access", "AuthController::accessDenied");
    $routes->post("register", "OAuthController::register");
    $routes->post("login", "OAuthController::login");
    $routes->get("oauth", "OAuthController::index");
});

