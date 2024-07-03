<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 // 게시판
$routes->get('/', 'Home::index');
$routes->get('/board', 'Board::list');
$routes->get('/boardWrite', 'Board::write');
$routes->match(['get','post'], 'writeSave', 'Board::save');
$routes->get('/boardView/(:num)', 'Board::view/$1');
$routes->get('/modify/(:num)', 'Board::modify/$1');
$routes->get('/delete/(:num)', 'Board::delete/$1');
$routes->post('/save_image', 'Board::save_image');
$routes->post('/file_delete', 'Board::file_delete');

// 댓글
$routes->match(['get','post'], '/memo_write', 'MemoController::memo_write');
$routes->post('/save_image_memo', 'MemoController::save_image_memo');
$routes->post('/memo_file_delete', 'MemoController::memo_file_delete');
$routes->post('/memo_delete', 'MemoController::memo_delete');
$routes->post('/memo_modify', 'MemoController::memo_modify');
$routes->post('/memo_modify_update', 'MemoController::memo_modify_update');

// member
$routes->get('/login', 'MemberController::login');
$routes->get('/logout', 'MemberController::logout');
$routes->match(['get','post'], '/loginok', 'MemberController::loginok');