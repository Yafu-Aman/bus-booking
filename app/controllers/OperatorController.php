<?php

class OperatorController extends Controller {

    private $routeModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Route.php';
        $this->routeModel = new Route();
    }

    public function dashboard() {
        $this->requireRole('operator');
        $operatorId = $_SESSION['user_id'];
        $routes     = $this->routeModel->getByOperator($operatorId);
        $this->render('operator/dashboard', [
            'routes' => $routes
        ]);
    }

    public function showAddRoute() {
        $this->requireRole('operator');
        $cities = $this->routeModel->getCities();
        $this->render('operator/add_route', [
            'cities' => $cities
        ]);
    }

    public function handleAddRoute() {
        $this->requireRole('operator');

        $operatorId = $_SESSION['user_id'];
        $cities     = $this->routeModel->getCities();

        // Use typed city if dropdown not selected
        $fromCity = trim($_POST['from_city']     ?? '');
        $toCity   = trim($_POST['to_city']       ?? '');
        $fromNew  = trim($_POST['from_city_new'] ?? '');
        $toNew    = trim($_POST['to_city_new']   ?? '');

        if (empty($fromCity) && !empty($fromNew)) {
            $fromCity = $fromNew;
        }
        if (empty($toCity) && !empty($toNew)) {
            $toCity = $toNew;
        }

        $departureTime = trim($_POST['departure_time'] ?? '');
        $price         = trim($_POST['price']          ?? '');

        if (empty($fromCity) || empty($toCity) ||
            empty($departureTime) || empty($price)) {
            $this->render('operator/add_route', [
                'cities' => $cities,
                'error'  => 'Please fill in all fields.'
            ]);
            return;
        }

        if (strtolower($fromCity) === strtolower($toCity)) {
            $this->render('operator/add_route', [
                'cities' => $cities,
                'error'  => 'Departure and destination cities cannot be the same.'
            ]);
            return;
        }

        if (!is_numeric($price) || (float)$price <= 0) {
            $this->render('operator/add_route', [
                'cities' => $cities,
                'error'  => 'Please enter a valid price greater than zero.'
            ]);
            return;
        }

        if (strtotime($departureTime) <= time()) {
            $this->render('operator/add_route', [
                'cities' => $cities,
                'error'  => 'Departure time must be in the future.'
            ]);
            return;
        }

        if ($this->routeModel->isDuplicateOnDate($fromCity, $toCity, $departureTime)) {
            $this->render('operator/add_route', [
                'cities' => $cities,
                'error'  => 'A route from ' . htmlspecialchars($fromCity) .
                            ' to ' . htmlspecialchars($toCity) .
                            ' already exists on this date. Only one departure per route per day is allowed.'
            ]);
            return;
        }

        $success = $this->routeModel->create(
            $fromCity, $toCity, $departureTime,
            (float)$price, $operatorId
        );

        if ($success) {
            $routes = $this->routeModel->getByOperator($operatorId);
            $this->render('operator/dashboard', [
                'routes'  => $routes,
                'success' => 'Route submitted successfully! It will appear in search once approved by admin.'
            ]);
        } else {
            $this->render('operator/add_route', [
                'cities' => $cities,
                'error'  => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function deleteRoute() {
        $this->requireRole('operator');

        $routeId    = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;
        $operatorId = $_SESSION['user_id'];

        if ($routeId === 0) {
            $this->redirect('/bus-booking/public/index.php?page=operator');
            return;
        }

        $result = $this->routeModel->delete($routeId, $operatorId);

        if ($result === 'has_bookings') {
            $routes = $this->routeModel->getByOperator($operatorId);
            $this->render('operator/dashboard', [
                'routes' => $routes,
                'error'  => 'This route cannot be deleted because passengers have already booked seats on it. Contact the admin if you need to remove it.'
            ]);
        } else {
            $this->redirect('/bus-booking/public/index.php?page=operator');
        }
    }
}