<?php
$error   = $error   ?? '';
$success = $success ?? '';
?>

<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2>Add New Operator</h2>
        <a href="/bus-booking/public/index.php?page=admin-users"
           class="btn-secondary">← Back</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <div class="auth-box" style="max-width:500px;">
        <form action="/bus-booking/public/index.php?page=admin-add-operator"
              method="POST">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name"
                       placeholder="Operator full name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email"
                       placeholder="Operator email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password"
                       placeholder="Min 8 chars, uppercase, number, special"
                       oninput="checkStrength(this.value)"
                       required>

                <div class="strength-bar-wrap">
                    <div class="strength-bar" id="strength-bar"></div>
                </div>
                <small id="strength-text" class="strength-text"></small>

                <ul class="password-rules">
                    <li id="rule-length">  At least 8 characters</li>
                    <li id="rule-upper">   One uppercase letter (A-Z)</li>
                    <li id="rule-lower">   One lowercase letter (a-z)</li>
                    <li id="rule-number">  One number (0-9)</li>
                    <li id="rule-special"> One special character (@#$!%*?&)</li>
                </ul>
            </div>

            <button type="submit" class="btn-primary btn-full">
                Create Operator Account
            </button>

        </form>
    </div>

</div>

<script>
function checkStrength(password) {
    var bar     = document.getElementById('strength-bar');
    var text    = document.getElementById('strength-text');
    var score   = 0;

    var ruleLength  = document.getElementById('rule-length');
    var ruleUpper   = document.getElementById('rule-upper');
    var ruleLower   = document.getElementById('rule-lower');
    var ruleNumber  = document.getElementById('rule-number');
    var ruleSpecial = document.getElementById('rule-special');

    if (password.length >= 8) {
        score++; ruleLength.style.color = '#2e7d32';
    } else {
        ruleLength.style.color = '#c62828';
    }
    if (/[A-Z]/.test(password)) {
        score++; ruleUpper.style.color = '#2e7d32';
    } else {
        ruleUpper.style.color = '#c62828';
    }
    if (/[a-z]/.test(password)) {
        score++; ruleLower.style.color = '#2e7d32';
    } else {
        ruleLower.style.color = '#c62828';
    }
    if (/[0-9]/.test(password)) {
        score++; ruleNumber.style.color = '#2e7d32';
    } else {
        ruleNumber.style.color = '#c62828';
    }
    if (/[\@\#\$\!\%\*\?\&]/.test(password)) {
        score++; ruleSpecial.style.color = '#2e7d32';
    } else {
        ruleSpecial.style.color = '#c62828';
    }

    var width = (score / 5) * 100;
    var color = '#e53935';
    var label = 'Very Weak';

    if (score === 2) { color = '#ff7043'; label = 'Weak';     }
    if (score === 3) { color = '#ffc107'; label = 'Fair';     }
    if (score === 4) { color = '#66bb6a'; label = 'Good';     }
    if (score === 5) { color = '#2e7d32'; label = 'Strong ✓'; }

    bar.style.width      = width + '%';
    bar.style.background = color;
    text.textContent     = label;
    text.style.color     = color;
}
</script>