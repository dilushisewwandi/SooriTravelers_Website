<?php
/**
 * Soori Travels - Registration Page (Refactored with Firebase Auth)
 */
$pageTitle = 'Customer Registration - Soori Travels';
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
        <img src="images/registration_banner.jpg" alt="Registration Banner">
        <div class="auth-hero-overlay" data-aos="fade-up">
            <h1>Join Us</h1>
            <div class="breadcrumb"><a href="index.php">Home</a> &nbsp;/&nbsp; Registration</div>
        </div>
    </div>

    <!-- registration form -->
    <section class="auth-section">
        <div class="auth-card auth-card--wide" data-aos="fade-up" data-aos-delay="200">
            <div class="auth-card-header">
                <div class="auth-icon"><i class="fas fa-user-plus"></i></div>
                <h2>Create Account</h2>
                <p>Start your journey with Soori Travels</p>
            </div>

            <div id="regMessage" class="auth-message"></div>

            <form id="data-form" onsubmit="handleRegister(event)">
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" id="name" class="auth-input" placeholder="John Doe" oninput="alphabeticInput(event)" required>
                    </div>
                    <div class="auth-form-group">
                        <label>Country <span class="required">*</span></label>
                        <select id="countrycmb" class="auth-input" required>
                            <option value="" hidden>Select your country</option>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Albania">Albania</option>
                            <option value="Algeria">Algeria</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Australia">Australia</option>
                            <option value="Austria">Austria</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belgium">Belgium</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Denmark">Denmark</option>
                            <option value="Egypt">Egypt</option>
                            <option value="Finland">Finland</option>
                            <option value="France">France</option>
                            <option value="Germany">Germany</option>
                            <option value="Greece">Greece</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Ireland">Ireland</option>
                            <option value="Italy">Italy</option>
                            <option value="Japan">Japan</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Maldives">Maldives</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Netherlands">Netherlands</option>
                            <option value="New Zealand">New Zealand</option>
                            <option value="Norway">Norway</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Poland">Poland</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Russia">Russia</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Singapore">Singapore</option>
                            <option value="South Africa">South Africa</option>
                            <option value="South Korea">South Korea</option>
                            <option value="Spain">Spain</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sweden">Sweden</option>
                            <option value="Switzerland">Switzerland</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Turkey">Turkey</option>
                            <option value="UAE">United Arab Emirates</option>
                            <option value="UK">United Kingdom</option>
                            <option value="USA">United States</option>
                            <option value="Vietnam">Vietnam</option>
                        </select>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" id="email" class="auth-input" placeholder="you@example.com" required>
                </div>

                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label>Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="password" class="auth-input" placeholder="Min 6 characters" minlength="6" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="auth-form-group">
                        <label>Contact Number <span class="required">*</span></label>
                        <input type="tel" id="contact_no" class="auth-input" placeholder="+94 77 123 4567" required>
                    </div>
                </div>

                <button type="submit" id="regBtn" class="auth-btn">
                    <i class="fas fa-user-plus"></i>&nbsp; Create Account
                </button>

                <div class="auth-footer-links">
                    <p>Already have an account? <a href="login_new.php">Sign in</a></p>
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

        function alphabeticInput(event) {
            event.target.value = event.target.value.replace(/[^a-zA-Z\s]/g, '');
        }

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

        // Redirect if already logged in (not during active registration)
        let isRegistering = false;
        auth.onAuthStateChanged(function(user) {
            if (user && !isRegistering) window.location.href = 'index.php';
        });

        async function handleRegister(event) {
            event.preventDefault();
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const phone = document.getElementById('contact_no').value.trim();
            const country = document.getElementById('countrycmb').value;

            const msgDiv = document.getElementById('regMessage');
            const btn = document.getElementById('regBtn');

            if (!name || !email || !password || !phone || !country) {
                msgDiv.className = 'auth-message error';
                msgDiv.textContent = 'Please fill in all required fields';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp; Creating account...';
            msgDiv.className = 'auth-message';
            msgDiv.style.display = 'none';

            isRegistering = true;
            const result = await registerWithEmail(name, email, password, phone, country);

            if (result.success) {
                msgDiv.className = 'auth-message success';
                msgDiv.textContent = 'Account created successfully! Redirecting...';
                setTimeout(function() { window.location.href = 'index.php'; }, 2000);
            } else {
                msgDiv.className = 'auth-message error';
                msgDiv.textContent = result.message;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-user-plus"></i>&nbsp; Create Account';
            }
        }

        // Mobile nav toggle
        document.getElementById('menu-btn').onclick = function() {
            document.querySelector('.navbar').classList.toggle('active');
        };
    </script>
</body>
</html>
