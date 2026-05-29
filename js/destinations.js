/**
 * Destinations Module - Loads destinations from API
 */
document.addEventListener('DOMContentLoaded', function() {
    loadDestinations();
});

async function loadDestinations() {
    const container = document.getElementById('destinations-container');
    if (!container) return;

    try {
        const response = await fetch('api/get_destinations.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            container.innerHTML = '';
            const delays = [150, 300, 450, 600, 750, 900, 1150];

            result.data.forEach(function(dest, index) {
                const delay = delays[index % delays.length];
                const box = document.createElement('div');
                box.className = 'box';
                box.setAttribute('data-aos', 'fade-up');
                box.setAttribute('data-aos-delay', delay);

                box.innerHTML = `
                    <div class="image">
                        <img src="${dest.image_url || 'images/placeholder.jpg'}" alt="${dest.name}">
                    </div>
                    <div class="content">
                        <h3>${dest.name}</h3>
                        <p>${dest.description || ''}</p>
                        <a href="#">read more <i class="fas fa-angle-right"></i></a>
                    </div>
                `;

                container.appendChild(box);
            });

            if (typeof AOS !== 'undefined') AOS.refresh();
        }
    } catch (error) {
        console.error('Failed to load destinations:', error);
    }
}
