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

    $routes->group('grade', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'GradeController::index');
        $routes->get('create', 'GradeController::create');
        $routes->get('edit/(:num)', 'GradeController::edit/$1');
        $routes->post('save', 'GradeController::save');
        $routes->post('save/(:num)', 'GradeController::save/$1');
        $routes->post('delete/(:num)', 'GradeController::delete/$1');
    });

    $routes->group('subject', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'SubjectController::index');
        $routes->get('create', 'SubjectController::create');
        $routes->get('edit/(:num)', 'SubjectController::edit/$1');
        $routes->post('save', 'SubjectController::save');
        $routes->post('save/(:num)', 'SubjectController::save/$1');
        $routes->post('delete/(:num)', 'SubjectController::delete/$1');
    });


    $routes->group('student', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'StudentController::index');
        $routes->get('create', 'StudentController::create');
        $routes->get('edit/(:num)', 'StudentController::edit/$1');
        $routes->post('save', 'StudentController::save');
        $routes->post('save/(:num)', 'StudentController::save/$1');
        $routes->post('delete/(:num)', 'StudentController::delete/$1');

        $routes->get('attendance/list/class/(:num)', 'StudentController::attendanceList/$1');

        $routes->get('attendance/create/(:num)', 'StudentController::createAttendance/$1');
        $routes->get('student/attendance/edit/(:num)/(:segment)', 'StudentController::editAttendance/$1/$2');
        
        $routes->post('attendance/save', 'StudentController::simpan');

        $routes->get('attendance/detail/(:num)/(:segment)','StudentController::attendanceDetail/$1/$2');

    
    });

    $routes->group('absence', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'StudentAbsenceController::index');
        $routes->get('create', 'StudentAbsenceController::create');
        $routes->get('edit/(:num)', 'StudentAbsenceController::edit/$1');
        $routes->post('save', 'StudentAbsenceController::save');
        $routes->post('save/(:num)', 'StudentAbsenceController::save/$1');
        $routes->post('delete/(:num)', 'StudentAbsenceController::delete/$1');
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

        $routes->get('attendance', 'Presence::attendancePage');
        
    });

    $routes->post('attendance/data', 'Presence::attendanceData');

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

$routes->group('lessonplan', function($routes) {
    $routes->get('/', 'Lessonplan::index');
    $routes->get('(:num)', 'Lessonplan::show/$1');
    $routes->delete('(:num)', 'Lessonplan::delete/$1');

    $routes->get('create', 'Lessonplan::create');
    $routes->get('edit/(:num)', 'Lessonplan::edit/$1');
    $routes->post('store', 'Lessonplan::store');
    $routes->post('update/(:num)', 'Lessonplan::update/$1');

});
$routes->get('lessonplan/print/(:num)', 'Lessonplan::print/$1');

$routes->group('unit', function($routes) {
    $routes->get('/', 'Unit::index');
    $routes->get('create', 'Unit::create');
    $routes->get('edit/(:num)', 'Unit::edit/$1');

    $routes->post('store', 'Unit::store');
    $routes->post('update/(:num)', 'Unit::update/$1');
    $routes->post('delete/(:num)', 'Unit::delete/$1');
});

$routes->group('subunit', function($routes) {
    $routes->get('/', 'Subunit::index');
    $routes->get('create', 'Subunit::create');
    $routes->get('edit/(:num)', 'Subunit::edit/$1');

    $routes->post('store', 'Subunit::store');
    $routes->post('update/(:num)', 'Subunit::update/$1');
    $routes->post('delete/(:num)', 'Subunit::delete/$1');
});


$routes->group('outcome', function($routes) {
    $routes->get('/', 'Outcome::index');
    $routes->get('create', 'Outcome::create');
    $routes->get('edit/(:num)', 'Outcome::edit/$1');

    $routes->post('store', 'Outcome::store');
    $routes->post('update/(:num)', 'Outcome::update/$1');
    $routes->post('delete/(:num)', 'Outcome::delete/$1');
});

$routes->group('objective', function($routes) {
    $routes->get('/', 'Objective::index');
    $routes->get('create', 'Objective::create');
    $routes->get('edit/(:num)', 'Objective::edit/$1');

    $routes->post('store', 'Objective::store');
    $routes->post('update/(:num)', 'Objective::update/$1');
    $routes->post('delete/(:num)', 'Objective::delete/$1');
});



// routes.php
$routes->group('roles', function($routes) {
    $routes->get('/', 'Role::index');
    $routes->get('create', 'Role::create');
    $routes->get('edit/(:num)', 'Role::edit/$1');
    $routes->post('save', 'Role::save');
    $routes->post('save/(:num)', 'Role::save/$1');
    $routes->post('delete/(:num)', 'Role::delete/$1');
});

$routes->group('user-subject', function($routes) {
    $routes->get('/', 'UserSubject::index');
    $routes->get('create', 'UserSubject::create');   // show form
    $routes->post('store', 'UserSubject::store');
    $routes->post('delete/(:num)', 'UserSubject::delete/$1');
    $routes->get('assign/(:num)', 'UserSubject::assign/$1');
    $routes->post('store', 'UserSubject::store');
});


//REKAPP

$routes->group('rekap', ['filter' => 'auth'], function ($routes) {
      $routes->get('/', 'RekapController::index');

    $routes->get('create', 'RekapController::create');
    $routes->get('edit/(:num)', 'RekapController::edit/$1');

    $routes->post('save', 'RekapController::save');
    $routes->post('save/(:num)', 'RekapController::save/$1');

    $routes->get('print','RekapController::print');
});
