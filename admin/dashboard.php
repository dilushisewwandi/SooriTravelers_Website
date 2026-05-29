<?php
/**
 * Soori Travels - Admin Dashboard (Refactored)
 * Central admin panel with sidebar navigation
 */
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Soori Travels</title>
    <link rel="icon" href="../images/title_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>
<body>

<!-- Header -->
<header class="admin-header">
    <div style="display:flex; align-items:center; gap:1.5rem;">
        <button id="sidebar-toggle" onclick="document.querySelector('.admin-sidebar').classList.toggle('active')"><i class="fas fa-bars"></i></button>
        <a href="dashboard.php" class="logo">Soori Travels <small>Admin</small></a>
    </div>
    <span class="admin-user"><i class="fas fa-user-circle" style="margin-right:0.5rem;"></i> <?php echo htmlspecialchars($adminName); ?></span>
    <a href="#" onclick="adminLogout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</header>

<!-- Sidebar -->
<nav class="admin-sidebar" data-aos="fade-right">
    <a href="#" class="active" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#" data-section="bookings"><i class="fas fa-calendar-check"></i> Bookings</a>
    <a href="#" data-section="packages"><i class="fas fa-box"></i> Packages</a>
    <a href="#" data-section="vehicles"><i class="fas fa-bus"></i> Vehicles</a>
    <a href="#" data-section="services"><i class="fas fa-concierge-bell"></i> Services</a>
    <a href="#" data-section="destinations"><i class="fas fa-map-marker-alt"></i> Destinations</a>
    <a href="#" data-section="gallery"><i class="fas fa-images"></i> Gallery</a>
    <a href="#" data-section="testimonials"><i class="fas fa-star"></i> Testimonials</a>
    <a href="#" data-section="media"><i class="fas fa-photo-video"></i> Media</a>
</nav>

<!-- Main Content -->
<main class="admin-main" data-aos="fade-left">

    <!-- Dashboard Section -->
    <div id="section-dashboard" class="admin-section">
        <h1>Dashboard</h1>
        <div class="stats-grid" id="stats-grid" data-aos="fade-up">
            <div class="stat-card"><div class="stat-number" id="stat-bookings">-</div><div class="stat-label">Total Bookings</div></div>
            <div class="stat-card"><div class="stat-number" id="stat-pending">-</div><div class="stat-label">Pending</div></div>
            <div class="stat-card"><div class="stat-number" id="stat-packages">-</div><div class="stat-label">Packages</div></div>
            <div class="stat-card"><div class="stat-number" id="stat-vehicles">-</div><div class="stat-label">Vehicles</div></div>
        </div>
        <h2>Recent Bookings</h2>
        <div class="admin-table-container">
            <table class="admin-table" id="recent-bookings-table">
                <thead><tr><th>Customer</th><th>Package</th><th>Date</th><th>Status</th></tr></thead>
                <tbody><tr><td colspan="4">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Bookings Section -->
    <div id="section-bookings" class="admin-section" style="display:none;">
        <h1>Manage Bookings</h1>
        <div class="admin-toolbar">
            <input type="text" class="admin-search" id="booking-search" placeholder="Search by customer name or email...">
            <select class="admin-filter" id="booking-status-filter">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="bookings-table">
                <thead><tr><th>ID</th><th>Customer</th><th>Email</th><th>Package</th><th>Vehicle</th><th>Date</th><th>People</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="9">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Packages Section -->
    <div id="section-packages" class="admin-section" style="display:none;">
        <h1>Manage Packages</h1>
        <div class="admin-toolbar">
            <button class="admin-btn admin-btn-primary" onclick="openModal('package-modal')"><i class="fas fa-plus"></i> Add Package</button>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="packages-table">
                <thead><tr><th>ID</th><th>Title</th><th>Location</th><th>Price</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="7">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Vehicles Section -->
    <div id="section-vehicles" class="admin-section" style="display:none;">
        <h1>Manage Vehicles</h1>
        <div class="admin-toolbar">
            <button class="admin-btn admin-btn-primary" onclick="openModal('vehicle-modal')"><i class="fas fa-plus"></i> Add Vehicle</button>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="vehicles-table">
                <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Seats</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="6">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Services Section -->
    <div id="section-services" class="admin-section" style="display:none;">
        <h1>Manage Services</h1>
        <div class="admin-toolbar">
            <button class="admin-btn admin-btn-primary" onclick="openModal('service-modal')"><i class="fas fa-plus"></i> Add Service</button>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="services-table">
                <thead><tr><th>ID</th><th>Title</th><th>Description</th><th>Icon</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="5">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Destinations Section -->
    <div id="section-destinations" class="admin-section" style="display:none;">
        <h1>Manage Destinations</h1>
        <div class="admin-toolbar">
            <button class="admin-btn admin-btn-primary" onclick="openModal('destination-modal')"><i class="fas fa-plus"></i> Add Destination</button>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="destinations-table">
                <thead><tr><th>ID</th><th>Name</th><th>Country</th><th>Image</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="5">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Gallery Section -->
    <div id="section-gallery" class="admin-section" style="display:none;">
        <h1>Manage Gallery</h1>
        <div class="admin-toolbar">
            <button class="admin-btn admin-btn-primary" onclick="openModal('gallery-modal')"><i class="fas fa-plus"></i> Add Image</button>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="gallery-table">
                <thead><tr><th>ID</th><th>Title</th><th>Image</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="4">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div id="section-testimonials" class="admin-section" style="display:none;">
        <h1>Manage Testimonials</h1>
        <div class="admin-toolbar">
            <button class="admin-btn admin-btn-primary" onclick="openModal('testimonial-modal')"><i class="fas fa-plus"></i> Add Testimonial</button>
        </div>
        <div class="admin-table-container">
            <table class="admin-table" id="testimonials-table">
                <thead><tr><th>ID</th><th>Customer</th><th>Rating</th><th>Text</th><th>Actions</th></tr></thead>
                <tbody><tr><td colspan="5">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>

    <!-- Media Section -->
    <div id="section-media" class="admin-section" style="display:none;">
        <h1>Media Manager</h1>
        <div class="admin-toolbar">
            <form id="media-upload-form" enctype="multipart/form-data" style="display:flex; gap:1rem; align-items:center;">
                <input type="file" id="media-file" class="admin-input" style="max-width:30rem;" accept="image/*,video/*">
                <button type="submit" class="admin-btn admin-btn-primary">Upload</button>
            </form>
            <select class="admin-filter" id="media-type-filter" onchange="loadMedia()">
                <option value="">All Types</option>
                <option value="image">Images</option>
                <option value="video">Videos</option>
            </select>
        </div>
        <div id="media-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(15rem, 1fr)); gap:1.5rem;"></div>
    </div>

