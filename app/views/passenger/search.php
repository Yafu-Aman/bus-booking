<?php
// Set default values if variables are not passed
$cities    = $cities    ?? [];
$allRoutes = $allRoutes ?? [];
$searched  = $searched  ?? false;
$results   = $results   ?? [];
$fromCity  = $fromCity  ?? '';
$toCity    = $toCity    ?? '';
$date      = $date      ?? '';
$error     = $error     ?? '';
?>

<div class="search-container">
    <h2>Search Bus Routes</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="search-box">
        <form action="/bus-booking/public/index.php?page=search" method="POST">
            <div class="search-row">

                <div class="form-group">
                    <label for="from_city">From</label>
                    <select name="from_city" id="from_city" required>
                        <option value="">Select departure city</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo htmlspecialchars($city['city']); ?>"
                                <?php echo ($fromCity === $city['city']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($city['city']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="to_city">To</label>
                    <select name="to_city" id="to_city" required>
                        <option value="">Select destination city</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo htmlspecialchars($city['city']); ?>"
                                <?php echo ($toCity === $city['city']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($city['city']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Travel Date</label>
                    <input type="date" id="date" name="date"
                           value="<?php echo htmlspecialchars($date); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-primary btn-search">Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- Search Results (shown after searching) -->
    <?php if ($searched): ?>
        <div class="results-section">
            <h3>
                <?php if (count($results) > 0): ?>
                    <?php echo count($results); ?> route(s) found from
                    <?php echo htmlspecialchars($fromCity); ?> to
                    <?php echo htmlspecialchars($toCity); ?>
                <?php else: ?>
                    No routes found for this search.
                <?php endif; ?>
            </h3>

            <?php foreach ($results as $route): ?>
                <div class="route-card">
                    <div class="route-info">
                        <div class="route-cities">
                            <span class="city-from">
                                <?php echo htmlspecialchars($route['from_city']); ?>
                            </span>
                            <span class="route-arrow">→</span>
                            <span class="city-to">
                                <?php echo htmlspecialchars($route['to_city']); ?>
                            </span>
                        </div>
                        <div class="route-details">
                            <span>🕐 <?php echo date('h:i A', strtotime($route['departure_time'])); ?></span>
                            <span>🚌 <?php echo htmlspecialchars($route['operator_name']); ?></span>
                            <span>📅 <?php echo date('M d, Y', strtotime($route['departure_time'])); ?></span>
                        </div>
                    </div>
                    <div class="route-price">
                        <span class="price">
                            ETB <?php echo number_format($route['price'], 2); ?>
                        </span>
                        <a href="/bus-booking/public/index.php?page=seats&route_id=<?php echo $route['id']; ?>"
                           class="btn-primary">
                            Select Seats
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <!-- Default Routes Table (shown before searching) -->
        <div class="default-routes">
            <h3>All Available Routes</h3>

            <?php if (count($allRoutes) > 0): ?>
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
                        <?php foreach ($allRoutes as $route): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($route['from_city']); ?></td>
                                <td><?php echo htmlspecialchars($route['to_city']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($route['departure_time'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($route['departure_time'])); ?></td>
                                <td><?php echo htmlspecialchars($route['operator_name']); ?></td>
                                <td><?php echo number_format($route['price'], 2); ?></td>
                                <td>
                                    <a href="/bus-booking/public/index.php?page=seats&route_id=<?php echo $route['id']; ?>"
                                       class="btn-book">
                                        Book Now
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-routes">No upcoming routes available at the moment.</p>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

<!-- JavaScript to disable same city in destination dropdown -->
<script>
    // Get both dropdown elements
    var fromDropdown = document.getElementById('from_city');
    var toDropdown   = document.getElementById('to_city');

    // When user changes the FROM city
    fromDropdown.addEventListener('change', function() {

        // Get the city they selected
        var selectedCity = this.value;

        // Loop through every option in the TO dropdown
        for (var i = 0; i < toDropdown.options.length; i++) {
            var option = toDropdown.options[i];

            // If this option matches the selected FROM city
            if (option.value === selectedCity) {
                // Disable it so user cannot select it
                option.disabled = true;
                option.style.color = '#ccc';
            } else {
                // Enable all other options
                option.disabled = false;
                option.style.color = '';
            }
        }

        // If user already selected the same city in TO, reset it
        if (toDropdown.value === selectedCity) {
            toDropdown.value = '';
        }
    });
</script>