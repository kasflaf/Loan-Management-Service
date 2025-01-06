<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// In app/Config/Routes.php
$routes->get('/testdb', 'TestController::checkDbConnection');
$routes->get('/', 'Home::index');