</main>

<!-- ===== MODALS ===== -->

<!-- Package Modal -->
<div class="admin-modal-overlay" id="package-modal">
    <div class="admin-modal">
        <h2 id="package-modal-title">Add Package</h2>
        <form id="package-form" onsubmit="savePackage(event)">
            <input type="hidden" id="pkg-id">
            <div class="admin-form-group"><label>Title</label><input type="text" id="pkg-title" class="admin-input" required></div>
            <div class="admin-form-group"><label>Description</label><textarea id="pkg-description" class="admin-textarea"></textarea></div>
            <div class="admin-form-group"><label>Location</label><input type="text" id="pkg-location" class="admin-input"></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="admin-form-group"><label>Price ($)</label><input type="number" id="pkg-price" class="admin-input" step="0.01" required></div>
                <div class="admin-form-group"><label>Duration (days)</label><input type="number" id="pkg-duration" class="admin-input" min="1" required></div>
            </div>
            <div class="admin-form-group"><label>Thumbnail URL</label><input type="text" id="pkg-thumbnail" class="admin-input"></div>
            <div class="admin-form-group">
                <label>Status</label>
                <select id="pkg-status" class="admin-select"><option value="active">Active</option><option value="inactive">Inactive</option></select>
            </div>
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="admin-btn admin-btn-primary">Save</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="closeModal('package-modal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Vehicle Modal -->
<div class="admin-modal-overlay" id="vehicle-modal">
    <div class="admin-modal">
        <h2 id="vehicle-modal-title">Add Vehicle</h2>
        <form id="vehicle-form" onsubmit="saveVehicle(event)">
            <input type="hidden" id="veh-id">
            <div class="admin-form-group"><label>Vehicle Name</label><input type="text" id="veh-name" class="admin-input" required></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="admin-form-group"><label>Type</label><input type="text" id="veh-type" class="admin-input" required></div>
                <div class="admin-form-group"><label>Seat Capacity</label><input type="number" id="veh-seats" class="admin-input" min="1" required></div>
            </div>
            <div class="admin-form-group"><label>Image URL</label><input type="text" id="veh-image" class="admin-input"></div>
            <div class="admin-form-group">
                <label>Status</label>
                <select id="veh-status" class="admin-select"><option value="available">Available</option><option value="maintenance">Maintenance</option></select>
            </div>
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="admin-btn admin-btn-primary">Save</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="closeModal('vehicle-modal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Service Modal -->
<div class="admin-modal-overlay" id="service-modal">
    <div class="admin-modal">
        <h2 id="service-modal-title">Add Service</h2>
        <form id="service-form" onsubmit="saveService(event)">
            <input type="hidden" id="svc-id">
            <div class="admin-form-group"><label>Title</label><input type="text" id="svc-title" class="admin-input" required></div>
            <div class="admin-form-group"><label>Description</label><textarea id="svc-description" class="admin-textarea"></textarea></div>
            <div class="admin-form-group"><label>Icon Class (Font Awesome)</label><input type="text" id="svc-icon" class="admin-input" placeholder="fas fa-hotel"></div>
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="admin-btn admin-btn-primary">Save</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="closeModal('service-modal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Destination Modal -->
<div class="admin-modal-overlay" id="destination-modal">
    <div class="admin-modal">
        <h2 id="destination-modal-title">Add Destination</h2>
        <form id="destination-form" onsubmit="saveDestination(event)">
            <input type="hidden" id="dest-id">
            <div class="admin-form-group"><label>Name</label><input type="text" id="dest-name" class="admin-input" required></div>
            <div class="admin-form-group"><label>Country</label><input type="text" id="dest-country" class="admin-input"></div>
            <div class="admin-form-group"><label>Description</label><textarea id="dest-description" class="admin-textarea"></textarea></div>
            <div class="admin-form-group"><label>Image URL</label><input type="text" id="dest-image" class="admin-input"></div>
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="admin-btn admin-btn-primary">Save</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="closeModal('destination-modal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Gallery Modal -->
<div class="admin-modal-overlay" id="gallery-modal">
    <div class="admin-modal">
        <h2 id="gallery-modal-title">Add Gallery Image</h2>
        <form id="gallery-form" onsubmit="saveGalleryItem(event)">
            <input type="hidden" id="gal-id">
            <div class="admin-form-group"><label>Title</label><input type="text" id="gal-title" class="admin-input" required></div>
            <div class="admin-form-group"><label>Image URL</label><input type="text" id="gal-image" class="admin-input" required></div>
            <div class="admin-form-group"><label>Category</label><input type="text" id="gal-category" class="admin-input"></div>
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="admin-btn admin-btn-primary">Save</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="closeModal('gallery-modal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Testimonial Modal -->
<div class="admin-modal-overlay" id="testimonial-modal">
    <div class="admin-modal">
        <h2 id="testimonial-modal-title">Add Testimonial</h2>
        <form id="testimonial-form" onsubmit="saveTestimonial(event)">
            <input type="hidden" id="test-id">
            <div class="admin-form-group"><label>Customer Name</label><input type="text" id="test-name" class="admin-input" required></div>
            <div class="admin-form-group"><label>Review Text</label><textarea id="test-text" class="admin-textarea" required></textarea></div>
            <div class="admin-form-group"><label>Rating (1-5)</label><input type="number" id="test-rating" class="admin-input" min="1" max="5" required></div>
            <div class="admin-form-group"><label>Profile Image URL</label><input type="text" id="test-image" class="admin-input"></div>
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="admin-btn admin-btn-primary">Save</button>
                <button type="button" class="admin-btn admin-btn-danger" onclick="closeModal('testimonial-modal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
