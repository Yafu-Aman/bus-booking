<?php

class BookingController extends Controller {

    private $bookingModel;
    private $routeModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Booking.php';
        require_once __DIR__ . '/../models/Route.php';
        $this->bookingModel = new Booking();
        $this->routeModel   = new Route();
    }

    public function passengerDashboard() {
    $this->requireRole('passenger');

    $userId = $_SESSION['user_id'];

    $bookings      = $this->bookingModel->getByUserId($userId);
    $pending       = $this->bookingModel->getPending($userId);
    $lastBooking   = $this->bookingModel->getLastBooking($userId);
    $mostTravelled = $this->bookingModel->getMostTravelledRoute($userId);
    $suggested     = $this->bookingModel->getSuggestedRoutes($userId);
    $stats         = $this->bookingModel->getPassengerStats($userId);

    $this->render('passenger/dashboard', [
        'bookings'      => $bookings,
        'pending'       => $pending,
        'lastBooking'   => $lastBooking,
        'mostTravelled' => $mostTravelled,
        'suggested'     => $suggested,
        'stats'         => $stats
    ]);
}

    public function showSeats() {
        $this->requireRole('passenger');

        $routeId = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;

        if ($routeId === 0) {
            $this->redirect('/bus-booking/public/index.php?page=search');
            return;
        }

        $route = $this->routeModel->findById($routeId);

        if (!$route) {
            $this->redirect('/bus-booking/public/index.php?page=search');
            return;
        }

        $bookedSeats = $this->bookingModel->getBookedSeats($routeId);

        $pricing = $this->bookingModel->calculatePrice(
            $route['price'],
            $route['departure_time']
        );

        $this->bookingModel->savePending($_SESSION['user_id'], $routeId);

        $this->render('passenger/seats', [
            'route'       => $route,
            'bookedSeats' => $bookedSeats,
            'totalSeats'  => 45,
            'finalPrice'  => $pricing['final_price'],
            'priceReason' => $pricing['reason']
        ]);
    }

    public function handleBooking() {
        $this->requireRole('passenger');

        $routeId    = isset($_POST['route_id'])    ? (int)$_POST['route_id']    : 0;
        $seatNumber = isset($_POST['seat_number']) ? (int)$_POST['seat_number'] : 0;
        $userId     = $_SESSION['user_id'];

        if ($routeId === 0 || $seatNumber === 0) {
            $this->redirect('/bus-booking/public/index.php?page=search');
            return;
        }

        $route       = $this->routeModel->findById($routeId);
        $bookedSeats = $this->bookingModel->getBookedSeats($routeId);

        // Validation 1 — same route already booked
        if ($this->bookingModel->hasSameRouteBooking($userId, $routeId)) {
            $this->render('passenger/seats', [
                'route'       => $route,
                'bookedSeats' => $bookedSeats,
                'totalSeats'  => 45,
                'finalPrice'  => $route['price'],
                'priceReason' => 'Standard price',
                'error'       => 'You already have a confirmed booking on this exact route. Please cancel your existing booking first.'
            ]);
            return;
        }

        // Validation 2 — same date conflict
        if ($this->bookingModel->hasConflict($userId, $routeId)) {
            $this->render('passenger/seats', [
                'route'       => $route,
                'bookedSeats' => $bookedSeats,
                'totalSeats'  => 45,
                'finalPrice'  => $route['price'],
                'priceReason' => 'Standard price',
                'error'       => 'You already have a booking on this travel date. Long distance buses take the full day — please cancel your existing booking first or choose a different date.'
            ]);
            return;
        }

        // Validation 3 — seat just taken
        if ($this->bookingModel->isSeatTaken($routeId, $seatNumber)) {
            $this->render('passenger/seats', [
                'route'       => $route,
                'bookedSeats' => $bookedSeats,
                'totalSeats'  => 45,
                'finalPrice'  => $route['price'],
                'priceReason' => 'Standard price',
                'error'       => 'Sorry, seat ' . $seatNumber . ' was just taken. Please choose another seat.'
            ]);
            return;
        }

        // Calculate dynamic price
        $pricing = $this->bookingModel->calculatePrice(
            $route['price'],
            $route['departure_time']
        );

        // Create booking
        $referenceCode = $this->bookingModel->create(
            $userId,
            $routeId,
            $seatNumber,
            $pricing['final_price']
        );

        if ($referenceCode) {
            $this->bookingModel->deletePending($userId);
            $this->render('passenger/confirmation', [
                'route'         => $route,
                'seatNumber'    => $seatNumber,
                'referenceCode' => $referenceCode,
                'finalPrice'    => $pricing['final_price'],
                'priceReason'   => $pricing['reason']
            ]);
        } else {
            $this->redirect('/bus-booking/public/index.php?page=search');
        }
    }

    public function dismissPending() {
        $this->requireRole('passenger');
        $this->bookingModel->deletePending($_SESSION['user_id']);
        $this->redirect('/bus-booking/public/index.php?page=dashboard');
    }

    public function cancelBooking() {
        $this->requireRole('passenger');

        $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
        $userId    = $_SESSION['user_id'];

        if ($bookingId > 0) {
            $this->bookingModel->cancel($bookingId, $userId);
        }

        $this->redirect('/bus-booking/public/index.php?page=dashboard');
    }
}