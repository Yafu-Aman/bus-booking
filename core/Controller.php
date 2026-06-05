<?php

class Controller {

    protected function render($view, $data = []) {
        extract($data);

        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die('View not found: ' . $viewPath);
        }

        require_once __DIR__ . '/../app/views/layouts/header.php';
        require        $viewPath;
        require_once __DIR__ . '/../app/views/layouts/footer.php';
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function hasRole($role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/bus-booking/public/index.php?page=login');
        }
    }

    protected function requireRole($role) {
        $this->requireLogin();
        if (!$this->hasRole($role)) {
            $this->redirect('/bus-booking/public/index.php?page=login');
        }
    }
}