<?php

class Route {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Search only approved routes
    public function search($fromCity, $toCity, $date) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.full_name AS operator_name
            FROM routes r
            JOIN users u ON r.operator_id = u.id
            WHERE LOWER(TRIM(r.from_city)) = LOWER(TRIM(?))
            AND   LOWER(TRIM(r.to_city))   = LOWER(TRIM(?))
            AND   DATE(r.departure_time)   = ?
            AND   r.departure_time        >= NOW()
            AND   r.approval_status        = 'approved'
        ");
        $stmt->bind_param('sss', $fromCity, $toCity, $date);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get cities from approved routes only
    public function getCities() {
        $result = $this->db->query("
            SELECT DISTINCT from_city AS city 
            FROM routes WHERE approval_status = 'approved'
            UNION
            SELECT DISTINCT to_city AS city 
            FROM routes WHERE approval_status = 'approved'
            ORDER BY city
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get upcoming approved routes for default display
    public function getUpcomingRoutes() {
        $result = $this->db->query("
            SELECT r.*, u.full_name AS operator_name
            FROM routes r
            JOIN users u ON r.operator_id = u.id
            WHERE r.departure_time  >= NOW()
            AND   r.approval_status  = 'approved'
            ORDER BY r.departure_time ASC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get one route by ID
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.full_name AS operator_name
            FROM routes r
            JOIN users u ON r.operator_id = u.id
            WHERE r.id = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get routes by operator — all statuses so operator sees pending too
    public function getByOperator($operatorId) {
        $stmt = $this->db->prepare("
            SELECT r.*, COUNT(b.id) AS total_bookings
            FROM routes r
            LEFT JOIN bookings b ON r.id = b.route_id
                                 AND b.status = 'confirmed'
            WHERE r.operator_id = ?
            GROUP BY r.id
            ORDER BY r.departure_time ASC
        ");
        $stmt->bind_param('i', $operatorId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Create a new route — starts as pending
    public function create($fromCity, $toCity, $departureTime, $price, $operatorId) {
        $stmt = $this->db->prepare("
            INSERT INTO routes 
            (from_city, to_city, departure_time, price, operator_id, approval_status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param('sssdi', $fromCity, $toCity, $departureTime, $price, $operatorId);
        return $stmt->execute();
    }

    // Delete route — operator can delete only if no bookings
    public function delete($routeId, $operatorId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total FROM bookings
            WHERE route_id = ? AND status = 'confirmed'
        ");
        $stmt->bind_param('i', $routeId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row['total'] > 0) {
            return 'has_bookings';
        }

        $stmt = $this->db->prepare("
            DELETE FROM routes WHERE id = ? AND operator_id = ?
        ");
        $stmt->bind_param('ii', $routeId, $operatorId);
        $stmt->execute();
        return 'deleted';
    }

    // Check duplicate route on same date
    public function isDuplicateOnDate($fromCity, $toCity, $departureTime) {
        $date = date('Y-m-d', strtotime($departureTime));
        $stmt = $this->db->prepare("
            SELECT id FROM routes
            WHERE LOWER(from_city)     = LOWER(?)
            AND   LOWER(to_city)       = LOWER(?)
            AND   DATE(departure_time) = ?
        ");
        $stmt->bind_param('sss', $fromCity, $toCity, $date);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Get all routes for admin — all statuses
    public function getAllRoutes() {
        $result = $this->db->query("
            SELECT r.*, u.full_name AS operator_name,
                   COUNT(b.id) AS total_bookings
            FROM routes r
            JOIN users u ON r.operator_id = u.id
            LEFT JOIN bookings b ON r.id = b.route_id
                                 AND b.status = 'confirmed'
            GROUP BY r.id
            ORDER BY r.approval_status ASC, r.departure_time ASC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get only pending routes for admin approval
    public function getPendingRoutes() {
        $result = $this->db->query("
            SELECT r.*, u.full_name AS operator_name
            FROM routes r
            JOIN users u ON r.operator_id = u.id
            WHERE r.approval_status = 'pending'
            ORDER BY r.created_at ASC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Admin approves a route
    public function approve($routeId) {
        $stmt = $this->db->prepare("
            UPDATE routes SET approval_status = 'approved' WHERE id = ?
        ");
        $stmt->bind_param('i', $routeId);
        return $stmt->execute();
    }

    // Admin rejects a route — deletes it
    public function reject($routeId) {
        $stmt = $this->db->prepare("
            DELETE FROM routes WHERE id = ? AND approval_status = 'pending'
        ");
        $stmt->bind_param('i', $routeId);
        return $stmt->execute();
    }
}