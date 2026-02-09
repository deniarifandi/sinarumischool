<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

    $routes->get('login', 'Auth::login');
    $routes->post('auth/loginauth', 'Auth::loginAuth');
    $routes->get('logout', 'Auth::logout');

    $routes->group('division', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'DivisionController::index');
        $routes->get('create', 'DivisionController::create');
        $routes->get('edit/(:num)', 'DivisionController::edit/$1');
        $routes->post('save', 'DivisionController::save');
        $routes->post('save/(:num)', 'DivisionController::save/$1'); // <-- ADD THIS
        $routes->post('delete/(:num)', 'DivisionController::delete/$1');
    });

    $routes->group('users', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'UserController::index');
        $routes->get('create', 'UserController::create');
        $routes->get('edit/(:num)', 'UserController::edit/$1');
        $routes->post('save', 'UserController::save');
        $routes->post('save/(:num)', 'UserController::save/$1');
        $routes->post('delete/(:num)', 'UserController::delete/$1');

        //roles
        $routes->get('role/(:num)', 'UserController::editRole/$1');
        $routes->post('role/(:num)', 'UserController::updateRole/$1');
        //divisions
        $routes->get('division/(:num)', 'UserController::editDivision/$1');
        $routes->post('division/(:num)', 'UserController::updateDivision/$1');
    });



    $routes->group('', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'Home::index');

        //Profile
        $routes->get('profile', 'Profile::index');
        $routes->get('profile/edit', 'Profile::form');
        $routes->post('profile/update', 'Profile::update');
        $routes->get('profile/security', 'Profile::security');
        $routes->post('profile/change-password', 'Profile::changePassword');

        //Presence
        $routes->get('presence', 'Presence::index');
        $routes->post('presence/checkin', 'Presence::checkIn');

        //report
        $routes->get('presence/full_report/(:num)/(:num)', 'Presence::full_report/$1/$2');
    });

    $routes->group('grade', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'GradeController::index');
        $routes->get('create', 'GradeController::create');
        $routes->get('edit/(:num)', 'GradeController::edit/$1');
        $routes->post('save', 'GradeController::save');
        $routes->post('save/(:num)', 'GradeController::save/$1');
        $routes->post('delete/(:num)', 'GradeController::delete/$1');
    });



    $routes->group('class', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'ClassController::index');
        $routes->get('create', 'ClassController::create');
        $routes->get('edit/(:num)', 'ClassController::edit/$1');
        $routes->post('save', 'ClassController::save');
        $routes->post('save/(:num)', 'ClassController::save/$1');
        $routes->post('delete/(:num)', 'ClassController::delete/$1');
    });



    // Grades


    //CSI
    //socioemotional
    // app/Config/Routes.php

// app/Config/Routes.php

$routes->group('socioreport', ['filter' => 'auth'], function ($routes) {

    // index (grouped by class + month)
    $routes->get('/', 'SocioReportController::index');

    // import CSV (by class + month + year)
    $routes->post('import', 'SocioReportController::import');

    // print report (per class + month)
    // example: /socioreport/print/3/2026-03
    $routes->get('print/(:num)/(:segment)', 'SocioReportController::print/$1/$2');

    // delete report (per class + month)
    // example: /socioreport/delete/3/2026-03
    $routes->post('delete/(:num)/(:segment)', 'SocioReportController::deletePeriod/$1/$2');
});


