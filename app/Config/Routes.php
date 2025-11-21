<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::login');

$routes->group('auth', function($routes) {
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::processRegister');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::processLogin');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('kelolafilm', 'Admin::kelolafilm');
    $routes->get('kelolauser', 'Admin::kelolauser');
    $routes->post('tambah-film', 'Admin::tambahFilm');
    $routes->get('delete-film/(:num)', 'Admin::deleteFilm/$1');
    $routes->get('delete-user/(:num)', 'Admin::deleteUser/$1');
});

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Home::dashboard');
    $routes->get('film/(:num)', 'Home::watchFilm/$1');
});