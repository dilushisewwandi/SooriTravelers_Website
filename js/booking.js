/**
 * Booking Module - Tour booking with gallery, vehicle availability
 */
document.addEventListener('DOMContentLoaded', function() {
    requireAuth();
    initBookingPage();

    // Mobile menu toggle
    var menuBtn = document.getElementById('bk-menu-btn');
    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            document.querySelector('.bk-nav').classList.toggle('active');
        });
    }
});

async function initBookingPage() {
    var urlParams = new URLSearchParams(window.location.search);
    var packageId = urlParams.get('package_id');

    if (!packageId) {
        document.querySelector('.bk-content').innerHTML =
            '<div style="text-align:center; padding:8rem 2rem;">' +
            '<i class="fas fa-exclamation-circle" style="font-size:5rem; color:#F9A603; margin-bottom:2rem;"></i>' +
            '<h2 style="font-size:2.4rem; color:#333; margin-bottom:1rem;">No Package Selected</h2>' +
            '<p style="font-size:1.4rem; color:#888; margin-bottom:2rem;">Please select a tour package first.</p>' +
            '<a href="index.php#packages" class="bk-back-btn"><i class="fas fa-arrow-left"></i> Browse Packages</a>' +
            '</div>';
        return;
    }

    await loadPackageDetails(packageId);

    var dateInput = document.getElementById('travel-date');
    if (dateInput) {
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];

        dateInput.addEventListener('change', function() {
            loadAvailableVehicles(this.value);
        });
    }

    var bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', handleBookingSubmit);
    }
}

async function loadPackageDetails(packageId) {
    try {
        var response = await fetch('api/get_packages.php?id=' + packageId);
        var result = await response.json();

        if (result.success) {
            var pkg = result.data;
            document.getElementById('package-title').textContent = pkg.title;
            document.getElementById('package-description').textContent = pkg.description || 'Experience the beauty of Sri Lanka with this carefully curated tour package.';
            document.getElementById('package-price').textContent = '$' + Number(pkg.price).toLocaleString() + ' / person';
            document.getElementById('package-duration').textContent = pkg.duration_days + ' Days';
            document.getElementById('package-location').textContent = pkg.location || 'Sri Lanka';
            document.getElementById('package-id-input').value = pkg.id;

            if (pkg.thumbnail_url) {
                document.getElementById('package-image').src = pkg.thumbnail_url;
            }

            // Build gallery
            var gallery = document.getElementById('gallery-container');
            var images = [];

            // Add main thumbnail
            if (pkg.thumbnail_url) {
                images.push(pkg.thumbnail_url);
            }

            // Add package images
            if (pkg.images && pkg.images.length > 0) {
                pkg.images.forEach(function(img) {
                    if (img.image_url && img.image_url !== pkg.thumbnail_url) {
                        images.push(img.image_url);
                    }
                });
            }

            // If we have fewer than 2 images, add fallback placeholders
            if (images.length < 2) {
                images.push('images/home-bg.jpg');
                images.push('images/blog-1.jpg');
                images.push('images/blog-2.jpg');
                images.push('images/blog-3.jpg');
            }

            gallery.innerHTML = '';
            images.forEach(function(src, i) {
                var img = document.createElement('img');
                img.src = src;
                img.alt = 'Tour photo ' + (i + 1);
                if (i === 0) img.classList.add('active-thumb');
                img.addEventListener('click', function() {
                    document.getElementById('package-image').src = src;
                    gallery.querySelectorAll('img').forEach(function(el) {
                        el.classList.remove('active-thumb');
                    });
                    img.classList.add('active-thumb');
                });
                gallery.appendChild(img);
            });
        }
    } catch (error) {
        console.error('Failed to load package:', error);
    }
}

async function loadAvailableVehicles(date) {
    var vehicleSelect = document.getElementById('vehicle-select');
    if (!vehicleSelect || !date) return;

    vehicleSelect.innerHTML = '<option value="">Loading vehicles...</option>';

    try {
        var response = await fetch('api/get_vehicles.php?date=' + date);
        var result = await response.json();

        vehicleSelect.innerHTML = '<option value="">-- Select Vehicle --</option>';

        if (result.success && result.data.length > 0) {
            result.data.forEach(function(vehicle) {
                var option = document.createElement('option');
                option.value = vehicle.id;
                option.textContent = vehicle.vehicle_name + ' (' + vehicle.vehicle_type + ') - ' + vehicle.seat_capacity + ' seats';
                option.setAttribute('data-seats', vehicle.seat_capacity);
                vehicleSelect.appendChild(option);
            });
        } else {
            vehicleSelect.innerHTML = '<option value="">No vehicles available on this date</option>';
        }
    } catch (error) {
        console.error('Failed to load vehicles:', error);
        vehicleSelect.innerHTML = '<option value="">Error loading vehicles</option>';
    }
}

// +/- people buttons
function adjustPeople(delta) {
    var input = document.getElementById('number-of-people');
    var val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 50) val = 50;
    input.value = val;
}

async function handleBookingSubmit(event) {
    event.preventDefault();

    var user = getCurrentUser();
    if (!user) {
        showBookingMessage('Please login to make a booking', 'error');
        setTimeout(function() { window.location.href = 'login_new.php'; }, 1500);
        return;
    }

    var packageId = document.getElementById('package-id-input').value;
    var vehicleId = document.getElementById('vehicle-select').value;
    var travelDate = document.getElementById('travel-date').value;
    var numberOfPeople = document.getElementById('number-of-people').value;

    if (!packageId || !vehicleId || !travelDate || !numberOfPeople) {
        showBookingMessage('Please fill in all fields', 'error');
        return;
    }

    // Seat capacity check
    var vehicleSelect = document.getElementById('vehicle-select');
    var selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
    var seatCapacity = parseInt(selectedOption.getAttribute('data-seats'));

    if (parseInt(numberOfPeople) > seatCapacity) {
        showBookingMessage('Selected vehicle only has ' + seatCapacity + ' seats. Please choose a larger vehicle or reduce the group size.', 'error');
        return;
    }

    var submitBtn = document.getElementById('booking-submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
        var response = await fetch('api/create_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                firebase_uid: user.uid,
                package_id: parseInt(packageId),
                vehicle_id: parseInt(vehicleId),
                travel_date: travelDate,
                number_of_people: parseInt(numberOfPeople)
            })
        });

        var result = await response.json();

        if (result.success) {
            showBookingMessage('Booking created successfully! Redirecting to your profile...', 'success');
            setTimeout(function() { window.location.href = 'profile_new.php'; }, 2000);
        } else {
            showBookingMessage(result.message || 'Booking failed. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Booking error:', error);
        showBookingMessage('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-suitcase-rolling"></i> Book Now';
    }
}

function showBookingMessage(text, type) {
    var msgDiv = document.getElementById('booking-message');
    msgDiv.className = 'bk-message ' + type;
    msgDiv.textContent = text;
    if (type === 'error') {
        setTimeout(function() { msgDiv.className = 'bk-message'; }, 5000);
    }
}
