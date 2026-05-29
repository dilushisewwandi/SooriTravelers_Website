<?php
/**
 * Soori Travels - Login Page (Refactored with Firebase Auth)
 */
$pageTitle = 'Login - Soori Travels';
$basePath = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="icon" href="images/title_icon.png">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>
    <script src="js/firebase-auth.js"></script>
</head>
<body>
    <!-- header -->
    <header class="header">
        <div id="menu-btn" class="fas fa-bars"></div>
        <a href="index.php" class="logo">Soori Travels</a>
        <nav class="navbar"><a href="index.php#home">home</a></nav>
        <a href="index.php" class="btn">home</a>
    </header>

    <!-- hero banner -->
    <div class="auth-hero">
        <img src="images/registration_banner.jpg" alt="Login Banner">
        <div class="auth-hero-overlay" data-aos="fade-up">
            <h1>Welcome Back</h1>
            <div class="breadcrumb"><a href="index.php">Home</a> &nbsp;/&nbsp; Login</div>
        </div>
    </div>

    <!-- login form -->
    <section class="auth-section">
        <div class="auth-card" data-aos="fade-up" data-aos-delay="200">
            <div class="auth-card-header">
                <div class="auth-icon"><i class="fas fa-user"></i></div>
                <h2>Sign In</h2>
                <p>Access your travel dashboard</p>
            </div>

            <div id="loginMessage" class="auth-message"></div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="auth-form-group">
                    <label>Email Address</label>
                    <input type="email" id="email" class="auth-input" placeholder="you@example.com" required>
                </div>

                <div class="auth-form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" class="auth-input" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="loginbtn" class="auth-btn">
                    <i class="fas fa-sign-in-alt"></i>&nbsp; Login
                </button>

                <div class="auth-footer-links">
                    <a href="#" onclick="handleForgotPassword(event)"><i class="fas fa-lock"></i>&nbsp; Forgot Password?</a>
                    <p>Don't have an account? <a href="register_new.php">Create one</a></p>
                </div>
            </form>
        </div>
    </section>

    <!-- footer -->
    <div class="auth-copyright">
        <span>Copyright &copy; <?php echo date('Y'); ?> www.Sooritravels.com. All rights reserved</span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 800, offset: 100 });

        // Redirect if already logged in (not during active login)
        let isLoggingIn = false;
        auth.onAuthStateChanged(function(user) {
            if (user && !isLoggingIn) window.location.href = 'index.php';
        });

        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        async function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const msgDiv = document.getElementById('loginMessage');
            const btn = document.getElementById('loginbtn');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp; Logging in...';
            msgDiv.className = 'auth-message';
            msgDiv.style.display = 'none';

            isLoggingIn = true;
            const result = await loginWithEmail(email, password);

            if (result.success) {
                msgDiv.className = 'auth-message success';
                msgDiv.textContent = 'Login successful! Redirecting...';
                setTimeout(function() { window.location.href = 'index.php'; }, 1500);
            } else {
                msgDiv.className = 'auth-message error';
                msgDiv.textContent = result.message;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sign-in-alt"></i>&nbsp; Login';
            }
        }

        async function handleForgotPassword(event) {
            event.preventDefault();
            const email = prompt('Enter your email address for password reset:');
            if (!email) return;

            const result = await resetPassword(email);
            const msgDiv = document.getElementById('loginMessage');
            if (result.success !== false) {
                msgDiv.className = 'auth-message success';
                msgDiv.textContent = result.message || 'Password reset email sent!';
            } else {
                msgDiv.className = 'auth-message error';
                msgDiv.textContent = result.message || 'Failed to send reset email.';
            }
        }

        // Mobile nav toggle
        document.getElementById('menu-btn').onclick = function() {
            document.querySelector('.navbar').classList.toggle('active');
        };
    </script>
</body>
</html>
