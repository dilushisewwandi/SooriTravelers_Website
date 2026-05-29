/**
 * Testimonials Module - Loads testimonials from API
 */
document.addEventListener('DOMContentLoaded', function() {
    loadTestimonials();
});

async function loadTestimonials() {
    const container = document.getElementById('testimonials-container');
    if (!container) return;

    try {
        const response = await fetch('api/get_testimonials.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            container.innerHTML = '';

            result.data.forEach(function(testimonial) {
                const box = document.createElement('div');
                box.className = 'box';

                const stars = '★'.repeat(testimonial.rating) + '☆'.repeat(5 - testimonial.rating);

                box.innerHTML = `
                    <p>${testimonial.review_text}</p>
                    <div class="user">
                        <img src="${testimonial.profile_image || 'images/pic-1.png'}" alt="${testimonial.customer_name}">
                        <div class="info">
                            <h3>${testimonial.customer_name}</h3>
                            <span>${stars}</span>
                        </div>
                    </div>
                `;

                container.appendChild(box);
            });

            if (typeof AOS !== 'undefined') AOS.refresh();
        }
    } catch (error) {
        console.error('Failed to load testimonials:', error);
    }
}