const API_BASE = '../api/';

// ===== NAVIGATION =====
document.querySelectorAll('.admin-sidebar a').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const section = this.getAttribute('data-section');

        // Update active states
        document.querySelectorAll('.admin-sidebar a').forEach(function(a) { a.classList.remove('active'); });
        this.classList.add('active');

        // Show/hide sections
        document.querySelectorAll('.admin-section').forEach(function(s) { s.style.display = 'none'; });
        document.getElementById('section-' + section).style.display = 'block';

        // Load data for the section
        loadSectionData(section);
    });
});

function loadSectionData(section) {
    switch(section) {
        case 'dashboard': loadDashboard(); break;
        case 'bookings': loadBookings(); break;
        case 'packages': loadPackages(); break;
        case 'vehicles': loadVehicles(); break;
        case 'services': loadServices(); break;
        case 'destinations': loadDestinations(); break;
        case 'gallery': loadGallery(); break;
        case 'testimonials': loadTestimonials(); break;
        case 'media': loadMedia(); break;
    }
}

// ===== MODAL HELPERS =====
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ===== LOGOUT =====
async function adminLogout() {
    await fetch(API_BASE + 'admin_logout.php');
    window.location.href = 'login.php';
}

// ===== DASHBOARD =====
async function loadDashboard() {
    try {
        const [bookingsRes, packagesRes, vehiclesRes] = await Promise.all([
            fetch(API_BASE + 'admin_get_bookings.php'),
            fetch(API_BASE + 'admin_packages.php'),
            fetch(API_BASE + 'admin_vehicles.php')
        ]);

        const bookings = await bookingsRes.json();
        const packages = await packagesRes.json();
        const vehicles = await vehiclesRes.json();

        if (bookings.success) {
            const data = bookings.data;
            document.getElementById('stat-bookings').textContent = data.length;
            document.getElementById('stat-pending').textContent = data.filter(function(b) { return b.booking_status === 'pending'; }).length;

            // Recent bookings (last 5)
            const tbody = document.querySelector('#recent-bookings-table tbody');
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No bookings yet</td></tr>';
            } else {
                tbody.innerHTML = data.slice(0, 5).map(function(b) {
                    return '<tr><td>' + escapeHtml(b.customer_name) + '</td><td>' + escapeHtml(b.package_title) + '</td><td>' + escapeHtml(b.travel_date) + '</td><td><span class="badge badge-' + b.booking_status + '">' + b.booking_status + '</span></td></tr>';
                }).join('');
            }
        }

        if (packages.success) document.getElementById('stat-packages').textContent = packages.data.length;
        if (vehicles.success) document.getElementById('stat-vehicles').textContent = vehicles.data.length;
    } catch (error) {
        console.error('Dashboard load error:', error);
    }
}

