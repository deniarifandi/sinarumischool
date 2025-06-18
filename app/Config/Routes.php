<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'auth'], function($routes) {

	//home
	$routes->get('/', 'Home::index');
	$routes->get('/blank', 'Home::blank');

	//API
	$routes->get('/api/kelompok/getdata','tujuan::getdata');	

	//Report
	$routes->get('/Guru/print','Guru::print');
	$routes->get('/Kelompok/print','Kelompok::print');

	//Absensi
	$routes->get('absensi','absensi::index');
	$routes->get('absensi/add','absensi::addAbsensi');
	$routes->get('absensi/edit/(:segment)', 'absensi::editAbsensi/$1');
	$routes->get('absensi/delete/(:segment)', 'absensi::delete/$1');
	
	//Datatables
	$routes->post('absensi','absensi::saveAbsensi');
	$routes->post('/absensi/data', 'absensi::data');
	$routes->post('/hari/data', 'hari::data');
	$routes->post('/modul/data', 'modul::data');
	$routes->post('/petakonsep/data', 'petakonsep::data');
	$routes->post('/Subjek/data', 'Subjek::data');
	$routes->post('/setting/data', 'setting::data');
	$routes->post('/Tujuan/data', 'Tujuan::data');
	$routes->post('/topik/data', 'topik::data');
	$routes->post('/subtopik/data', 'subtopik::data');
	$routes->post('/Murid/data', 'Murid::data');
	$routes->post('/tingkat/data', 'tingkat::data');
	$routes->post('/Kelompok/data', 'Kelompok::data');
	$routes->post('/Guru/data', 'Guru::data');

	//Resource
	$routes->resource('Murid');
	$routes->resource('assignments');
	$routes->resource('students');
	$routes->resource('hari');
	$routes->resource('modul');
	$routes->resource('petakonsep');
	$routes->resource('Subjek');
	$routes->resource('setting');
	$routes->resource('Tujuan');
	$routes->resource('topik');
	$routes->resource('subtopik');
	$routes->resource('Tingkat');
	$routes->resource('Kelompok');
	$routes->resource('Guru');

	$routes->get('/logout', 'Home::logout');
});
$routes->get('login','Home::login');
$routes->get('/login', 'Home::login');
$routes->post('/auth/loginauth', 'Home::loginAuth');
