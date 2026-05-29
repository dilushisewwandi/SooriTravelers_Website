/**
 * Services Module - Loads services from API
 */
document.addEventListener('DOMContentLoaded', function() {
    loadServices();
});

async function loadServices() {
    const container = document.getElementById('services-container');
    if (!container) return;

    try {
        const response = await fetch('api/get_services.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            container.innerHTML = '';
            const delays = [150, 300, 450, 600, 750, 900];

            result.data.forEach(function(service, index) {
                const delay = delays[index % delays.length];
                const box = document.createElement('div');
                box.className = 'box';
                box.setAttribute('data-aos', 'fade-up');
                box.setAttribute('data-aos-delay', delay);

                box.innerHTML = `
                    <div class="svc-icon"><i class="${service.icon_class || 'fas fa-star'}"></i></div>
                    <h3>${service.title}</h3>
                    <p>${service.description || ''}</p>
                `;

                container.appendChild(box);
            });

            // Refresh AOS for new elements
            if (typeof AOS !== 'undefined') AOS.refresh();
        }
    } catch (error) {
        console.error('Failed to load services:', error);
    }
}
