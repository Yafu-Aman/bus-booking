<?php
$error       = $error       ?? '';
$route       = $route       ?? [];
$bookedSeats = $bookedSeats ?? [];
$totalSeats  = $totalSeats  ?? 45;
$finalPrice  = $finalPrice  ?? $route['price'];
$priceReason = $priceReason ?? 'Standard price';
?>

<div class="seats-container">

    <div class="route-summary">
        <h2>
            <?php echo htmlspecialchars($route['from_city']); ?>
            →
            <?php echo htmlspecialchars($route['to_city']); ?>
        </h2>
        <div class="route-meta">
            <span>📅 <?php echo date('M d, Y', strtotime($route['departure_time'])); ?></span>
            <span>🕐 <?php echo date('h:i A',  strtotime($route['departure_time'])); ?></span>
            <span>💰 ETB <?php echo number_format($finalPrice, 2); ?></span>
        </div>

        <!-- Dynamic price notice -->
        <?php if ($priceReason !== 'Standard price'): ?>
            <div class="price-notice">
                <?php
                if (strpos($priceReason, '+') !== false) {
                    echo '⚠️ ';
                } else {
                    echo '🎉 ';
                }
                echo htmlspecialchars($priceReason);
                ?>
                <span style="color:#888; font-size:12px;">
                    (Base price: ETB <?php echo number_format($route['price'], 2); ?>)
                </span>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="seat-legend">
        <span class="legend-item"><span class="seat-demo available"></span> Available</span>
        <span class="legend-item"><span class="seat-demo booked"></span> Booked</span>
        <span class="legend-item"><span class="seat-demo selected"></span> Your Selection</span>
    </div>

    <div class="bus-container">
        <div class="bus-front">🚌 Driver</div>
        <div class="seat-grid">
            <?php for ($i = 1; $i <= $totalSeats; $i++): ?>
                <?php $isBooked = in_array($i, $bookedSeats); ?>
                <div class="seat <?php echo $isBooked ? 'booked' : 'available'; ?>"
                     data-seat="<?php echo $i; ?>"
                     <?php echo $isBooked ? '' : 'onclick="selectSeat(this)"'; ?>>
                    <?php echo $i; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <form action="/bus-booking/public/index.php?page=book"
          method="POST" id="booking-form" style="display:none;">
        <input type="hidden" name="route_id"    value="<?php echo $route['id']; ?>">
        <input type="hidden" name="seat_number" id="selected-seat-input" value="">
        <div class="booking-summary">
            <p>Selected: <strong id="selected-seat-display">-</strong></p>
            <p>Price: <strong>ETB <?php echo number_format($finalPrice, 2); ?></strong></p>
            <?php if ($priceReason !== 'Standard price'): ?>
                <p style="font-size:13px; color:#888;"><?php echo htmlspecialchars($priceReason); ?></p>
            <?php endif; ?>
            <button type="submit" class="btn-primary">Confirm Booking</button>
            <button type="button" onclick="cancelSelection()" class="btn-secondary">Cancel</button>
        </div>
    </form>

</div>

<script>
var currentSelected = null;

function selectSeat(el) {
    if (currentSelected === el) {
        el.className = 'seat available';
        currentSelected = null;
        document.getElementById('booking-form').style.display = 'none';
        return;
    }
    if (currentSelected) {
        currentSelected.className = 'seat available';
    }
    el.className = 'seat selected';
    currentSelected = el;
    var num = el.getAttribute('data-seat');
    document.getElementById('selected-seat-input').value        = num;
    document.getElementById('selected-seat-display').textContent = 'Seat ' + num;
    document.getElementById('booking-form').style.display       = 'block';
}

function cancelSelection() {
    if (currentSelected) {
        currentSelected.className = 'seat available';
        currentSelected = null;
    }
    document.getElementById('booking-form').style.display = 'none';
}
</script>