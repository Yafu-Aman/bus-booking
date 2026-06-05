<?php
$routes        = $routes        ?? [];
$pendingRoutes = $pendingRoutes ?? [];
?>

<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2>Manage Routes</h2>
        <a href="/bus-booking/public/index.php?page=admin"
           class="btn-secondary">← Back</a>
    </div>

    <!-- Pending routes section -->
    <?php if (count($pendingRoutes) > 0): ?>
        <div style="margin-bottom:40px;">
            <h3 style="color:#f57c00; margin-bottom:16px;">
                ⏳ Pending Approval (<?php echo count($pendingRoutes); ?>)
            </h3>
            <table class="routes-table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Date</th>
                        <th>Departure</th>
                        <th>Operator</th>
                        <th>Price (ETB)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingRoutes as $route): ?>
                        <tr style="background:#fff8e1;">
                            <td><?php echo htmlspecialchars($route['from_city']); ?></td>
                            <td><?php echo htmlspecialchars($route['to_city']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($route['departure_time'])); ?></td>
                            <td><?php echo date('h:i A',  strtotime($route['departure_time'])); ?></td>
                            <td><?php echo htmlspecialchars($route['operator_name']); ?></td>
                            <td><?php echo number_format($route['price'], 2); ?></td>
                            <td style="display:flex; gap:8px;">
                                <a href="/bus-booking/public/index.php?page=admin-approve-route&route_id=<?php echo $route['id']; ?>"
                                   class="btn-book">
                                    Approve
                                </a>
                                <button class="btn-cancel"
                                        onclick="confirmReject(<?php echo $route['id']; ?>)">
                                    Reject
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="margin-bottom:32px;">
            <p style="color:#2e7d32; font-weight:600;">
                ✅ No pending routes — all caught up!
            </p>
        </div>
    <?php endif; ?>

    <!-- All routes section -->
    <h3 style="margin-bottom:16px;">All Routes</h3>

    <?php if (count($routes) === 0): ?>
        <p class="no-bookings">No routes found.</p>
    <?php else: ?>
        <table class="routes-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Operator</th>
                    <th>Price (ETB)</th>
                    <th>Bookings</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($routes as $route): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($route['from_city']); ?></td>
                        <td><?php echo htmlspecialchars($route['to_city']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($route['departure_time'])); ?></td>
                        <td><?php echo htmlspecialchars($route['operator_name']); ?></td>
                        <td><?php echo number_format($route['price'], 2); ?></td>
                        <td><?php echo $route['total_bookings']; ?> booked</td>
                        <td>
                            <?php if ($route['approval_status'] === 'approved'): ?>
                                <span class="status-confirmed">Approved</span>
                            <?php elseif ($route['approval_status'] === 'pending'): ?>
                                <span style="color:#f57c00; font-weight:600; font-size:13px;">Pending</span>
                            <?php else: ?>
                                <span class="status-cancelled">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-cancel"
                                    onclick="confirmAdminDelete(<?php echo $route['id']; ?>)">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>