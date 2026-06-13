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

        <?php foreach ($messages as $msg): ?>
            <div class="message-card <?php echo $msg['is_read'] == 0 ? 'message-unread' : ''; ?>">

                <div class="message-header">
                    <div class="message-meta">
                        <span class="message-name">
                            <?php echo htmlspecialchars($msg['name']); ?>
                        </span>
                        <span class="message-email">
                            <?php echo htmlspecialchars($msg['email']); ?>
                        </span>
                        <span class="message-date">
                            <?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?>
                        </span>
                    </div>
                    <div>
                        <?php if ($msg['is_read'] == 0): ?>
                            <span style="color:#f57c00; font-weight:600; font-size:13px;">
                                🔵 New
                            </span>
                        <?php else: ?>
                            <span style="color:#aaa; font-size:13px;">Read</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="message-subject">
                    📌 <?php echo htmlspecialchars($msg['subject']); ?>
                </div>

                <div class="message-body">
                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>