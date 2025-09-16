<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->setDefaultNamespace('App\Controllers');
// $routes->setDefaultController('Dashboard');
// $routes->setDefaultMethod('index');
// $routes->setTranslateURIDashes(false);
// $routes->set404Override();
 $routes->setAutoRoute(true);

$routes->get('/', 'Dashboard::index');
$routes->get('/login', 'Login::index');
$routes->get('/detalhada', 'Detalhada');
$routes->get('/admissoes', 'Admissoes');
