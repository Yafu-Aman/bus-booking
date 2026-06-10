<?php
$bookings      = $bookings      ?? [];
$pending       = $pending       ?? null;
$lastBooking   = $lastBooking   ?? null;
$mostTravelled = $mostTravelled ?? null;
$suggested     = $suggested     ?? [];
$stats         = $stats         ?? ['total_trips' => 0, 'total_spent' => 0];
?>

<div class="dashboard-container">

    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! 👋</h2>

    <!-- Personal Stats -->
    <div class="stats-grid" style="margin-bottom:24px; margin-top:16px;">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total_trips']; ?></div>
            <div class="stat-label">Total Trips</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                ETB <?php echo number_format($stats['total_spent'], 0); ?>
            </div>
            <div class="stat-label">Total Spent</div>
        </div>
        <?php if ($mostTravelled): ?>
            <div class="stat-card">
                <div class="stat-number" style="font-size:16px; padding-top:8px;">
                    <?php echo htmlspecialchars($mostTravelled['from_city']); ?>
                    →
                    <?php echo htmlspecialchars($mostTravelled['to_city']); ?>
                </div>
                <div class="stat-label">Favourite Route</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Continue previous booking notification -->
    <?php if ($pending): ?>
        <div class="pending-notice">
            <p>
                You have an unfinished booking —
                <strong>
                    <?php echo htmlspecialchars($pending['from_city']); ?>
                    →
                    <?php echo htmlspecialchars($pending['to_city']); ?>
                </strong>
                on <?php echo date('M d, Y', strtotime($pending['departure_time'])); ?>
            </p>
            <div class="pending-actions">
                <a href="/bus-booking/public/index.php?page=seats&route_id=<?php echo $pending['route_id']; ?>"
                   class="btn-primary">Continue Booking</a>
                <a href="/bus-booking/public/index.php?page=dismiss-pending"
                   class="btn-secondary">Dismiss</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quick Rebook -->
    <?php if ($lastBooking): ?>
        <div class="pending-notice" style="border-left-color:#1a73e8; background:#e8f0fe;">
            <p style="color:#1557b0;">
                🔁 Quick rebook your last trip —
                <strong>
                    <?php echo htmlspecialchars($lastBooking['from_city']); ?>
                    →
                    <?php echo htmlspecialchars($lastBooking['to_city']); ?>
                </strong>
            </p>
            <div class="pending-actions">
                <a href="/bus-booking/public/index.php?page=search"
                   class="btn-primary">Book Again</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Suggested Routes -->
    <?php if (count($suggested) > 0): ?>
        <div style="margin-bottom:32px;">
            <h3>✨ Suggested Routes For You</h3>
            <p style="color:#888; font-size:13px; margin-bottom:16px;">
                Based on your travel history
            </p>
            <div class="suggested-grid">
                <?php foreach ($suggested as $route): ?>
                    <div class="suggested-card">
                        <div class="suggested-cities">
                            <span><?php echo htmlspecialchars($route['from_city']); ?></span>
                            <span class="route-arrow">→</span>
                            <span><?php echo htmlspecialchars($route['to_city']); ?></span>
                        </div>
                        <div class="suggested-meta">
                            <span>📅 <?php echo date('M d', strtotime($route['departure_time'])); ?></span>
                            <span>🕐 <?php echo date('h:i A', strtotime($route['departure_time'])); ?></span>
                        </div>
                        <div class="suggested-price">
                            ETB <?php echo number_format($route['price'], 2); ?>
                        </div>
                        <a href="/bus-booking/public/index.php?page=seats&route_id=<?php echo $route['id']; ?>"
                           class="btn-primary" style="width:100%; text-align:center; margin-top:12px;">
                            Book Now
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Search button -->
    <div style="display:flex; justify-content:flex-end; align-items:center; margin-bottom:8px; margin-top:0;">
        <a href="/bus-booking/public/index.php?page=search" class="btn-primary">
            Search Bus Routes
        </a>
    </div>

    <!-- Booking History -->
    <h3 style="margin-bottom:16px; margin-top:8px;">My Bookings</h3>

    <?php if (count($bookings) === 0): ?>
        <p class="no-bookings">You have no bookings yet.</p>
    <?php else: ?>
        <table class="routes-table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Seat</th>
                    <th>Price Paid</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['reference_code']); ?></td>
                        <td><?php echo htmlspecialchars($booking['from_city']); ?></td>
                        <td><?php echo htmlspecialchars($booking['to_city']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($booking['departure_time'])); ?></td>
                        <td><?php echo htmlspecialchars($booking['seat_number']); ?></td>
                        <td>ETB <?php echo number_format($booking['price'], 2); ?></td>
                        <td>
                            <span class="status-<?php echo $booking['status']; ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($booking['status'] === 'confirmed'): ?>
                                <form method="POST"
                                      action="/bus-booking/public/index.php?page=cancel-booking">
                                    <input type="hidden" name="booking_id"
                                           value="<?php echo $booking['id']; ?>">
                                    <button type="button" class="btn-cancel"
                                            onclick="confirmCancel(this)">
                                        Cancel
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color:#aaa;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>