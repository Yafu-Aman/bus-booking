<?php

session_start();

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

$router = new Router();

// Auth routes
$router->get('home',      'AuthController', 'showHome');
$router->get('login',     'AuthController', 'showLogin');
$router->get('register',  'AuthController', 'showRegister');
$router->get('logout',    'AuthController', 'logout');
$router->post('login',    'AuthController', 'login');
$router->post('register', 'AuthController', 'register');
$router->get('contact',  'ContactController', 'showContact');
$router->post('contact', 'ContactController', 'handleContact');


// Passenger routes
$router->get('search',          'RouteController',  'showSearch');
$router->post('search',         'RouteController',  'handleSearch');
$router->get('seats',           'BookingController', 'showSeats');
$router->post('book',           'BookingController', 'handleBooking');
$router->get('dashboard',       'BookingController', 'passengerDashboard');
$router->get('dismiss-pending', 'BookingController', 'dismissPending');
$router->post('cancel-booking', 'BookingController', 'cancelBooking');

// Operator routes
$router->get('operator',        'OperatorController', 'dashboard');
$router->get('operator-add',    'OperatorController', 'showAddRoute');
$router->post('operator-add',   'OperatorController', 'handleAddRoute');
$router->get('operator-delete', 'OperatorController', 'deleteRoute');

// Admin routes
$router->get('admin',               'AdminController', 'dashboard');
$router->get('admin-users',         'AdminController', 'manageUsers');
$router->get('admin-suspend',       'AdminController', 'suspendUser');
$router->get('admin-activate',      'AdminController', 'activateUser');
$router->get('admin-routes',        'AdminController', 'manageRoutes');
$router->get('admin-approve-route', 'AdminController', 'approveRoute');
$router->get('admin-reject-route',  'AdminController', 'rejectRoute');
$router->get('admin-delete-route',  'AdminController', 'deleteRoute');
$router->get('admin-add-operator',  'AdminController', 'showAddOperator');
$router->post('admin-add-operator', 'AdminController', 'handleAddOperator');
$router->get('admin-messages', 'AdminController', 'manageMessages');

$router->dispatch();