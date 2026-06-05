<?php
$routes  = $routes  ?? [];
$success = $success ?? '';
$error   = $error   ?? '';
?>

<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2>Operator Dashboard</h2>
        <a href="/bus-booking/public/index.php?page=operator-add"
           class="btn-primary">+ Add New Route</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <h3>My Routes</h3>

    <?php if (count($routes) === 0): ?>
        <p class="no-bookings">You have no routes yet. Add your first route!</p>
    <?php else: ?>
        <table class="routes-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Departure</th>
                    <th>Price (ETB)</th>
                    <th>Bookings</th>
                    <th>Approval</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($routes as $route): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($route['from_city']); ?></td>
                        <td><?php echo htmlspecialchars($route['to_city']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($route['departure_time'])); ?></td>
                        <td><?php echo date('h:i A',  strtotime($route['departure_time'])); ?></td>
                        <td><?php echo number_format($route['price'], 2); ?></td>
                        <td><?php echo $route['total_bookings']; ?> booked</td>
                        <td>
                            <?php if ($route['approval_status'] === 'approved'): ?>
                                <span class="status-confirmed">Approved</span>
                            <?php elseif ($route['approval_status'] === 'pending'): ?>
                                <span style="color:#f57c00; font-weight:600; font-size:13px;">
                                    Pending
                                </span>
                            <?php else: ?>
                                <span class="status-cancelled">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($route['total_bookings'] == 0): ?>
                                <button class="btn-cancel"
                                        onclick="confirmDelete(<?php echo $route['id']; ?>)">
                                    Delete
                                </button>
                            <?php else: ?>
                                <span style="color:#aaa; font-size:13px;">Cannot delete</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>