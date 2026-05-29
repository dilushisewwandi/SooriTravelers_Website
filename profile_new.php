<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Soori Travels</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="icon" href="images/title_icon.png">
</head>
<body>

<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>
<script src="js/firebase-auth.js"></script>

<!-- Header -->
<header class="header">
    <div id="menu-btn" class="fas fa-bars"></div>
    <a data-aos="zoom-in-left" data-aos-delay="150" href="index.php" class="logo">Soori Travels</a>
    <nav class="navbar">
        <a data-aos="zoom-in-left" data-aos-delay="300" href="index.php#home">home</a>
        <a data-aos="zoom-in-left" data-aos-delay="450" href="index.php#about">about</a>
        <a data-aos="zoom-in-left" data-aos-delay="600" href="index.php#packages">packages</a>
        <a data-aos="zoom-in-left" data-aos-delay="750" href="index.php#destination">destination</a>
        <a data-aos="zoom-in-left" data-aos-delay="900" href="index.php#services">services</a>
        <a data-aos="zoom-in-left" data-aos-delay="1150" href="index.php#gallery">gallery</a>
        <a data-aos="zoom-in-left" data-aos-delay="1300" href="index.php#blogs">blogs</a>
    </nav>
    <div id="auth-nav-btn">
        <a data-aos="zoom-in-left" data-aos-delay="1300" href="#" class="btn" onclick="handleLogout()">sign out</a>
    </div>
</header>

<!-- Profile Layout -->
<div class="profile-wrapper">

    <!-- Sidebar -->
    <aside class="profile-sidebar" data-aos="fade-right">
        <div class="profile-avatar" onclick="document.getElementById('file').click()">
            <img src="images/profilepic.jpg" id="photo" alt="Profile">
            <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
            <input type="file" id="file" accept="image/*">
        </div>
        <div class="profile-user-name" id="sidebar-name">—</div>
        <div class="profile-user-email" id="sidebar-email">—</div>
        <div class="profile-member-badge"><i class="fas fa-gem"></i> Member</div>

        <ul class="profile-nav">
            <li><a href="#" id="tab-basicInfo" class="active-tab"><i class="fas fa-user"></i> <span>Basic Info</span></a></li>
            <li><a href="#" id="tab-security"><i class="fas fa-shield-alt"></i> <span>Security</span></a></li>
            <li><a href="#" id="tab-bookings"><i class="fas fa-suitcase-rolling"></i> <span>Bookings</span></a></li>
            <li><a href="#" id="tab-settings"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="profile-content" data-aos="fade-left" data-aos-delay="200">

        <!-- ===== BASIC INFO ===== -->
        <section id="basicInfoSection">
            <div class="section-header">
                <h2><i class="fas fa-user-circle"></i> Basic Information</h2>
                <p>Manage your personal details</p>
            </div>

            <div id="profile-message" class="profile-message"></div>

            <div class="profile-form-card" data-aos="fade-up" data-aos-delay="100">
                <form id="detailsForm" onsubmit="return false;">
                    <div class="profile-form-grid">
                        <div class="profile-form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" class="profile-input" readonly>
                        </div>
                        <div class="profile-form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="profile-input" readonly>
                        </div>
                        <div class="profile-form-group">
                            <label for="contact">Contact Number</label>
                            <input type="text" id="contact" name="contact" class="profile-input" readonly>
                        </div>
                        <div class="profile-form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" class="profile-input" readonly>
                        </div>
                    </div>

                    <div class="profile-actions" id="btnupdate">
                        <button type="button" class="profile-btn profile-btn--primary" onclick="toggleEditMode()">
                            <i class="fas fa-pen"></i>&nbsp; Edit Profile
                        </button>
                    </div>
                    <div class="profile-actions" id="btnedit" style="display:none;">
                        <button type="button" id="saveBtn" class="profile-btn profile-btn--primary" onclick="saveChanges()">
                            <i class="fas fa-check"></i>&nbsp; Save Changes
                        </button>
                        <button type="button" class="profile-btn profile-btn--outline" onclick="cancelChanges()">Cancel</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- ===== SECURITY ===== -->
        <section id="securitySection" style="display:none;">
            <div class="section-header">
                <h2><i class="fas fa-shield-alt"></i> Security</h2>
                <p>Manage your password and sessions</p>
            </div>

            <div id="security-message" class="profile-message"></div>

            <div class="security-card">
                <h3><i class="fas fa-key"></i>&nbsp; Password</h3>
                <p>A password reset link will be sent to your registered email address.</p>
                <button type="button" class="profile-btn profile-btn--primary" onclick="handlePasswordReset()">
                    <i class="fas fa-envelope"></i>&nbsp; Send Reset Email
                </button>
            </div>

            <div class="security-card">
                <h3><i class="fas fa-sign-out-alt"></i>&nbsp; Sign Out</h3>
                <p>Sign out from all devices and end your current session.</p>
                <button type="button" class="profile-btn profile-btn--outline" onclick="handleLogout()">Sign Out</button>
            </div>
        </section>

        <!-- ===== BOOKING HISTORY ===== -->
        <section id="bookingHistorySection" style="display:none;">
            <div class="section-header">
                <h2><i class="fas fa-suitcase-rolling"></i> Booking History</h2>
                <p>View your past and upcoming trips</p>
            </div>

            <div id="bookings-container">
                <div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading bookings...</div>
            </div>
        </section>

        <!-- ===== SETTINGS ===== -->
        <section id="settingsSection" style="display:none;">
            <div class="section-header">
                <h2><i class="fas fa-cog"></i> Settings</h2>
                <p>Account preferences and danger zone</p>
            </div>

            <div class="security-card" style="border-color:rgba(244,67,54,0.2);">
                <h3 style="color:#f44336;"><i class="fas fa-exclamation-triangle"></i>&nbsp; Delete Account</h3>
                <p>This action is irreversible. Your account and all associated data will be permanently removed.</p>
                <button type="button" class="profile-btn profile-btn--danger" onclick="handleDeleteAccount()">
                    <i class="fas fa-trash-alt"></i>&nbsp; Delete My Account
                </button>
            </div>
        </section>

    </main>
