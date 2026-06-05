<div class="auth-container">
    <div class="auth-box">
        <h2>Login</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="/bus-booking/public/index.php?page=login" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="btn-primary btn-full">Login</button>
        </form>

        <p class="auth-switch">
            Don't have an account?
            <a href="/bus-booking/public/index.php?page=register">Register here</a>
        </p>
    </div>
</div>