// ===== BOOKINGS =====
let allBookings = [];

async function loadBookings() {
    try {
        const response = await fetch(API_BASE + 'admin_get_bookings.php');
        const result = await response.json();
        if (result.success) {
            allBookings = result.data;
            renderBookings(allBookings);
        }
    } catch (error) { console.error('Load bookings error:', error); }
}

function renderBookings(bookings) {
    const tbody = document.querySelector('#bookings-table tbody');
    if (bookings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;">No bookings found</td></tr>';
        return;
    }
    tbody.innerHTML = bookings.map(function(b) {
        const actions = b.booking_status === 'pending'
            ? '<button class="admin-btn admin-btn-success admin-btn-sm" onclick="updateBookingStatus(' + b.id + ', \'confirmed\')">Confirm</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="updateBookingStatus(' + b.id + ', \'cancelled\')">Cancel</button>'
            : '<span class="badge badge-' + b.booking_status + '">' + b.booking_status + '</span>';
        return '<tr><td>' + b.id + '</td><td>' + escapeHtml(b.customer_name) + '</td><td>' + escapeHtml(b.customer_email) + '</td><td>' + escapeHtml(b.package_title) + '</td><td>' + escapeHtml(b.vehicle_name) + '</td><td>' + escapeHtml(b.travel_date) + '</td><td>' + b.number_of_people + '</td><td><span class="badge badge-' + b.booking_status + '">' + b.booking_status + '</span></td><td>' + actions + '</td></tr>';
    }).join('');
}

async function updateBookingStatus(bookingId, status) {
    if (!confirm('Change booking #' + bookingId + ' to ' + status + '?')) return;
    try {
        const response = await fetch(API_BASE + 'admin_update_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ booking_id: bookingId, status: status })
        });
        const result = await response.json();
        if (result.success) loadBookings();
        else alert(result.message || 'Failed to update');
    } catch (error) { alert('Error updating booking'); }
}

// Booking search & filter
document.getElementById('booking-search').addEventListener('input', filterBookings);
document.getElementById('booking-status-filter').addEventListener('change', filterBookings);

function filterBookings() {
    const search = document.getElementById('booking-search').value.toLowerCase();
    const status = document.getElementById('booking-status-filter').value;
    const filtered = allBookings.filter(function(b) {
        const matchSearch = !search || (b.customer_name || '').toLowerCase().includes(search) || (b.customer_email || '').toLowerCase().includes(search);
        const matchStatus = !status || b.booking_status === status;
        return matchSearch && matchStatus;
    });
    renderBookings(filtered);
}

