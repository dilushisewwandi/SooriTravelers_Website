<?php
/**
 * Soori Travels - Main Homepage (Refactored)
 * Dynamic content loaded via API endpoints
 */
$pageTitle = 'Soori Travels Official Website';
$basePath = '';
$extraCss = [];
$extraJs = ['js/services.js', 'js/destinations.js', 'js/gallery.js', 'js/testimonials.js', 'js/packages.js'];

include 'components/header.php';
include 'components/navbar.php';
?>

<!-- home section starts -->
<section class="home" id="home">
    <div class="content">
        <span data-aos="fade-up" data-aos-delay="150">Your Travel Partner</span>
        <h3 data-aos="fade-up" data-aos-delay="300">Soori Travels</h3>
        <p data-aos="fade-up" data-aos-delay="450">we're ready to make your vacation<br> wonderful and unforgettable.</p>
        <a data-aos="fade-up" data-aos-delay="600" href="#packages" class="btn">book now</a>
    </div>
</section>
<!-- home section ends -->

<!-- about section starts -->
<section class="about" id="about">
    <div class="video-container" data-aos="fade-right" data-aos-delay="300">
        <video src="images/train video.mp4" muted autoplay loop class="video"></video>
        <div class="controls">
            <span class="control-btn" data-src="images/train video.mp4"></span>
            <span class="control-btn" data-src="images/about-vid-2.mp4"></span>
            <span class="control-btn" data-src="images/about-vid-3.mp4"></span>
        </div>
    </div>
    <div class="content" data-aos="fade-left" data-aos-delay="600">
        <span>why choose us?</span>
        <h3>Your Trusted Travel Partner in Sri Lanka</h3>
        <p>"SOORI TRAVELS" is a travel agency with over 20 years of expertise in the Sri Lankan tourism industry 
            including "A" grade drivers, and guides who are almost fully knowledgeable about Sri Lanka. With much 
            joy, good knowledge, and extra cultural and local experiences, we're ready to make your ideal tour or 
            vacation wonderful and unforgettable.</p>
        <div class="about-stats">
            <div class="stat" data-aos="zoom-in" data-aos-delay="200">
                <h4>20+</h4><span>Years Experience</span>
            </div>
            <div class="stat" data-aos="zoom-in" data-aos-delay="400">
                <h4>500+</h4><span>Happy Clients</span>
            </div>
            <div class="stat" data-aos="zoom-in" data-aos-delay="600">
                <h4>50+</h4><span>Destinations</span>
            </div>
        </div>
        <a href="#packages" class="about-btn">explore tours <i class="fas fa-arrow-right"></i></a>
    </div>
</section>
<!-- about section ends -->

<!-- Dynamic sections loaded from database via JS -->
<?php include 'components/package_card.php'; ?>
<?php include 'components/destination_section.php'; ?>
<?php include 'components/service_section.php'; ?>
<?php include 'components/gallery_section.php'; ?>
<?php include 'components/testimonial_section.php'; ?>

<!-- blogs section starts -->
<section class="blogs" id="blogs">
    <div class="heading" data-aos="fade-up">
        <span>blogs & posts</span>
        <h1>Travel Stories</h1>
        <p>Insights, tips, and tales from our adventures across Sri Lanka</p>
    </div>
    <div class="box-container">
        <div class="box" data-aos="fade-up" data-aos-delay="150">
            <div class="image">
                <img src="images/blog-1.jpg" alt="">
                <span class="blog-date"><i class="fas fa-clock"></i> 21 May 2021</span>
            </div>
            <div class="content">
                <a href="#" class="link">Life is a journey, not a destination</a>
                <p>Discover the hidden gems and untold stories that make Sri Lanka a travelers paradise.</p>
                <div class="icon">
                    <a href="#"><i class="fas fa-user"></i> by admin</a>
                    <a href="#"><i class="fas fa-comments"></i> 12 comments</a>
                </div>
            </div>
        </div>
        <div class="box" data-aos="fade-up" data-aos-delay="300">
            <div class="image">
                <img src="images/blog-2.jpg" alt="">
                <span class="blog-date"><i class="fas fa-clock"></i> 15 Jun 2021</span>
            </div>
            <div class="content">
                <a href="#" class="link">Exploring the ancient wonders of Sri Lanka</a>
                <p>From Sigiriya to Polonnaruwa, uncover centuries of history and cultural heritage.</p>
                <div class="icon">
                    <a href="#"><i class="fas fa-user"></i> by admin</a>
                    <a href="#"><i class="fas fa-comments"></i> 8 comments</a>
                </div>
            </div>
        </div>
        <div class="box" data-aos="fade-up" data-aos-delay="450">
            <div class="image">
                <img src="images/blog-3.jpg" alt="">
                <span class="blog-date"><i class="fas fa-clock"></i> 03 Aug 2021</span>
            </div>
            <div class="content">
                <a href="#" class="link">Beach paradise: southern coastal secrets</a>
                <p>Sun-kissed beaches, whale watching, and tropical sunsets along the southern coast.</p>
                <div class="icon">
                    <a href="#"><i class="fas fa-user"></i> by admin</a>
                    <a href="#"><i class="fas fa-comments"></i> 15 comments</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- blogs section ends -->

<!-- banner section starts -->
<div class="banner">
    <div class="content" data-aos="zoom-in-up" data-aos-delay="300">
        <span>start your adventures</span>
        <h3>Let's Explore This<br>Beautiful World</h3>
        <p>Your dream vacation is just one click away. Let us craft the perfect Sri Lankan experience for you.</p>
        <a href="#packages" class="btn">book now</a>
    </div>
</div>
<!-- banner section ends -->

<?php include 'components/footer.php'; ?>
