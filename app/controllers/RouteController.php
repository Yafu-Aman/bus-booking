<?php

class RouteController extends Controller {

    private $routeModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Route.php';
        $this->routeModel = new Route();
    }

    // Show search page with all upcoming routes by default
    public function showSearch() {
        $this->requireRole('passenger');

        // Get all cities for dropdowns
        $cities = $this->routeModel->getCities();

        // Get ALL upcoming routes to show by default
        $allRoutes = $this->routeModel->getUpcomingRoutes();

        $this->render('passenger/search', [
            'cities'    => $cities,
            'allRoutes' => $allRoutes,
            'results'   => [],
            'searched'  => false
        ]);
    }

    // Handle search form submission
    public function handleSearch() {
    $this->requireRole('passenger');

    $fromCity = trim($_POST['from_city'] ?? '');
    $toCity   = trim($_POST['to_city']   ?? '');
    $date     = trim($_POST['date']      ?? '');

    $cities    = $this->routeModel->getCities();
    $allRoutes = $this->routeModel->getUpcomingRoutes();

    if (empty($fromCity) || empty($toCity) || empty($date)) {
        $this->render('passenger/search', [
            'cities'    => $cities,
            'allRoutes' => $allRoutes,
            'results'   => [],
            'searched'  => false,
            'error'     => 'Please fill in all search fields.'
        ]);
        return;
    }

    $results = $this->routeModel->search($fromCity, $toCity, $date);

    $this->render('passenger/search', [
        'cities'    => $cities,
        'allRoutes' => $allRoutes,
        'results'   => $results,
        'searched'  => true,
        'fromCity'  => $fromCity,
        'toCity'    => $toCity,
        'date'      => $date
    ]);
}
}