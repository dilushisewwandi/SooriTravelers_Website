<!-- footer section starts -->
<section class="footer">

    <div class="box-container">

        <div class="box" data-aos="fade-up" data-aos-delay="150">
            <a href="#" class="logo">soori travels </a>
            <p style="text-align:justify">We are with over 20 years of expertise in the Sri Lankan tourism industry including "A" grade drivers, and guides who are almost fully knowledgeable about Sri Lanka</p>
            <div class="share">
                <a href="https://www.facebook.com/tharusha.dasun.9212" class="fab fa-facebook-f"></a>
                <a href="https://www.tripadvisor.co.uk/Attraction_Review-g644047-d25182290-Reviews-Soori_Travels-Unawatuna_Galle_District_Southern_Province.html" class="fa fa-tripadvisor"></a>
                <a href="https://www.instagram.com/soori_travels/" class="fab fa-instagram"></a>
                <a href="https://www.linkedin.com/in/sooriarachchi-kamal-3a110985/?originalSubdomain=lk" class="fab fa-linkedin"></a>
            </div>
        </div>

        <div class="box" data-aos="fade-up" data-aos-delay="300">
            <h3>quick links</h3>
            <a href="<?php echo $basePath ?? ''; ?>index.php#home" class="links"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="<?php echo $basePath ?? ''; ?>index.php#about" class="links"> <i class="fas fa-arrow-right"></i> about </a>
            <a href="<?php echo $basePath ?? ''; ?>index.php#destination" class="links"> <i class="fas fa-arrow-right"></i> destination </a>
            <a href="<?php echo $basePath ?? ''; ?>index.php#services" class="links"> <i class="fas fa-arrow-right"></i> services </a>
            <a href="<?php echo $basePath ?? ''; ?>index.php#gallery" class="links"> <i class="fas fa-arrow-right"></i> gallery </a>
            <a href="<?php echo $basePath ?? ''; ?>index.php#blogs" class="links"> <i class="fas fa-arrow-right"></i> blogs </a>
        </div>

        <div class="box" data-aos="fade-up" data-aos-delay="450">
            <h3>contact info</h3>
            <p> <i class="fas fa-map"></i> Galle, Sri Lanka </p>
            <p> <i class="fas fa-phone"></i> +94 766977234 </p>
            <p> <i class="fas fa-envelope"></i> Soori_70@hotmaiil.com </p>
            <p> <i class="fas fa-clock"></i> 7:00am - 10:00pm </p>
        </div>

        <div class="box" data-aos="fade-up" data-aos-delay="600">
            <h3>newsletter</h3>
            <p>subscribe for latest updates</p>
            <form action="">
                <input type="email" name="" placeholder="enter your email" class="email" id="">
                <input type="submit" value="subscribe" class="fbtn">
            </form>
        </div>

    </div>

</section>

<div class="credit">created by <a href="<?php echo $basePath ?? ''; ?>developers.html">team us</a> | all rights reserved!</div>
<!-- footer section ends -->

<!-- AOS animation library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        offset: 150,
    });
</script>

<!-- Global nav script -->
<script src="<?php echo $basePath ?? ''; ?>js/script.js"></script>

<?php if (!empty($extraJs)): ?>
    <?php foreach ($extraJs as $jsFile): ?>
        <script src="<?php echo $basePath ?? ''; ?><?php echo $jsFile; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
