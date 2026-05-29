<?php
/**
 * Soori Travels - Admin Login (Refactored)
 * Session-based admin authentication via API
 */
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Soori Travels</title>
    <link rel="icon" href="../images/title_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-login-wrapper">
        <div class="login-box">
            <div class="login-icon"><i class="fas fa-shield-alt"></i></div>
            <h1>Admin Panel</h1>
            <p>Soori Travels Administration</p>

            <div id="login-message"></div>

            <form id="admin-login-form" onsubmit="handleAdminLogin(event)">
                <div class="admin-form-group">
                    <label>Email</label>
                    <input type="email" id="admin-email" class="admin-input" placeholder="admin@soorittravels.com" required>
                </div>
                <div class="admin-form-group">
                    <label>Password</label>
                    <input type="password" id="admin-password" class="admin-input" placeholder="Enter password" required>
                </div>
                <button type="submit" class="admin-btn admin-btn-primary" id="login-btn">
                    Login
                </button>
            </form>
            <a href="../index.php" class="login-back-link"><i class="fas fa-arrow-left"></i> Back to Website</a>
        </div>
    </div>

    <script>
        async function handleAdminLogin(event) {
            event.preventDefault();
            const msgDiv = document.getElementById('login-message');
            const btn = document.getElementById('login-btn');

            btn.disabled = true;
            btn.textContent = 'Logging in...';

            const email = document.getElementById('admin-email').value.trim();
            const password = document.getElementById('admin-password').value;

            try {
                const response = await fetch('../api/admin_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email, password: password })
                });

                const result = await response.json();
                msgDiv.style.display = 'block';

                if (result.success) {
                    msgDiv.style.color = '#4CAF50';
                    msgDiv.textContent = 'Login successful! Redirecting...';
                    setTimeout(function() {
                        window.location.href = 'dashboard.php';
                    }, 1000);
                } else {
                    msgDiv.style.color = '#f44336';
                    msgDiv.textContent = result.message || 'Invalid credentials';
                }
            } catch (error) {
                msgDiv.style.display = 'block';
                msgDiv.style.color = '#f44336';
                msgDiv.textContent = 'Connection error. Please try again.';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Login';
            }
        }
    </script>
</body>
</html>
