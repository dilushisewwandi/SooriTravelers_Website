/**
 * Packages Module - Loads tour packages from API
 */
document.addEventListener('DOMContentLoaded', function() {
    if (typeof auth !== 'undefined') {
        auth.onAuthStateChanged(function() {
            loadPackages();
        });
    } else {
        loadPackages();
    }
});

async function loadPackages() {
    const container = document.getElementById('packages-container');
    if (!container) return;

    try {
        const response = await fetch('api/get_packages.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            container.innerHTML = '';

            result.data.forEach(function(pkg, index) {
                const card = document.createElement('div');
                card.className = 'pkg-card';
                card.setAttribute('data-aos', 'fade-up');
                card.setAttribute('data-aos-delay', (index % 3) * 150);

                const nights = (pkg.duration_days - 1);
                const isLoggedIn = typeof auth !== 'undefined' && auth.currentUser;
                const linkHref = isLoggedIn ? 'booking.php?package_id=' + pkg.id : '#';
                const linkClick = isLoggedIn ? '' : 'onclick="alertLoginRequired(event)"';

                const location = pkg.location || 'Sri Lanka';
                const description = pkg.description || 'Explore this amazing destination with Soori Travels.';
                const price = pkg.price ? '$' + Number(pkg.price).toLocaleString() : '';
                const thumbnail = pkg.thumbnail_url || 'images/placeholder.jpg';

                card.innerHTML =
                    '<div class="pkg-image">' +
                        '<img src="' + escapeAttr(thumbnail) + '" alt="' + escapeAttr(pkg.title) + '">' +
                        (price ? '<div class="pkg-price">' + price + '</div>' : '') +
                        '<div class="pkg-duration"><i class="far fa-clock"></i> ' + pkg.duration_days + 'D / ' + nights + 'N</div>' +
                        '<div class="pkg-location"><i class="fas fa-map-marker-alt"></i> ' + escapeHtml(location) + '</div>' +
                    '</div>' +
                    '<div class="pkg-body">' +
                        '<h3>' + escapeHtml(pkg.title) + '</h3>' +
                        '<p>' + escapeHtml(description) + '</p>' +
                        '<div class="pkg-footer">' +
                            '<div class="pkg-meta">' +
                                '<span><i class="fas fa-users"></i> Group</span>' +
                                '<span><i class="fas fa-star"></i> Popular</span>' +
                            '</div>' +
                            '<a href="' + linkHref + '" ' + linkClick + ' class="pkg-book-btn">Book Now <i class="fas fa-arrow-right"></i></a>' +
                        '</div>' +
                    '</div>';

                container.appendChild(card);
            });

            if (typeof AOS !== 'undefined') AOS.refresh();
        }
    } catch (error) {
        console.error('Failed to load packages:', error);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function escapeAttr(text) {
    if (!text) return '';
    return text.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function alertLoginRequired(event) {
    event.preventDefault();
    alert('Please login first to book a tour package!');
}
