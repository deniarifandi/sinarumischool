<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'Auth::login');
$routes->post('auth/loginauth', 'Auth::loginAuth');
$routes->get('logout', 'Auth::logout');

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
    $routes->get('presence/full_report/(:num)/(:num)', 'Presence::full_report/$1/$2');

});

