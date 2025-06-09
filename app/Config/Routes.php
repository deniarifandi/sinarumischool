<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'auth'], function($routes) {
	$routes->get('/', 'Home::index');
	$routes->get('/blank', 'Home::blank');

	$routes->resource('murid');
	$routes->post('/murid/data', 'murid::data');

	$routes->get('/api/kelompok/getdata','tujuan::getdata');

	$routes->resource('kelompok');
	$routes->post('/kelompok/data', 'kelompok::data');

	$routes->resource('guru');
	$routes->post('/guru/data', 'guru::data');

	$routes->get('absensi','absensi::index');
	$routes->get('absensi/add','absensi::addAbsensi');
	$routes->get('absensi/edit/(:segment)', 'absensi::editAbsensi/$1');
	$routes->post('absensi','absensi::saveAbsensi');
	$routes->post('/absensi/data', 'absensi::data');

	$routes->resource('hari');
	$routes->post('/hari/data', 'hari::data');

	$routes->resource('modul');
	$routes->post('/modul/data', 'modul::data');

	$routes->resource('petakonsep');
	$routes->post('/petakonsep/data', 'petakonsep::data');

	$routes->resource('subjek');
	$routes->post('/subjek/data', 'subjek::data');

	$routes->resource('setting');
	$routes->post('/setting/data', 'setting::data');

	$routes->resource('tujuan');
	$routes->post('/tujuan/data', 'tujuan::data');

	$routes->resource('topik');
	$routes->post('/topik/data', 'topik::data');

	$routes->resource('subtopik');
	$routes->post('/subtopik/data', 'subtopik::data');


	$routes->resource('assignments');
	$routes->resource('students');
	$routes->post('/students/data', 'Students::data');
	$routes->post('/assignments/data', 'Assignments::data');
});
$routes->get('login','Home::login');
$routes->get('/login', 'Home::login');
$routes->post('/auth/loginauth', 'Home::loginAuth');
$routes->get('/logout', 'Home::logout');