<?php

class User {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($fullName, $email, $password, $role = 'passenger') {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (full_name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('ssss', $fullName, $email, $hashedPassword, $role);
        return $stmt->execute();
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get all users except admins
    public function getAllUsers() {
        $result = $this->db->query("
            SELECT * FROM users
            WHERE role != 'admin'
            ORDER BY created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Suspend a user account
    public function suspend($userId) {
        $stmt = $this->db->prepare("
            UPDATE users SET status = 'suspended'
            WHERE id = ? AND role != 'admin'
        ");
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }

    // Activate a user account
    public function activate($userId) {
        $stmt = $this->db->prepare("
            UPDATE users SET status = 'active'
            WHERE id = ? AND role != 'admin'
        ");
        $stmt->bind_param('i', $userId);
        return $stmt->execute();
    }

    // Get total counts for dashboard stats
    public function getCounts() {
        $result = $this->db->query("
            SELECT
                COUNT(CASE WHEN role = 'passenger' THEN 1 END) AS total_passengers,
                COUNT(CASE WHEN role = 'operator'  THEN 1 END) AS total_operators
            FROM users
        ");
        return $result->fetch_assoc();
    }
}