// ===== PACKAGES =====
async function loadPackages() {
    try {
        const response = await fetch(API_BASE + 'admin_packages.php');
        const result = await response.json();
        if (result.success) {
            const tbody = document.querySelector('#packages-table tbody');
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No packages</td></tr>';
                return;
            }
            tbody.innerHTML = result.data.map(function(p) {
                return '<tr><td>' + p.id + '</td><td>' + escapeHtml(p.title) + '</td><td>' + escapeHtml(p.location) + '</td><td>$' + p.price + '</td><td>' + p.duration_days + ' days</td><td><span class="badge badge-' + p.status + '">' + p.status + '</span></td><td><button class="admin-btn admin-btn-primary admin-btn-sm" onclick="editPackage(' + p.id + ')">Edit</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="deleteItem(\'admin_packages.php\', ' + p.id + ', loadPackages)">Delete</button></td></tr>';
            }).join('');
        }
    } catch (error) { console.error('Load packages error:', error); }
}

function editPackage(id) {
    fetch(API_BASE + 'get_packages.php?id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                const p = result.data;
                document.getElementById('pkg-id').value = p.id;
                document.getElementById('pkg-title').value = p.title || '';
                document.getElementById('pkg-description').value = p.description || '';
                document.getElementById('pkg-location').value = p.location || '';
                document.getElementById('pkg-price').value = p.price;
                document.getElementById('pkg-duration').value = p.duration_days;
                document.getElementById('pkg-thumbnail').value = p.thumbnail_url || '';
                document.getElementById('pkg-status').value = p.status || 'active';
                document.getElementById('package-modal-title').textContent = 'Edit Package';
                openModal('package-modal');
            }
        });
}

async function savePackage(event) {
    event.preventDefault();
    const id = document.getElementById('pkg-id').value;
    const data = {
        title: document.getElementById('pkg-title').value.trim(),
        description: document.getElementById('pkg-description').value.trim(),
        location: document.getElementById('pkg-location').value.trim(),
        price: parseFloat(document.getElementById('pkg-price').value),
        duration_days: parseInt(document.getElementById('pkg-duration').value),
        thumbnail_url: document.getElementById('pkg-thumbnail').value.trim(),
        status: document.getElementById('pkg-status').value
    };
    if (id) data.id = parseInt(id);

    try {
        const response = await fetch(API_BASE + 'admin_packages.php', {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            closeModal('package-modal');
            document.getElementById('package-form').reset();
            document.getElementById('pkg-id').value = '';
            document.getElementById('package-modal-title').textContent = 'Add Package';
            loadPackages();
        } else { alert(result.message || 'Save failed'); }
    } catch (error) { alert('Error saving package'); }
}

// ===== VEHICLES =====
async function loadVehicles() {
    try {
        const response = await fetch(API_BASE + 'admin_vehicles.php');
        const result = await response.json();
        if (result.success) {
            const tbody = document.querySelector('#vehicles-table tbody');
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No vehicles</td></tr>';
                return;
            }
            tbody.innerHTML = result.data.map(function(v) {
                return '<tr><td>' + v.id + '</td><td>' + escapeHtml(v.vehicle_name) + '</td><td>' + escapeHtml(v.vehicle_type) + '</td><td>' + v.seat_capacity + '</td><td><span class="badge badge-' + v.status + '">' + v.status + '</span></td><td><button class="admin-btn admin-btn-primary admin-btn-sm" onclick="editVehicle(' + v.id + ')">Edit</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="deleteItem(\'admin_vehicles.php\', ' + v.id + ', loadVehicles)">Delete</button></td></tr>';
            }).join('');
        }
    } catch (error) { console.error('Load vehicles error:', error); }
}

function editVehicle(id) {
    fetch(API_BASE + 'admin_vehicles.php')
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                const v = result.data.find(function(x) { return x.id == id; });
                if (v) {
                    document.getElementById('veh-id').value = v.id;
                    document.getElementById('veh-name').value = v.vehicle_name || '';
                    document.getElementById('veh-type').value = v.vehicle_type || '';
                    document.getElementById('veh-seats').value = v.seat_capacity;
                    document.getElementById('veh-image').value = v.vehicle_image || '';
                    document.getElementById('veh-status').value = v.status || 'available';
                    document.getElementById('vehicle-modal-title').textContent = 'Edit Vehicle';
                    openModal('vehicle-modal');
                }
            }
        });
}

