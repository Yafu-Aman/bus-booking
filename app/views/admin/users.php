<?php $users = $users ?? []; ?>

<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2>Manage Users</h2>
        <div style="display:flex; gap:12px;">
            <a href="/bus-booking/public/index.php?page=admin-add-operator"
               class="btn-primary">
                + Add Operator
            </a>
            <a href="/bus-booking/public/index.php?page=admin"
               class="btn-secondary">← Back</a>
        </div>
    </div>

    <?php if (count($users) === 0): ?>
        <p class="no-bookings">No users found.</p>
    <?php else: ?>
        <table class="routes-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                            <span class="status-<?php echo $user['status']; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['status'] === 'active'): ?>
                                <button class="btn-cancel"
                                        onclick="confirmSuspend(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')">
                                    Suspend
                                </button>
                            <?php else: ?>
                                <button class="btn-book"
                                        onclick="confirmActivate(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')">
                                    Activate
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>