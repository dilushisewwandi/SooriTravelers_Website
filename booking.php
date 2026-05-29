<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tour - Soori Travels</title>
    <link rel="icon" href="images/title_icon.png">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>
    <script src="js/firebase-auth.js"></script>
</head>
<body>

<!-- Header -->
<header class="bk-header">
    <div id="bk-menu-btn" class="fas fa-bars"></div>
    <a href="index.php" class="logo">Soori Travels</a>
    <nav class="bk-nav">
        <a href="index.php">home</a>
        <a href="index.php#packages">packages</a>
        <a href="index.php#gallery">gallery</a>
    </nav>
    <a href="index.php" class="bk-back-btn"><i class="fas fa-arrow-left"></i> Back</a>
</header>

<!-- Split Layout -->
<div class="bk-layout">

    <!-- LEFT: Hero Image -->
    <div class="bk-hero">
        <img id="package-image" src="images/home-bg.jpg" alt="Tour Package">
    </div>

    <!-- RIGHT: Content -->
    <div class="bk-content">

        <h1 class="bk-title" id="package-title">Loading...</h1>
        <p class="bk-description" id="package-description">Loading package details...</p>

        <!-- Info Badges -->
        <div class="bk-info-row">
            <div class="bk-info-item">
                <div class="bk-info-icon"><i class="fas fa-tag"></i></div>
                <div class="bk-info-text">
                    <small>Price</small>
                    <strong id="package-price">—</strong>
                </div>
            </div>
            <div class="bk-info-item">
                <div class="bk-info-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="bk-info-text">
                    <small>Duration</small>
                    <strong id="package-duration">—</strong>
                </div>
            </div>
            <div class="bk-info-item">
                <div class="bk-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="bk-info-text">
                    <small>Key Highlights</small>
                    <strong id="package-location">—</strong>
                </div>
            </div>
        </div>

        <!-- Tour Gallery -->
        <h3 class="bk-gallery-label">Tour Gallery</h3>
        <div class="bk-gallery" id="gallery-container">
            <!-- dynamically loaded -->
        </div>

        <!-- Booking Form Card -->
        <div class="bk-form-card">
            <h2 class="bk-form-title">Reserve Your Spot</h2>

            <div id="booking-message" class="bk-message"></div>

            <form id="booking-form">
                <input type="hidden" id="package-id-input" value="">

                <div class="bk-form-grid">
                    <div class="bk-form-group">
                        <label for="travel-date">Travel Date</label>
                        <input type="date" id="travel-date" class="bk-input" required>
                    </div>
                    <div class="bk-form-group">
                        <label for="number-of-people">Number Of People</label>
                        <div class="bk-number-wrap">
                            <button type="button" class="bk-num-btn" onclick="adjustPeople(-1)">−</button>
                            <input type="number" id="number-of-people" min="1" max="50" value="1" required>
                            <button type="button" class="bk-num-btn" onclick="adjustPeople(1)">+</button>
                        </div>
                    </div>
                </div>

                <div class="bk-vehicle-row">
                    <div class="bk-form-group">
                        <label for="vehicle-select">Select Vehicle</label>
                        <select id="vehicle-select" class="bk-input" required>
                            <option value="">Please Select a Travel Date first</option>
                        </select>
                    </div>
                    <button type="submit" id="booking-submit-btn" class="bk-submit-btn">
                        <i class="fas fa-suitcase-rolling"></i> Book Now
                    </button>
                </div>
            </form>
        </div>

        <!-- Trust Badges -->
        <div class="bk-trust">
            <h4>Book with Confidence</h4>
            <div class="bk-trust-badges">
                <div class="badge"><i class="fas fa-shield-alt"></i> Secure Payment</div>
                <div class="badge"><i class="fas fa-headset"></i> 24/7 Support</div>
                <div class="badge"><i class="fas fa-user-tie"></i> Expert Guides</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bk-footer">
            &copy; <?php echo date('Y'); ?> Soori Travels Inc. All rights reserved.
        </div>

    </div>
</div>

<script src="js/booking.js"></script>

</body>
</html>