</div>

<script>
    // Store original values for cancel
    let originalValues = {};

    // Require authentication
    requireAuth();

    // ===== Tab Navigation =====
    const tabs = {
        'tab-basicInfo': 'basicInfoSection',
        'tab-security': 'securitySection',
        'tab-bookings': 'bookingHistorySection',
        'tab-settings': 'settingsSection'
    };

    Object.keys(tabs).forEach(function(tabId) {
        document.getElementById(tabId).addEventListener('click', function(e) {
            e.preventDefault();
            Object.values(tabs).forEach(function(sId) {
                document.getElementById(sId).style.display = 'none';
            });
            document.getElementById(tabs[tabId]).style.display = 'block';
            document.querySelectorAll('.profile-nav a').forEach(function(a) {
                a.classList.remove('active-tab');
            });
            this.classList.add('active-tab');
        });
    });

    // ===== Mobile menu toggle =====
    document.getElementById('menu-btn').addEventListener('click', function() {
        document.querySelector('.navbar').classList.toggle('active');
    });

    // ===== Auth state → load profile =====
    auth.onAuthStateChanged(async function(user) {
        if (user) {
            await loadProfile(user.uid);
        }
    });

    async function loadProfile(uid) {
        try {
            const response = await fetch('api/get_user.php?firebase_uid=' + encodeURIComponent(uid));
            const result = await response.json();

            if (result.success) {
                const data = result.data;
                document.getElementById('fullname').value = data.name || '';
                document.getElementById('email').value = data.email || '';
                document.getElementById('contact').value = data.phone || '';
                document.getElementById('nationality').value = data.country || '';

                // Sidebar info
                document.getElementById('sidebar-name').textContent = data.name || '—';
                document.getElementById('sidebar-email').textContent = data.email || '—';

                originalValues = {
                    fullname: data.name || '',
                    contact: data.phone || '',
                    nationality: data.country || ''
                };

                renderBookings(data.bookings || []);
            }
        } catch (error) {
            console.error('Failed to load profile:', error);
        }
    }

    // ===== Bookings =====
    function renderBookings(bookings) {
        const container = document.getElementById('bookings-container');

        if (bookings.length === 0) {
            container.innerHTML =
                '<div class="empty-state">' +
                    '<i class="fas fa-suitcase-rolling"></i>' +
                    '<h3>No bookings yet</h3>' +
                    '<p>Ready for your next adventure? <a href="index.php#packages">Browse packages</a></p>' +
                '</div>';
            return;
        }

        let html = '<div class="bookings-table-wrap"><table class="bookings-table">';
        html += '<thead><tr><th>Package</th><th>Vehicle</th><th>Date</th><th>People</th><th>Status</th></tr></thead><tbody>';

        bookings.forEach(function(b) {
            const statusClass = b.booking_status === 'confirmed' ? 'status-badge--confirmed' :
                                b.booking_status === 'cancelled' ? 'status-badge--cancelled' : 'status-badge--pending';
            html += '<tr>';
            html += '<td>' + escapeHtml(b.package_title) + '</td>';
            html += '<td>' + escapeHtml(b.vehicle_name) + '</td>';
            html += '<td>' + escapeHtml(b.travel_date) + '</td>';
            html += '<td>' + b.number_of_people + '</td>';
            html += '<td><span class="status-badge ' + statusClass + '">' + escapeHtml(b.booking_status) + '</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ===== Edit / Save / Cancel =====
    function toggleEditMode() {
        ['fullname', 'contact', 'nationality'].forEach(function(id) {
            document.getElementById(id).removeAttribute('readonly');
        });
        document.getElementById('btnupdate').style.display = 'none';
        document.getElementById('btnedit').style.display = 'flex';
    }

    function cancelChanges() {
        document.getElementById('fullname').value = originalValues.fullname;
        document.getElementById('contact').value = originalValues.contact;
        document.getElementById('nationality').value = originalValues.nationality;

        ['fullname', 'contact', 'nationality'].forEach(function(id) {
            document.getElementById(id).setAttribute('readonly', true);
        });

        document.getElementById('btnupdate').style.display = 'flex';
        document.getElementById('btnedit').style.display = 'none';
    }

    async function saveChanges() {
        const user = getCurrentUser();
        if (!user) return;

        const msgDiv = document.getElementById('profile-message');
        const saveBtn = document.getElementById('saveBtn');

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp; Saving...';

        try {
            const response = await fetch('api/update_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    firebase_uid: user.uid,
                    name: document.getElementById('fullname').value.trim(),
                    phone: document.getElementById('contact').value.trim(),
                    country: document.getElementById('nationality').value.trim()
                })
            });

            const result = await response.json();

            if (result.success) {
                msgDiv.className = 'profile-message success';
                msgDiv.textContent = 'Profile updated successfully!';

                originalValues.fullname = document.getElementById('fullname').value.trim();
                originalValues.contact = document.getElementById('contact').value.trim();
                originalValues.nationality = document.getElementById('nationality').value.trim();

                // Update sidebar
                document.getElementById('sidebar-name').textContent = originalValues.fullname || '—';

                cancelChanges();
            } else {
                msgDiv.className = 'profile-message error';
                msgDiv.textContent = result.message || 'Update failed';
            }
        } catch (error) {
            msgDiv.className = 'profile-message error';
            msgDiv.textContent = 'An error occurred. Please try again.';
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-check"></i>&nbsp; Save Changes';
            setTimeout(function() { msgDiv.className = 'profile-message'; }, 3000);
        }
    }

    // ===== Password Reset =====
    async function handlePasswordReset() {
        const user = getCurrentUser();
        if (!user || !user.email) return;

        const msgDiv = document.getElementById('security-message');
        try {
            await resetPassword(user.email);
            msgDiv.className = 'profile-message success';
            msgDiv.textContent = 'Password reset email sent to ' + user.email;
        } catch (error) {
            msgDiv.className = 'profile-message error';
            msgDiv.textContent = error.message || 'Failed to send reset email';
        }
        setTimeout(function() { msgDiv.className = 'profile-message'; }, 5000);
    }

    // ===== Logout =====
    async function handleLogout() {
        await logout();
    }

    // ===== Delete Account =====
    function handleDeleteAccount() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            if (confirm('This will permanently remove all your data. Type OK to confirm.')) {
                alert('Please contact support to delete your account.');
            }
        }
    }

    // ===== Profile picture preview =====
    document.getElementById('file').addEventListener('change', function() {
        const chosen = this.files[0];
        if (chosen) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo').src = e.target.result;
            };
            reader.readAsDataURL(chosen);
        }
    });
</script>

<!-- AOS animation library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init({ duration: 800, offset: 100 });</script>

</body>
</html>
