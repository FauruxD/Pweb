<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 🟡 Landing Page Movix
$routes->get('/', 'Landing::index');

// 🟡 Akses LandingPages
$routes->get('landingpages', 'LandingPages::index');
$routes->get('landingpages/index', 'LandingPages::index');

// 🟢 Auth
$routes->group('auth', function($routes) {
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::processRegister');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::processLogin');
    $routes->get('logout', 'Auth::logout');
});

// 🔵 Admin Panel
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('kelolafilm', 'Admin::kelolafilm');
    $routes->get('kelolauser', 'Admin::kelolauser');
    $routes->post('tambah-film', 'Admin::tambahFilm');
    $routes->get('delete-film/(:num)', 'Admin::deleteFilm/$1');
    $routes->get('delete-user/(:num)', 'Admin::deleteUser/$1');
});

// 🔴 User / Home
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Home::dashboard');
    $routes->get('film/(:num)', 'Home::watchFilm/$1'); // halaman nonton
    $routes->get('genre', 'Genre::index');
    $routes->get('genre/(:any)', 'Genre::show/$1');
});

// 🟢 FAVORITE (TANPA DATABASE, JSON)
$routes->get('favorite', 'Favorite::index');       // halaman favorit
$routes->post('favorite/toggle', 'Favorite::toggle'); // tambah/hapus favorit (AJAX)
$routes->get('favorite', 'Favorite::index', ['filter' => 'auth']);

$routes->get('/watch/(:any)', 'Home::watch/$1');
$routes->get('/detail/(:num)', 'Home::detail/$1');
$routes->get('/detail/(:num)', 'Home::detail/$1');
$routes->get('/detail_tmdb/(:num)', 'Home::detailTmdb/$1');




