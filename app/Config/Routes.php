<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Auth::login');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::login');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('logout', 'Auth::logout');
});

// Admin routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('dashboard/getFilms', 'Admin\Dashboard::getFilms');
    $routes->get('dashboard/getUsers', 'Admin\Dashboard::getUsers');
    $routes->post('dashboard/addFilm', 'Admin\Dashboard::addFilm');
    $routes->delete('dashboard/deleteFilm/(:num)', 'Admin\Dashboard::deleteFilm/$1');
    $routes->delete('dashboard/deleteUser/(:num)', 'Admin\Dashboard::deleteUser/$1');
});

// User routes (akan dibuat nanti)
$routes->group('/', ['filter' => 'auth'], function($routes) {
    $routes->get('home', 'Home::index');
});