async function saveVehicle(event) {
    event.preventDefault();
    const id = document.getElementById('veh-id').value;
    const data = {
        vehicle_name: document.getElementById('veh-name').value.trim(),
        vehicle_type: document.getElementById('veh-type').value.trim(),
        seat_capacity: parseInt(document.getElementById('veh-seats').value),
        image_url: document.getElementById('veh-image').value.trim(),
        status: document.getElementById('veh-status').value
    };
    if (id) data.id = parseInt(id);

    try {
        const response = await fetch(API_BASE + 'admin_vehicles.php', {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            closeModal('vehicle-modal');
            document.getElementById('vehicle-form').reset();
            document.getElementById('veh-id').value = '';
            document.getElementById('vehicle-modal-title').textContent = 'Add Vehicle';
            loadVehicles();
        } else { alert(result.message || 'Save failed'); }
    } catch (error) { alert('Error saving vehicle'); }
}

// ===== SERVICES =====
async function loadServices() {
    try {
        const response = await fetch(API_BASE + 'admin_services.php');
        const result = await response.json();
        if (result.success) {
            const tbody = document.querySelector('#services-table tbody');
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No services</td></tr>';
                return;
            }
            tbody.innerHTML = result.data.map(function(s) {
                return '<tr><td>' + s.id + '</td><td>' + escapeHtml(s.title) + '</td><td>' + escapeHtml((s.description || '').substring(0, 50)) + '...</td><td><i class="' + escapeHtml(s.icon_class) + '"></i> ' + escapeHtml(s.icon_class) + '</td><td><button class="admin-btn admin-btn-primary admin-btn-sm" onclick="editService(' + s.id + ')">Edit</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="deleteItem(\'admin_services.php\', ' + s.id + ', loadServices)">Delete</button></td></tr>';
            }).join('');
        }
    } catch (error) { console.error('Load services error:', error); }
}

function editService(id) {
    fetch(API_BASE + 'admin_services.php')
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                const s = result.data.find(function(x) { return x.id == id; });
                if (s) {
                    document.getElementById('svc-id').value = s.id;
                    document.getElementById('svc-title').value = s.title || '';
                    document.getElementById('svc-description').value = s.description || '';
                    document.getElementById('svc-icon').value = s.icon_class || '';
                    document.getElementById('service-modal-title').textContent = 'Edit Service';
                    openModal('service-modal');
                }
            }
        });
}

async function saveService(event) {
    event.preventDefault();
    const id = document.getElementById('svc-id').value;
    const data = {
        title: document.getElementById('svc-title').value.trim(),
        description: document.getElementById('svc-description').value.trim(),
        icon_class: document.getElementById('svc-icon').value.trim()
    };
    if (id) data.id = parseInt(id);

    try {
        const response = await fetch(API_BASE + 'admin_services.php', {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            closeModal('service-modal');
            document.getElementById('service-form').reset();
            document.getElementById('svc-id').value = '';
            document.getElementById('service-modal-title').textContent = 'Add Service';
            loadServices();
        } else { alert(result.message || 'Save failed'); }
    } catch (error) { alert('Error saving service'); }
}

// ===== DESTINATIONS =====
async function loadDestinations() {
    try {
        const response = await fetch(API_BASE + 'admin_destinations.php');
        const result = await response.json();
        if (result.success) {
            const tbody = document.querySelector('#destinations-table tbody');
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No destinations</td></tr>';
                return;
            }
            tbody.innerHTML = result.data.map(function(d) {
                const img = d.image_url ? '<img src="' + escapeHtml(d.image_url) + '" style="width:5rem; height:3rem; object-fit:cover; border-radius:0.3rem;">' : '-';
                return '<tr><td>' + d.id + '</td><td>' + escapeHtml(d.name) + '</td><td>' + escapeHtml(d.country) + '</td><td>' + img + '</td><td><button class="admin-btn admin-btn-primary admin-btn-sm" onclick="editDestination(' + d.id + ')">Edit</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="deleteItem(\'admin_destinations.php\', ' + d.id + ', loadDestinations)">Delete</button></td></tr>';
            }).join('');
        }
    } catch (error) { console.error('Load destinations error:', error); }
}

function editDestination(id) {
    fetch(API_BASE + 'admin_destinations.php')
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                const d = result.data.find(function(x) { return x.id == id; });
                if (d) {
                    document.getElementById('dest-id').value = d.id;
                    document.getElementById('dest-name').value = d.name || '';
                    document.getElementById('dest-country').value = d.country || '';
                    document.getElementById('dest-description').value = d.description || '';
                    document.getElementById('dest-image').value = d.image_url || '';
                    document.getElementById('destination-modal-title').textContent = 'Edit Destination';
                    openModal('destination-modal');
                }
            }
        });
}

