<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::new');
$routes->get('logout', 'Login::logout');
$routes->get('forgot', 'Password::forgot');
