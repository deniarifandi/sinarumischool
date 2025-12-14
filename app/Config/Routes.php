<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->group('', ['filter' => 'auth'], function($routes) {

// 	//home
// 	$routes->get('/', 'Home::index');
// 	$routes->get('/blank', 'Home::blank');

// 	//API
// 	$routes->get('/api/kelompok/getdata','tujuan::getdata');	

// 	//Report
// 	$routes->get('/Guru/print','Guru::print');
// 	$routes->get('/Kelompok/print','Kelompok::print');
// 	$routes->get('/Unit/print','Unit::print');
// 	$routes->get('/Subunit/print','Subunit::print');
// 	$routes->get('/Personel/print/(:num)', 'Personel::print/$1');
// 	$routes->get('/Aktifitas/print', 'Aktifitas::print');

// 	//Absensi
// 	$routes->get('absensi','absensi::index');
// 	$routes->get('absensi/add','absensi::addAbsensi');
// 	$routes->get('absensi/edit/(:segment)', 'absensi::editAbsensi/$1');
// 	$routes->get('absensi/delete/(:segment)', 'absensi::delete/$1');

// 	$routes->get('absensi/front','absensi::front');
// 	$routes->get('absensi/result','absensi::result');
	
// 	//Resource
// 	$routes->resource('Murid');
// 	$routes->resource('assignments');
// 	$routes->resource('students');
// 	$routes->resource('hari');
// 	$routes->resource('modul');
// 	$routes->resource('petakonsep');
// 	$routes->resource('Subjek');
// 	$routes->resource('setting');
// 	$routes->resource('Tujuan');
// 	$routes->resource('topik');
// 	$routes->resource('subtopik');
// 	$routes->resource('Tingkat');
// 	$routes->resource('Kelompok');
// 	$routes->resource('Guru');
// 	$routes->resource('Unit');
// 	$routes->resource('Subunit');
// 	$routes->resource('Aktifitas');
// 	$routes->resource('Tipeaktifitas');
// 	$routes->resource('Divisi');
// 	$routes->resource('Jabatan');

// 	$routes->resource('Personel');
// 	$routes->resource('Presensidata');

// 	// $routes->resource('Gurudivisi');

// 	$routes->get('/logout', 'Home::logout');
// });
// //Datatables
// 	$routes->post('absensi','absensi::saveAbsensi');
// 	$routes->post('/absensi/data', 'absensi::data');
// 	$routes->post('/hari/data', 'hari::data');
// 	$routes->post('/modul/data', 'modul::data');
// 	$routes->post('/petakonsep/data', 'petakonsep::data');
// 	$routes->post('/Subjek/data', 'Subjek::data');
// 	$routes->post('/setting/data', 'setting::data');
// 	$routes->post('/Tujuan/data', 'Tujuan::data');
// 	$routes->post('/topik/data', 'topik::data');
// 	$routes->post('/subtopik/data', 'subtopik::data');
// 	$routes->post('/Murid/data', 'Murid::data');
// 	$routes->post('/Tingkat/data', 'Tingkat::data');
// 	$routes->post('/Kelompok/data', 'Kelompok::data');
// 	$routes->post('/Guru/data', 'Guru::data');
// 	$routes->post('/Unit/data', 'Unit::data');
// 	$routes->post('/Subunit/data', 'Subunit::data');
// 	$routes->post('/Aktifitas/data', 'Aktifitas::data');
// 	$routes->post('/Tipeaktifitas/data', 'Tipeaktifitas::data');
// 	$routes->post('/Divisi/data', 'Divisi::data');
// 	$routes->post('/Jabatan/data', 'Jabatan::data');
// 	$routes->post('/Presensidata/data', 'Presensidata::data');
// 	$routes->post('/Gurudivisi/data', 'Gurudivisi::data');

// 	$routes->post('/Personel/data', 'Personel::data');


// 	$routes->get('login','Home::login');
// 	$routes->get('/login', 'Home::login');
// 	$routes->post('/auth/loginauth', 'Home::loginAuth');

// 	//presensi
// 	$routes->get('/showform','Presensidata::showForm');
// 	$routes->get('/getname','Presensidata::getName');
// 	$routes->get('/showstatus','Presensidata::showStatus');
// 	$routes->post('/savepresensi','Presensidata::savePresensi');

// 	$routes->get('/presensidata/editstatus/(:num)','Presensidata::editStatus/$1');
// 	$routes->post('/presensidata/updatestatus', 'Presensidata::updateStatus');
	
// 	$routes->post('/savepresensidirect','Presensidata::savePresensiDirect');

// 	$routes->get('/presensidatafront','Presensidata::front');
// 	$routes->get('/presensidatareport','Presensidata::report');
// 	$routes->get('/presensidataexcel','Presensidata::excel');

// 	$routes->get('/showformscanner',function(){
// 		 return view('presence/formscanner');
// 	});

// 	//custom
// 	$routes->get('/Gurudivisi','Gurudivisi::index');
// 	$routes->get('/Gurudivisi/(:any)/edit','Gurudivisi::edit/$1');
// 	$routes->post('/Gurudivisi/submit','Gurudivisi::submit');
// 	$routes->post('/Gurudivisi/toggle', 'Gurudivisi::toggle');

// 	$routes->get('/Gurujabatan','Gurujabatan::index');
// 	$routes->get('/Gurujabatan/(:any)/edit','Gurujabatan::edit/$1');
// 	$routes->post('/Gurujabatan/submit','Gurujabatan::submit');
// 	$routes->post('/Gurujabatan/toggle', 'Gurujabatan::toggle');

// 	$routes->post('/api/storeattendance/(:any)','Presensidata::storeAttendance/$1');

//EVOLUTION X

	//$routes->get('/', 'AuthController::index');

	$routes->get('/', 'Redirector::index');

	$routes->get('/login', 'AuthController::index');
	$routes->post('/auth/login', 'AuthController::login');
	$routes->get('/logout', 'AuthController::logout');

	$routes->get('/no-access', function() {
	    echo "You don't have permission.";
	});

	// ADMIN
	$routes->group('admin', ['filter' => 'role:admin'], function($routes){
	    $routes->get('dashboard', 'Admin\Dashboard::index');
	});

	// GURU
	$routes->group('guru', ['filter' => 'role:guru'], function($routes){
	    $routes->get('dashboard', 'Guru\Dashboard::index');
	});

	// SISWA
	$routes->group('siswa', ['filter' => 'role:siswa'], function($routes){
	    $routes->get('dashboard', 'Siswa\Dashboard::index');
	});

	//Division Management
	$routes->group('admin', ['filter' => 'role:admin'], function($routes){
	   // Division Management
		$routes->get('divisions', 'Admin\Divisions::index');
		$routes->get('divisions/create', 'Admin\Divisions::create');
		$routes->post('divisions/store', 'Admin\Divisions::store');
		$routes->get('divisions/edit/(:num)', 'Admin\Divisions::edit/$1');
		$routes->post('divisions/update/(:num)', 'Admin\Divisions::update/$1');
		$routes->get('divisions/delete/(:num)', 'Admin\Divisions::delete/$1');

	});


	//USER MANAGEMENT
	$routes->group('admin', ['filter' => 'role:admin'], function($routes){
	    $routes->get('users', 'Admin\User::index');
	    $routes->get('users/create', 'Admin\User::create');
	    $routes->post('users/store', 'Admin\User::store');
	    $routes->get('users/edit/(:num)', 'Admin\User::edit/$1');
	    $routes->post('users/update/(:num)', 'Admin\User::update/$1');
	    $routes->get('users/delete/(:num)', 'Admin\User::delete/$1');
	});

	$routes->post('admin/users/datatable', 'Admin\User::datatable');

	// Student Management
	$routes->group('admin', ['filter' => 'role:admin'], function($routes){
	    $routes->get('students', 'Admin\Students::index');
	    $routes->get('students/create', 'Admin\Students::create');
	    $routes->post('students/store', 'Admin\Students::store');
	    $routes->get('students/edit/(:num)', 'Admin\Students::edit/$1');
	    $routes->post('students/update/(:num)', 'Admin\Students::update/$1');
	    $routes->get('students/delete/(:num)', 'Admin\Students::delete/$1');
	});

	// Class Management
	$routes->group('admin', ['filter' => 'role:admin'], function($routes){

	    $routes->get('classes', 'Admin\Classes::index');
	    $routes->get('classes/create', 'Admin\Classes::create');
	    $routes->post('classes/store', 'Admin\Classes::store');
	    $routes->get('classes/edit/(:num)', 'Admin\Classes::edit/$1');
	    $routes->post('classes/update/(:num)', 'Admin\Classes::update/$1');
	    $routes->get('classes/delete/(:num)', 'Admin\Classes::delete/$1');

	});

	$routes->post('admin/classes/datatable', 'Admin\Classes::datatable');

$routes->group('admin', ['filter' => 'role:admin,guru'], function($routes){

    // Subject Management
    $routes->get('subjects', 'Admin\Subjects::index');
    $routes->get('subjects/create', 'Admin\Subjects::create');
    $routes->post('subjects/store', 'Admin\Subjects::store');
    $routes->get('subjects/edit/(:num)', 'Admin\Subjects::edit/$1');
    $routes->post('subjects/update/(:num)', 'Admin\Subjects::update/$1');
    $routes->get('subjects/delete/(:num)', 'Admin\Subjects::delete/$1');


});


$routes->group('admin', ['filter' => 'role:admin,guru'], function($routes){

    // Subject Management
  // Chapter Management
	$routes->get('chapters/(:num)', 'Admin\Chapters::index/$1');   // list chapters for subject
	$routes->get('chapters/create/(:num)', 'Admin\Chapters::create/$1');
	$routes->post('chapters/store', 'Admin\Chapters::store');
	$routes->get('chapters/edit/(:num)', 'Admin\Chapters::edit/$1');
	$routes->post('chapters/update/(:num)', 'Admin\Chapters::update/$1');
	$routes->get('chapters/delete/(:num)', 'Admin\Chapters::delete/$1');

	// Sub-Chapter Management
	$routes->get('subchapters/(:num)', 'Admin\Subchapters::index/$1');   // list subchapters per chapter
	$routes->get('subchapters/create/(:num)', 'Admin\Subchapters::create/$1');
	$routes->post('subchapters/store', 'Admin\Subchapters::store');
	$routes->get('subchapters/edit/(:num)', 'Admin\Subchapters::edit/$1');
	$routes->post('subchapters/update/(:num)', 'Admin\Subchapters::update/$1');
	$routes->get('subchapters/delete/(:num)', 'Admin\Subchapters::delete/$1');

	// Lesson Objective Management
	$routes->get('objectives/(:num)', 'Admin\Objectives::index/$1');        // list per subchapter
	$routes->get('objectives/create/(:num)', 'Admin\Objectives::create/$1');
	$routes->post('objectives/store', 'Admin\Objectives::store');
	$routes->get('objectives/edit/(:num)', 'Admin\Objectives::edit/$1');
	$routes->post('objectives/update/(:num)', 'Admin\Objectives::update/$1');
	$routes->get('objectives/delete/(:num)', 'Admin\Objectives::delete/$1');
   
});

// Teaching Journal
$routes->group('journal', ['filter' => 'role:admin,guru'], function($routes){

    $routes->get('/', 'Journal::index');
    $routes->get('create', 'Journal::create');
    $routes->post('store', 'Journal::store');

    $routes->get('edit/(:num)', 'Journal::edit/$1');
    $routes->post('update/(:num)', 'Journal::update/$1');

    $routes->get('delete/(:num)', 'Journal::delete/$1');

});

    $routes->get('ajax/chapters/(:num)', 'Ajax::getChapters/$1');
	$routes->get('ajax/subchapters/(:num)', 'Ajax::getSubchapters/$1');
	$routes->get('ajax/objectives/(:num)', 'Ajax::getObjectives/$1');

	// Gradebook
$routes->group('gradebook', ['filter' => 'role:admin,guru'], function($routes){
    $routes->get('/', 'Gradebook::index');
    $routes->get('input', 'Gradebook::input');
    $routes->post('save', 'Gradebook::save');
});

	$routes->get('ajax/students/(:num)', 'Ajax::getStudents/$1');

	