async function saveDestination(event) {
    event.preventDefault();
    const id = document.getElementById('dest-id').value;
    const data = {
        name: document.getElementById('dest-name').value.trim(),
        country: document.getElementById('dest-country').value.trim(),
        description: document.getElementById('dest-description').value.trim(),
        image_url: document.getElementById('dest-image').value.trim()
    };
    if (id) data.id = parseInt(id);

    try {
        const response = await fetch(API_BASE + 'admin_destinations.php', {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            closeModal('destination-modal');
            document.getElementById('destination-form').reset();
            document.getElementById('dest-id').value = '';
            document.getElementById('destination-modal-title').textContent = 'Add Destination';
            loadDestinations();
        } else { alert(result.message || 'Save failed'); }
    } catch (error) { alert('Error saving destination'); }
}

// ===== GALLERY =====
async function loadGallery() {
    try {
        const response = await fetch(API_BASE + 'admin_gallery.php');
        const result = await response.json();
        if (result.success) {
            const tbody = document.querySelector('#gallery-table tbody');
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No gallery items</td></tr>';
                return;
            }
            tbody.innerHTML = result.data.map(function(g) {
                const img = g.image_url ? '<img src="' + escapeHtml(g.image_url) + '" style="width:6rem; height:4rem; object-fit:cover; border-radius:0.3rem;">' : '-';
                return '<tr><td>' + g.id + '</td><td>' + escapeHtml(g.title) + '</td><td>' + img + '</td><td><button class="admin-btn admin-btn-primary admin-btn-sm" onclick="editGalleryItem(' + g.id + ')">Edit</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="deleteItem(\'admin_gallery.php\', ' + g.id + ', loadGallery)">Delete</button></td></tr>';
            }).join('');
        }
    } catch (error) { console.error('Load gallery error:', error); }
}

function editGalleryItem(id) {
    fetch(API_BASE + 'admin_gallery.php')
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                const g = result.data.find(function(x) { return x.id == id; });
                if (g) {
                    document.getElementById('gal-id').value = g.id;
                    document.getElementById('gal-title').value = g.title || '';
                    document.getElementById('gal-image').value = g.image_url || '';
                    document.getElementById('gal-category').value = g.category || '';
                    document.getElementById('gallery-modal-title').textContent = 'Edit Gallery Image';
                    openModal('gallery-modal');
                }
            }
        });
}

async function saveGalleryItem(event) {
    event.preventDefault();
    const id = document.getElementById('gal-id').value;
    const data = {
        title: document.getElementById('gal-title').value.trim(),
        image_url: document.getElementById('gal-image').value.trim(),
        category: document.getElementById('gal-category').value.trim()
    };
    if (id) data.id = parseInt(id);

    try {
        const response = await fetch(API_BASE + 'admin_gallery.php', {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            closeModal('gallery-modal');
            document.getElementById('gallery-form').reset();
            document.getElementById('gal-id').value = '';
            document.getElementById('gallery-modal-title').textContent = 'Add Gallery Image';
            loadGallery();
        } else { alert(result.message || 'Save failed'); }
    } catch (error) { alert('Error saving gallery item'); }
}

// ===== TESTIMONIALS =====
async function loadTestimonials() {
    try {
        const response = await fetch(API_BASE + 'admin_testimonials.php');
        const result = await response.json();
        if (result.success) {
            const tbody = document.querySelector('#testimonials-table tbody');
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No testimonials</td></tr>';
                return;
            }
            tbody.innerHTML = result.data.map(function(t) {
                const stars = '★'.repeat(t.rating) + '☆'.repeat(5 - t.rating);
                return '<tr><td>' + t.id + '</td><td>' + escapeHtml(t.customer_name) + '</td><td style="color:#F9A603;">' + stars + '</td><td>' + escapeHtml((t.review_text || '').substring(0, 60)) + '...</td><td><button class="admin-btn admin-btn-primary admin-btn-sm" onclick="editTestimonial(' + t.id + ')">Edit</button> <button class="admin-btn admin-btn-danger admin-btn-sm" onclick="deleteItem(\'admin_testimonials.php\', ' + t.id + ', loadTestimonials)">Delete</button></td></tr>';
            }).join('');
        }
    } catch (error) { console.error('Load testimonials error:', error); }
}

