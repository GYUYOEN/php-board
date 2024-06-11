<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/board', 'Board::list');
$routes->get('/boardWrite', 'Board::write');
$routes->match(['get','post'], 'writeSave', 'Board::save');
$routes->get('/boardView/(:num)', 'Board::view/$1');
$routes->get('/modify/(:num)', 'Board::modify/$1');
$routes->get('/delete/(:num)', 'Board::delete/$1');

$routes->get('/login', 'MemberController::login');
$routes->get('/logout', 'MemberController::logout');
$routes->match(['get','post'], '/loginok', 'MemberController::loginok');