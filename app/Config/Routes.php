<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/blank', 'Home::blank');

$routes->resource('murid');
$routes->post('/murid/data', 'murid::data');

$routes->resource('kelompok');
$routes->post('/kelompok/data', 'kelompok::data');

$routes->resource('guru');
$routes->post('/guru/data', 'guru::data');

$routes->resource('petakonsep');
$routes->post('/petakonsep/data', 'petakonsep::data');

$routes->resource('assignments');
$routes->resource('students');
$routes->post('/students/data', 'Students::data');
$routes->post('/assignments/data', 'Assignments::data');