function editTestimonial(id) {
    fetch(API_BASE + 'admin_testimonials.php')
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                const t = result.data.find(function(x) { return x.id == id; });
                if (t) {
                    document.getElementById('test-id').value = t.id;
                    document.getElementById('test-name').value = t.customer_name || '';
                    document.getElementById('test-text').value = t.review_text || '';
                    document.getElementById('test-rating').value = t.rating;
                    document.getElementById('test-image').value = t.profile_image || '';
                    document.getElementById('testimonial-modal-title').textContent = 'Edit Testimonial';
                    openModal('testimonial-modal');
                }
            }
        });
}

async function saveTestimonial(event) {
    event.preventDefault();
    const id = document.getElementById('test-id').value;
    const data = {
        customer_name: document.getElementById('test-name').value.trim(),
        review_text: document.getElementById('test-text').value.trim(),
        rating: parseInt(document.getElementById('test-rating').value),
        profile_image: document.getElementById('test-image').value.trim()
    };
    if (id) data.id = parseInt(id);

    try {
        const response = await fetch(API_BASE + 'admin_testimonials.php', {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            closeModal('testimonial-modal');
            document.getElementById('testimonial-form').reset();
            document.getElementById('test-id').value = '';
            document.getElementById('testimonial-modal-title').textContent = 'Add Testimonial';
            loadTestimonials();
        } else { alert(result.message || 'Save failed'); }
    } catch (error) { alert('Error saving testimonial'); }
}

// ===== MEDIA MANAGER =====
async function loadMedia() {
    const typeFilter = document.getElementById('media-type-filter').value;
    const url = API_BASE + 'admin_media.php' + (typeFilter ? '?file_type=' + typeFilter : '');
    try {
        const response = await fetch(url);
        const result = await response.json();
        const grid = document.getElementById('media-grid');

        if (result.success && result.data.length > 0) {
            grid.innerHTML = result.data.map(function(m) {
                const isImage = m.file_type === 'image';
                const preview = isImage
                    ? '<img src="' + escapeHtml(m.file_path) + '" style="width:100%; height:12rem; object-fit:cover; border-radius:0.5rem 0.5rem 0 0;">'
                    : '<div style="width:100%; height:12rem; display:flex; align-items:center; justify-content:center; background:#0f3460; border-radius:0.5rem 0.5rem 0 0;"><i class="fas fa-video" style="font-size:3rem; color:#F9A603;"></i></div>';
                return '<div style="background:#16213e; border-radius:0.5rem; border:1px solid #0f3460; overflow:hidden;">' + preview + '<div style="padding:1rem;"><p style="font-size:1.2rem; color:#ccc; word-break:break-all;">' + escapeHtml(m.original_name) + '</p><button class="admin-btn admin-btn-danger admin-btn-sm" style="margin-top:0.5rem;" onclick="deleteMedia(' + m.id + ')">Delete</button></div></div>';
            }).join('');
        } else {
            grid.innerHTML = '<p style="color:#aaa; font-size:1.4rem; grid-column: 1/-1;">No media files found.</p>';
        }
    } catch (error) { console.error('Load media error:', error); }
}

document.getElementById('media-upload-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const fileInput = document.getElementById('media-file');
    if (!fileInput.files.length) { alert('Please select a file'); return; }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);

    try {
        const response = await fetch(API_BASE + 'admin_media.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            fileInput.value = '';
            loadMedia();
        } else { alert(result.message || 'Upload failed'); }
    } catch (error) { alert('Error uploading file'); }
});

async function deleteMedia(id) {
    if (!confirm('Delete this media file?')) return;
    try {
        const response = await fetch(API_BASE + 'admin_media.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        const result = await response.json();
        if (result.success) loadMedia();
        else alert(result.message || 'Delete failed');
    } catch (error) { alert('Error deleting media'); }
}

// ===== GENERIC DELETE =====
async function deleteItem(endpoint, id, reloadFn) {
    if (!confirm('Are you sure you want to delete this item?')) return;
    try {
        const response = await fetch(API_BASE + endpoint, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        const result = await response.json();
        if (result.success) reloadFn();
        else alert(result.message || 'Delete failed');
    } catch (error) { alert('Error deleting item'); }
}

// ===== INITIAL LOAD =====
loadDashboard();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init({ duration: 800, once: true });</script>
</body>
</html>
