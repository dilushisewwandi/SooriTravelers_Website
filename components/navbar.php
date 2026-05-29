<!-- header section starts -->
<header class="header">

    <div id="menu-btn" class="fas fa-bars"></div>

    <a data-aos="zoom-in-left" data-aos-delay="150" href="<?php echo $basePath ?? ''; ?>index.php" class="logo">Soori Travels </a>

    <nav class="navbar">
        <a data-aos="zoom-in-left" data-aos-delay="300" href="<?php echo $basePath ?? ''; ?>index.php#home">home</a>
        <a data-aos="zoom-in-left" data-aos-delay="450" href="<?php echo $basePath ?? ''; ?>index.php#about">about</a>
        <a data-aos="zoom-in-left" data-aos-delay="600" href="<?php echo $basePath ?? ''; ?>index.php#packages">packages</a>
        <a data-aos="zoom-in-left" data-aos-delay="750" href="<?php echo $basePath ?? ''; ?>index.php#destination">destination</a>
        <a data-aos="zoom-in-left" data-aos-delay="900" href="<?php echo $basePath ?? ''; ?>index.php#services">services</a>
        <a data-aos="zoom-in-left" data-aos-delay="1150" href="<?php echo $basePath ?? ''; ?>index.php#gallery">gallery</a>
        <a data-aos="zoom-in-left" data-aos-delay="1300" href="<?php echo $basePath ?? ''; ?>index.php#blogs">blogs</a>
    </nav>

    <div id="auth-nav-btn">
        <!-- Will be dynamically set by firebase-auth.js -->
        <a data-aos="zoom-in-left" data-aos-delay="1300" href="<?php echo $basePath ?? ''; ?>login_new.php" class="btn" id="loginBtn">login</a>
    </div>

</header>
<!-- header section ends -->
