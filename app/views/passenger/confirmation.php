<?php
$route         = $route         ?? [];
$seatNumber    = $seatNumber    ?? '';
$referenceCode = $referenceCode ?? '';
$finalPrice    = $finalPrice    ?? 0;
$priceReason   = $priceReason   ?? 'Standard price';
?>

<div class="confirmation-container">
    <div class="confirmation-box">

        <div class="check-icon">✅</div>
        <h2>Booking Confirmed!</h2>

        <div class="reference-code">
            <?php echo htmlspecialchars($referenceCode); ?>
        </div>
        <p class="ref-label">Your Reference Code — save this!</p>

        <div class="confirmation-details">
            <div class="detail-row">
                <span>From</span>
                <strong><?php echo htmlspecialchars($route['from_city']); ?></strong>
            </div>
            <div class="detail-row">
                <span>To</span>
                <strong><?php echo htmlspecialchars($route['to_city']); ?></strong>
            </div>
            <div class="detail-row">
                <span>Date</span>
                <strong>
                    <?php echo date('M d, Y', strtotime($route['departure_time'])); ?>
                </strong>
            </div>
            <div class="detail-row">
                <span>Departure</span>
                <strong>
                    <?php echo date('h:i A', strtotime($route['departure_time'])); ?>
                </strong>
            </div>
            <div class="detail-row">
                <span>Seat</span>
                <strong><?php echo htmlspecialchars($seatNumber); ?></strong>
            </div>
            <div class="detail-row">
                <span>Price Paid</span>
                <strong>ETB <?php echo number_format($finalPrice, 2); ?></strong>
            </div>
            <div class="detail-row">
                <span>Pricing</span>
                <strong style="color:#1a73e8;">
                    <?php echo htmlspecialchars($priceReason); ?>
                </strong>
            </div>
        </div>

        <a href="/bus-booking/public/index.php?page=dashboard" class="btn-primary">
            View My Bookings
        </a>

    </div>
</div>