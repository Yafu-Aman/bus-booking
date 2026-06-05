<?php
$totalPassengers = $totalPassengers ?? 0;
$totalOperators  = $totalOperators  ?? 0;
$totalConfirmed  = $totalConfirmed  ?? 0;
$totalCancelled  = $totalCancelled  ?? 0;
$totalRoutes     = $totalRoutes     ?? 0;
$pendingRoutes   = $pendingRoutes   ?? 0;
$unreadMessages  = $unreadMessages  ?? 0;
?>

<div class="dashboard-container">

    <h2>Admin Dashboard</h2>

    <?php if ($pendingRoutes > 0): ?>
        <div class="pending-notice">
            <p>
                You have <strong><?php echo $pendingRoutes; ?></strong>
                pending route(s) waiting for approval.
            </p>
            <div class="pending-actions">
                <a href="/bus-booking/public/index.php?page=admin-routes"
                   class="btn-primary">Review Routes</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($unreadMessages > 0): ?>
        <div class="pending-notice" style="border-left-color:#1a73e8; background:#e8f0fe;">
            <p style="color:#1557b0;">
                You have <strong><?php echo $unreadMessages; ?></strong>
                new unread message(s) from passengers.
            </p>
            <div class="pending-actions">
                <a href="/bus-booking/public/index.php?page=admin-messages"
                   class="btn-primary">View Messages</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo $totalPassengers; ?></div>
            <div class="stat-label">Passengers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $totalOperators; ?></div>
            <div class="stat-label">Operators</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $totalRoutes; ?></div>
            <div class="stat-label">Approved Routes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $pendingRoutes; ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $totalConfirmed; ?></div>
            <div class="stat-label">Confirmed Bookings</div>
        </div>
        <div class="stat-card cancelled">
            <div class="stat-number"><?php echo $totalCancelled; ?></div>
            <div class="stat-label">Cancelled Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $unreadMessages; ?></div>
            <div class="stat-label">New Messages</div>
        </div>
    </div>

    <div class="admin-links">
        <a href="/bus-booking/public/index.php?page=admin-users"
           class="btn-primary">Manage Users</a>
        <a href="/bus-booking/public/index.php?page=admin-routes"
           class="btn-secondary">Manage Routes</a>
        <a href="/bus-booking/public/index.php?page=admin-messages"
           class="btn-secondary">View Messages</a>
    </div>

</div>