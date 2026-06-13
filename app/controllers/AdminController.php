<?php

class AdminController extends Controller {

    private $userModel;
    private $routeModel;
    private $bookingModel;

    public function __construct() {
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Route.php';
        require_once __DIR__ . '/../models/Booking.php';
        $this->userModel    = new User();
        $this->routeModel   = new Route();
        $this->bookingModel = new Booking();
    }

    public function dashboard() {
    $this->requireRole('admin');

    $counts = $this->userModel->getCounts();

    $db     = Database::getInstance()->getConnection();

    $result = $db->query("
        SELECT
            COUNT(CASE WHEN status = 'confirmed' THEN 1 END) AS total_confirmed,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) AS total_cancelled
        FROM bookings
    ");
    $bookingCounts = $result->fetch_assoc();

    $result     = $db->query(
        "SELECT COUNT(*) AS total FROM routes WHERE approval_status = 'approved'"
    );
    $routeCount = $result->fetch_assoc();

    $result       = $db->query(
        "SELECT COUNT(*) AS total FROM routes WHERE approval_status = 'pending'"
    );
    $pendingCount = $result->fetch_assoc();

    $result         = $db->query(
        "SELECT COUNT(*) AS total FROM contact_messages WHERE is_read = 0"
    );
    $unreadMessages = $result->fetch_assoc();

    $this->render('admin/dashboard', [
        'totalPassengers' => $counts['total_passengers'],
        'totalOperators'  => $counts['total_operators'],
        'totalConfirmed'  => $bookingCounts['total_confirmed'],
        'totalCancelled'  => $bookingCounts['total_cancelled'],
        'totalRoutes'     => $routeCount['total'],
        'pendingRoutes'   => $pendingCount['total'],
        'unreadMessages'  => $unreadMessages['total']
    ]);
}

    public function manageUsers() {
        $this->requireRole('admin');
        $users = $this->userModel->getAllUsers();
        $this->render('admin/users', [
            'users' => $users
        ]);
    }

    public function suspendUser() {
        $this->requireRole('admin');
        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
        if ($userId > 0) {
            $this->userModel->suspend($userId);
        }
        $this->redirect('/bus-booking/public/index.php?page=admin-users');
    }

    public function activateUser() {
        $this->requireRole('admin');
        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
        if ($userId > 0) {
            $this->userModel->activate($userId);
        }
        $this->redirect('/bus-booking/public/index.php?page=admin-users');
    }

    public function manageRoutes() {
        $this->requireRole('admin');
        $routes        = $this->routeModel->getAllRoutes();
        $pendingRoutes = $this->routeModel->getPendingRoutes();
        $this->render('admin/routes', [
            'routes'        => $routes,
            'pendingRoutes' => $pendingRoutes
        ]);
    }

    public function approveRoute() {
        $this->requireRole('admin');
        $routeId = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;
        if ($routeId > 0) {
            $this->routeModel->approve($routeId);
        }
        $this->redirect('/bus-booking/public/index.php?page=admin-routes');
    }

    public function rejectRoute() {
        $this->requireRole('admin');
        $routeId = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;
        if ($routeId > 0) {
            $this->routeModel->reject($routeId);
        }
        $this->redirect('/bus-booking/public/index.php?page=admin-routes');
    }

    public function deleteRoute() {
    $this->requireRole('admin');
    $routeId = isset($_GET['route_id']) ? (int)$_GET['route_id'] : 0;

    if ($routeId > 0) {
        $db = Database::getInstance()->getConnection();

        // First delete all bookings linked to this route
        $stmt = $db->prepare("DELETE FROM bookings WHERE route_id = ?");
        $stmt->bind_param('i', $routeId);
        $stmt->execute();

        // Also delete pending bookings linked to this route
        $stmt = $db->prepare("DELETE FROM pending_bookings WHERE route_id = ?");
        $stmt->bind_param('i', $routeId);
        $stmt->execute();

        // Now safe to delete the route
        $stmt = $db->prepare("DELETE FROM routes WHERE id = ?");
        $stmt->bind_param('i', $routeId);
        $stmt->execute();
    }

    $this->redirect('/bus-booking/public/index.php?page=admin-routes');
}

    public function showAddOperator() {
        $this->requireRole('admin');
        $this->render('admin/add_operator');
    }

    public function handleAddOperator() {
        $this->requireRole('admin');

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email']     ?? '');
        $password = trim($_POST['password']  ?? '');

        if (empty($fullName) || empty($email) || empty($password)) {
            $this->render('admin/add_operator', [
                'error' => 'Please fill in all fields.'
            ]);
            return;
        }

        // Strong password validation

    if (strlen($password) < 8) {
    $this->render('admin/add_operator', [
        'error' => 'Password must be at least 8 characters.'
    ]);
    return;
            }
    if (!preg_match('/[A-Z]/', $password)) {
    $this->render('admin/add_operator', [
        'error' => 'Password must contain at least one uppercase letter.'
    ]);
    return;
            }
    if (!preg_match('/[a-z]/', $password)) {
    $this->render('admin/add_operator', [
        'error' => 'Password must contain at least one lowercase letter.'
    ]);
    return;
            }
    if (!preg_match('/[0-9]/', $password)) {
    $this->render('admin/add_operator', [
        'error' => 'Password must contain at least one number.'
    ]);
    return;
            }
    if (!preg_match('/[\@\#\$\!\%\*\?\&]/', $password)) {
    $this->render('admin/add_operator', [
        'error' => 'Password must contain at least one special character (@#$!%*?&).'
    ]);
    return;
            }

        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            $this->render('admin/add_operator', [
                'error' => 'An account with this email already exists.'
            ]);
            return;
        }

        $success = $this->userModel->create($fullName, $email, $password, 'operator');

        if ($success) {
            $this->render('admin/add_operator', [
                'success' => 'Operator account created successfully.'
            ]);
        } else {
            $this->render('admin/add_operator', [
                'error' => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function manageMessages() {
    $this->requireRole('admin');

    $db     = Database::getInstance()->getConnection();
    $result = $db->query("
        SELECT * FROM contact_messages
        ORDER BY is_read ASC, created_at DESC
    ");
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    // Mark all as read after viewing
    $db->query("UPDATE contact_messages SET is_read = 1");

    $this->render('admin/messages', [
        'messages' => $messages
    ]);
}
}