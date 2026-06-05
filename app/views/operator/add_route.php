<?php
$error  = $error  ?? '';
$cities = $cities ?? [];
?>

<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2>Add New Route</h2>
        <a href="/bus-booking/public/index.php?page=operator" class="btn-secondary">
            ← Back
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="auth-box" style="max-width:500px;">
        <form action="/bus-booking/public/index.php?page=operator-add" method="POST">

            <div class="form-group">
                <label>From City</label>
                <select name="from_city" id="from_city">
                    <option value="">Select existing city</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?php echo htmlspecialchars($city['city']); ?>">
                            <?php echo htmlspecialchars($city['city']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="from_city_new" id="from_city_new"
                       placeholder="Or type a new city"
                       style="margin-top:8px;">
            </div>

            <div class="form-group">
                <label>To City</label>
                <select name="to_city" id="to_city">
                    <option value="">Select existing city</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?php echo htmlspecialchars($city['city']); ?>">
                            <?php echo htmlspecialchars($city['city']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="to_city_new" id="to_city_new"
                       placeholder="Or type a new city"
                       style="margin-top:8px;">
            </div>

            <div class="form-group">
                <label>Departure Date and Time</label>
                <input type="datetime-local" name="departure_time" required>
            </div>

            <div class="form-group">
                <label>Ticket Price (ETB)</label>
                <input type="number" name="price"
                       placeholder="e.g. 3500" min="1" required>
            </div>

            <button type="submit" class="btn-primary btn-full">Add Route</button>

        </form>
    </div>

</div>

<script>
var fromSelect  = document.getElementById('from_city');
var toSelect    = document.getElementById('to_city');
var fromNew     = document.getElementById('from_city_new');
var toNew       = document.getElementById('to_city_new');

// When dropdown selected, clear the text input
fromSelect.addEventListener('change', function() {
    if (this.value !== '') {
        fromNew.value = '';
    }
    // Disable same city in destination
    var selected = this.value;
    for (var i = 0; i < toSelect.options.length; i++) {
        var opt = toSelect.options[i];
        if (opt.value === selected && selected !== '') {
            opt.disabled = true;
            opt.style.color = '#ccc';
        } else {
            opt.disabled = false;
            opt.style.color = '';
        }
    }
    if (toSelect.value === selected) {
        toSelect.value = '';
    }
});

// When text input typed, clear the dropdown
fromNew.addEventListener('input', function() {
    if (this.value !== '') {
        fromSelect.value = '';
    }
});

toSelect.addEventListener('change', function() {
    if (this.value !== '') {
        toNew.value = '';
    }
});

toNew.addEventListener('input', function() {
    if (this.value !== '') {
        toSelect.value = '';
    }
});
</script>