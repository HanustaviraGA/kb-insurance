<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentication Routes
$routes->get('/', 'AuthController::index');
$routes->post('/auth/login', 'AuthController::login_aksi');
$routes->post('/auth/logout', 'AuthController::logout_aksi');

// Dashboard Routes
$routes->get('/dashboard', 'DashboardController::index');
$routes->get('/dashboard/(:any)', 'DashboardController::index');
$routes->post('/loadpage', 'DashboardController::load_page');

// Pertanggungan Routes
$routes->post('/pertanggungan/init_table', 'PertanggunganController::init_table');
$routes->post('/pertanggungan/create', 'PertanggunganController::create');
$routes->put('/pertanggungan/update', 'PertanggunganController::update');
$routes->delete('/pertanggungan/delete', 'PertanggunganController::delete');
