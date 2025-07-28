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
	$routes->get('/Unit/print','Unit::print');
	$routes->get('/Subunit/print','Subunit::print');

	//Absensi
	$routes->get('absensi','absensi::index');
	$routes->get('absensi/add','absensi::addAbsensi');
	$routes->get('absensi/edit/(:segment)', 'absensi::editAbsensi/$1');
	$routes->get('absensi/delete/(:segment)', 'absensi::delete/$1');
	
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
	$routes->resource('Unit');
	$routes->resource('Subunit');
	$routes->resource('Aktifitas');
	$routes->resource('Tipeaktifitas');
	$routes->resource('Divisi');
	$routes->resource('Jabatan');

	$routes->resource('Personel');
	$routes->resource('Presensidata');

	// $routes->resource('Gurudivisi');

	$routes->get('/logout', 'Home::logout');
});
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
	$routes->post('/Tingkat/data', 'Tingkat::data');
	$routes->post('/Kelompok/data', 'Kelompok::data');
	$routes->post('/Guru/data', 'Guru::data');
	$routes->post('/Unit/data', 'Unit::data');
	$routes->post('/Subunit/data', 'Subunit::data');
	$routes->post('/Aktifitas/data', 'Aktifitas::data');
	$routes->post('/Tipeaktifitas/data', 'Tipeaktifitas::data');
	$routes->post('/Divisi/data', 'Divisi::data');
	$routes->post('/Jabatan/data', 'Jabatan::data');
	$routes->post('/Presensidata/data', 'Presensidata::data');
	$routes->post('/Gurudivisi/data', 'Gurudivisi::data');

	$routes->post('/Personel/data', 'Personel::data');

	$routes->get('login','Home::login');
	$routes->get('/login', 'Home::login');
	$routes->post('/auth/loginauth', 'Home::loginAuth');

	//presensi
	$routes->get('/showform','Presensidata::showForm');
	$routes->get('/showstatus','Presensidata::showStatus');
	$routes->post('/savepresensi','Presensidata::savePresensi');
	$routes->get('/presensidatafront','Presensidata::front');
	$routes->get('/presensidatareport','Presensidata::report');
	$routes->get('/presensidataexcel','Presensidata::excel');

	//custom
	$routes->get('/Gurudivisi','Gurudivisi::index');
	$routes->get('/Gurudivisi/(:any)/edit','Gurudivisi::edit/$1');
	$routes->post('/Gurudivisi/submit','Gurudivisi::submit');
	$routes->post('/Gurudivisi/toggle', 'Gurudivisi::toggle');

	$routes->get('/Gurujabatan','Gurujabatan::index');
	$routes->get('/Gurujabatan/(:any)/edit','Gurujabatan::edit/$1');
	$routes->post('/Gurujabatan/submit','Gurujabatan::submit');
	$routes->post('/Gurujabatan/toggle', 'Gurujabatan::toggle');