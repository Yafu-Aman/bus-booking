<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Booking System</title>
    <link rel="stylesheet" href="/bus-booking/public/css/style.css">
    <script src="/bus-booking/public/js/main.js"></script>
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">
        <a href="/bus-booking/public/index.php?page=home">🚌 BusBook</a>
    </div>

    <div class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>

            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>

            <?php if ($_SESSION['role'] === 'passenger'): ?>
                <a href="/bus-booking/public/index.php?page=dashboard">My Bookings</a>
                <a href="/bus-booking/public/index.php?page=search">Search Routes</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] === 'operator'): ?>
                <a href="/bus-booking/public/index.php?page=operator">Operator Panel</a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="/bus-booking/public/index.php?page=admin">Admin Panel</a>
            <?php endif; ?>

            <a href="/bus-booking/public/index.php?page=contact">Contact</a>
            <a href="/bus-booking/public/index.php?page=logout" class="btn-logout">Logout</a>

        <?php else: ?>

            <a href="/bus-booking/public/index.php?page=contact">Contact</a>
            <a href="/bus-booking/public/index.php?page=login">Login</a>
            <a href="/bus-booking/public/index.php?page=register">Register</a>

        <?php endif; ?>
    </div>
</nav>

<main class="main-content">