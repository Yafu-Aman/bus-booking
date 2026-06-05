<div class="auth-container">
    <div class="auth-box">
        <h2>Create Account</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="/bus-booking/public/index.php?page=register" method="POST">

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name"
                       placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Min 8 chars, uppercase, number, special"
                       oninput="checkStrength(this.value)"
                       required>

                <!-- Strength meter bar -->
                <div class="strength-bar-wrap">
                    <div class="strength-bar" id="strength-bar"></div>
                </div>
                <small id="strength-text" class="strength-text"></small>

                <!-- Requirements checklist -->
                <ul class="password-rules">
                    <li id="rule-length">  At least 8 characters</li>
                    <li id="rule-upper">   One uppercase letter (A-Z)</li>
                    <li id="rule-lower">   One lowercase letter (a-z)</li>
                    <li id="rule-number">  One number (0-9)</li>
                    <li id="rule-special"> One special character (@#$!%*?&)</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password"
                       name="confirm_password"
                       placeholder="Repeat your password"
                       oninput="checkMatch()"
                       required>
                <small id="match-text" class="strength-text"></small>
            </div>

            <button type="submit" class="btn-primary btn-full">
                Create Account
            </button>

        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="/bus-booking/public/index.php?page=login">Login here</a>
        </p>
    </div>
</div>

<script>
function checkStrength(password) {
    var bar      = document.getElementById('strength-bar');
    var text     = document.getElementById('strength-text');
    var score    = 0;

    // Check each rule and color the list item
    var ruleLength  = document.getElementById('rule-length');
    var ruleUpper   = document.getElementById('rule-upper');
    var ruleLower   = document.getElementById('rule-lower');
    var ruleNumber  = document.getElementById('rule-number');
    var ruleSpecial = document.getElementById('rule-special');

    if (password.length >= 8) {
        score++;
        ruleLength.style.color = '#2e7d32';
    } else {
        ruleLength.style.color = '#c62828';
    }

    if (/[A-Z]/.test(password)) {
        score++;
        ruleUpper.style.color = '#2e7d32';
    } else {
        ruleUpper.style.color = '#c62828';
    }

    if (/[a-z]/.test(password)) {
        score++;
        ruleLower.style.color = '#2e7d32';
    } else {
        ruleLower.style.color = '#c62828';
    }

    if (/[0-9]/.test(password)) {
        score++;
        ruleNumber.style.color = '#2e7d32';
    } else {
        ruleNumber.style.color = '#c62828';
    }

    if (/[\@\#\$\!\%\*\?\&]/.test(password)) {
        score++;
        ruleSpecial.style.color = '#2e7d32';
    } else {
        ruleSpecial.style.color = '#c62828';
    }

    // Update strength bar
    var width  = (score / 5) * 100;
    var color  = '#e53935';
    var label  = 'Very Weak';

    if (score === 2) { color = '#ff7043'; label = 'Weak';      }
    if (score === 3) { color = '#ffc107'; label = 'Fair';      }
    if (score === 4) { color = '#66bb6a'; label = 'Good';      }
    if (score === 5) { color = '#2e7d32'; label = 'Strong ✓';  }

    bar.style.width      = width + '%';
    bar.style.background = color;
    text.textContent     = label;
    text.style.color     = color;
}

function checkMatch() {
    var password = document.getElementById('password').value;
    var confirm  = document.getElementById('confirm_password').value;
    var text     = document.getElementById('match-text');

    if (confirm === '') {
        text.textContent = '';
        return;
    }

    if (password === confirm) {
        text.textContent = '✓ Passwords match';
        text.style.color = '#2e7d32';
    } else {
        text.textContent = '✗ Passwords do not match';
        text.style.color = '#c62828';
    }
}
</script>