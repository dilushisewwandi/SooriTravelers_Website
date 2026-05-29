/**
 * Gallery Module - Loads gallery images from API
 */
document.addEventListener('DOMContentLoaded', function() {
    loadGallery();
});

async function loadGallery() {
    const container = document.getElementById('gallery-container');
    if (!container) return;

    try {
        const response = await fetch('api/get_gallery.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            container.innerHTML = '';
            const delays = [150, 300, 450];

            result.data.forEach(function(item, index) {
                const delay = delays[index % delays.length];
                const box = document.createElement('div');
                box.className = 'box';
                box.setAttribute('data-aos', 'fade-up');
                box.setAttribute('data-aos-delay', delay);

                box.innerHTML = `
                    <img src="${item.image_url}" alt="${item.title || item.category || 'gallery'}">
                    <div class="gallery-overlay">
                        <span>${item.category || 'travel spot'}</span>
                        <h3>${item.title || item.category || 'travel spot'}</h3>
                    </div>
                `;

                container.appendChild(box);
            });

            if (typeof AOS !== 'undefined') AOS.refresh();
        }
    } catch (error) {
        console.error('Failed to load gallery:', error);
    }
}
