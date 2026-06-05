<?php $messages = $messages ?? []; ?>

<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2>Contact Messages</h2>
        <a href="/bus-booking/public/index.php?page=admin"
           class="btn-secondary">← Back</a>
    </div>

    <?php if (count($messages) === 0): ?>
        <p class="no-bookings">No messages yet.</p>
    <?php else: ?>
        <table class="routes-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr style="<?php echo $msg['is_read'] == 0 ? 'background:#fff8e1; font-weight:600;' : ''; ?>">
                        <td><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                        <td style="max-width:300px;">
                            <?php echo htmlspecialchars($msg['message']); ?>
                        </td>
                        <td><?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if ($msg['is_read'] == 0): ?>
                                <span style="color:#f57c00; font-weight:600; font-size:13px;">
                                    New
                                </span>
                            <?php else: ?>
                                <span style="color:#aaa; font-size:13px;">Read</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>