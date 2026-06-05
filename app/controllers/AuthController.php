<?php

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User();
    }

    public function showHome() {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        $this->render('auth/home');
    }

    public function showLogin() {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        $this->render('auth/login');
    }

    public function showRegister() {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        $this->render('auth/register');
    }

    public function login() {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->render('auth/login', [
                'error' => 'Please fill in all fields.'
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        // Check user exists and password matches
        if (!$user || !password_verify($password, $user['password'])) {
            $this->render('auth/login', [
                'error' => 'Invalid email or password.'
            ]);
            return;
        }

        // Check if account is suspended
        if ($user['status'] === 'suspended') {
            $this->render('auth/login', [
                'error' => 'Your account has been suspended. Please contact support.'
            ]);
            return;
        }

        // Store user info in session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        $this->redirectToDashboard();
    }

    public function register() {
        $fullName        = trim($_POST['full_name']        ?? '');
        $email           = trim($_POST['email']            ?? '');
        $password        = trim($_POST['password']         ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if (empty($fullName) || empty($email) || empty($password)) {
            $this->render('auth/register', [
                'error' => 'Please fill in all fields.'
            ]);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->render('auth/register', [
                'error' => 'Passwords do not match.'
            ]);
            return;
        }

        // Strong password validation
         $passwordError = $this->validatePassword($password);
          if ($passwordError) {
          $this->render('auth/register', [
        'error' => $passwordError
         ]);
         return;
           }

        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            $this->render('auth/register', [
                'error' => 'An account with this email already exists.'
            ]);
            return;
        }

        $success = $this->userModel->create($fullName, $email, $password);

        if ($success) {
            $this->render('auth/login', [
                'success' => 'Account created successfully. Please login.'
            ]);
        } else {
            $this->render('auth/register', [
                'error' => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/bus-booking/public/index.php?page=home');
    }

    private function redirectToDashboard() {
        $role = $_SESSION['role'] ?? '';
        if ($role === 'admin') {
            $this->redirect('/bus-booking/public/index.php?page=admin');
        } elseif ($role === 'operator') {
            $this->redirect('/bus-booking/public/index.php?page=operator');
        } else {
            $this->redirect('/bus-booking/public/index.php?page=dashboard');
        }
    }

    // Validate password strength
private function validatePassword($password) {
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters.';
    }
    // Check at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return 'Password must contain at least one uppercase letter.';
    }
    // Check at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return 'Password must contain at least one lowercase letter.';
    }
    // Check at least one number
    if (!preg_match('/[0-9]/', $password)) {
        return 'Password must contain at least one number.';
    }
    // Check at least one special character
    if (!preg_match('/[\@\#\$\!\%\*\?\&]/', $password)) {
        return 'Password must contain at least one special character (@#$!%*?&).';
    }
    return null;
}
}