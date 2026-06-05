<?php

class Booking {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all booked seats for a route
    public function getBookedSeats($routeId) {
        $stmt = $this->db->prepare("
            SELECT seat_number FROM bookings
            WHERE route_id = ? AND status = 'confirmed'
        ");
        $stmt->bind_param('i', $routeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $seats  = [];
        while ($row = $result->fetch_assoc()) {
            $seats[] = $row['seat_number'];
        }
        return $seats;
    }

    // Create a new booking
    public function create($userId, $routeId, $seatNumber, $finalPrice) {
        $referenceCode = 'BB-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $stmt = $this->db->prepare("
            INSERT INTO bookings
            (user_id, route_id, seat_number, price, reference_code, status)
            VALUES (?, ?, ?, ?, ?, 'confirmed')
        ");
        $stmt->bind_param('iiids', $userId, $routeId, $seatNumber, $finalPrice, $referenceCode);
        if ($stmt->execute()) {
            return $referenceCode;
        }
        return false;
    }

    // Check if a seat is already taken
    public function isSeatTaken($routeId, $seatNumber) {
        $stmt = $this->db->prepare("
            SELECT id FROM bookings
            WHERE route_id    = ?
            AND   seat_number = ?
            AND   status      = 'confirmed'
        ");
        $stmt->bind_param('ii', $routeId, $seatNumber);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Check if user already booked this exact route
    public function hasSameRouteBooking($userId, $routeId) {
        $stmt = $this->db->prepare("
            SELECT id FROM bookings
            WHERE user_id  = ?
            AND   route_id = ?
            AND   status   = 'confirmed'
        ");
        $stmt->bind_param('ii', $userId, $routeId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Check if user already has any booking on the same travel date
    public function hasConflict($userId, $routeId) {
        $stmt = $this->db->prepare("
            SELECT b.id
            FROM bookings b
            JOIN routes r1 ON b.route_id = r1.id
            JOIN routes r2 ON r2.id      = ?
            WHERE b.user_id               = ?
            AND   b.status                = 'confirmed'
            AND   DATE(r1.departure_time) = DATE(r2.departure_time)
            AND   b.route_id             != ?
        ");
        $stmt->bind_param('iii', $routeId, $userId, $routeId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Get all bookings for a passenger
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT b.*, r.from_city, r.to_city,
                   r.departure_time, r.price AS base_price
            FROM bookings b
            JOIN routes r ON b.route_id = r.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Cancel a booking
    public function cancel($bookingId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE bookings SET status = 'cancelled'
            WHERE id      = ?
            AND   user_id = ?
        ");
        $stmt->bind_param('ii', $bookingId, $userId);
        return $stmt->execute();
    }

    // Save pending booking
    public function savePending($userId, $routeId) {
        $stmt = $this->db->prepare(
            "DELETE FROM pending_bookings WHERE user_id = ?"
        );
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $stmt = $this->db->prepare("
            INSERT INTO pending_bookings (user_id, route_id)
            VALUES (?, ?)
        ");
        $stmt->bind_param('ii', $userId, $routeId);
        return $stmt->execute();
    }

    // Get pending booking for a user
    public function getPending($userId) {
        $stmt = $this->db->prepare("
            SELECT pb.*, r.from_city, r.to_city,
                   r.departure_time, r.price
            FROM pending_bookings pb
            JOIN routes r ON pb.route_id = r.id
            WHERE pb.user_id = ?
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Delete pending booking
    public function deletePending($userId) {
        $stmt = $this->db->prepare(
            "DELETE FROM pending_bookings WHERE user_id = ?"
        );
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }

    // Calculate dynamic price based on departure time
    public function calculatePrice($basePrice, $departureTime) {

    // Use MySQL server time to avoid timezone mismatch
    $result = $this->db->query("SELECT NOW() AS now_time");
    $row    = $result->fetch_assoc();
    $now    = strtotime($row['now_time']);

    $departure   = strtotime($departureTime);
    $diffSeconds = $departure - $now;
    $hoursUntil  = $diffSeconds / 3600;
    $daysUntil   = $diffSeconds / 86400;
    $dayOfWeek   = (int)date('N', $departure);

    $finalPrice = (float)$basePrice;
    $reason     = 'Standard price';

    if ($diffSeconds <= 0) {
    $finalPrice = (float)$basePrice;
    $reason     = 'Standard price';

} elseif ($hoursUntil <= 24) {
    // Last minute always wins
    $finalPrice = round((float)$basePrice * 1.30, 2);
    $reason     = 'Last minute booking (+30%)';

} elseif ($dayOfWeek === 6 || $dayOfWeek === 7) {
    // Weekend beats early bird
    $finalPrice = round((float)$basePrice * 1.20, 2);
    $reason     = 'Weekend price (+20%)';

} elseif ($daysUntil > 7) {
    // Early bird for non-weekend future routes
    $finalPrice = round((float)$basePrice * 0.90, 2);
    $reason     = 'Early bird discount (-10%)';
}

    return [
        'final_price' => $finalPrice,
        'reason'      => $reason
    ];
}

// Get passenger's most booked route
public function getMostTravelledRoute($userId) {
    $stmt = $this->db->prepare("
        SELECT r.from_city, r.to_city,
               COUNT(b.id) AS trip_count
        FROM bookings b
        JOIN routes r ON b.route_id = r.id
        WHERE b.user_id = ?
        AND   b.status  = 'confirmed'
        GROUP BY r.from_city, r.to_city
        ORDER BY trip_count DESC
        LIMIT 1
    ");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get last booking for quick rebook
public function getLastBooking($userId) {
    $stmt = $this->db->prepare("
        SELECT b.*, r.from_city, r.to_city,
               r.price, r.departure_time
        FROM bookings b
        JOIN routes r ON b.route_id = r.id
        WHERE b.user_id = ?
        AND   b.status  = 'confirmed'
        ORDER BY b.created_at DESC
        LIMIT 1
    ");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get suggested routes based on cities user has travelled
public function getSuggestedRoutes($userId) {
    $stmt = $this->db->prepare("
        SELECT DISTINCT r.id, r.from_city, r.to_city,
               r.departure_time, r.price,
               u.full_name AS operator_name
        FROM routes r
        JOIN users u ON r.operator_id = u.id
        JOIN bookings b ON (
            r.from_city = (
                SELECT r2.from_city FROM bookings b2
                JOIN routes r2 ON b2.route_id = r2.id
                WHERE b2.user_id = ?
                AND   b2.status  = 'confirmed'
                ORDER BY b2.created_at DESC
                LIMIT 1
            )
        )
        WHERE r.departure_time    >= NOW()
        AND   r.approval_status    = 'approved'
        AND   r.id NOT IN (
            SELECT route_id FROM bookings
            WHERE user_id = ? AND status = 'confirmed'
        )
        GROUP BY r.id
        ORDER BY r.departure_time ASC
        LIMIT 3
    ");
    $stmt->bind_param('ii', $userId, $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get total stats for passenger
public function getPassengerStats($userId) {
    $stmt = $this->db->prepare("
        SELECT
            COUNT(CASE WHEN b.status = 'confirmed' THEN 1 END) AS total_trips,
            COALESCE(SUM(CASE WHEN b.status = 'confirmed' THEN b.price END), 0) AS total_spent
        FROM bookings b
        WHERE b.user_id = ?
    ");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
}