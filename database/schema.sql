-- =====================================================
-- Soori Travels - Complete Database Schema
-- Database: soori_travels
-- =====================================================

CREATE DATABASE IF NOT EXISTS `soori_travels`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `soori_travels`;

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firebase_uid` VARCHAR(255) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_firebase_uid` (`firebase_uid`),
  UNIQUE KEY `uk_email` (`email`),
  INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ADMINS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PACKAGES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `packages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `duration_days` INT NOT NULL DEFAULT 1,
  `location` VARCHAR(255) DEFAULT NULL,
  `thumbnail_url` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_packages_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PACKAGE IMAGES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `package_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `package_id` INT NOT NULL,
  `image_url` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pkg_images_package` (`package_id`),
  CONSTRAINT `fk_pkg_images_package` FOREIGN KEY (`package_id`)
    REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- VEHICLES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `vehicle_name` VARCHAR(150) NOT NULL,
  `vehicle_type` VARCHAR(100) NOT NULL,
  `seat_capacity` INT NOT NULL DEFAULT 4,
  `vehicle_image` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('available', 'maintenance') NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`),
  INDEX `idx_vehicles_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- BOOKINGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `package_id` INT NOT NULL,
  `vehicle_id` INT NOT NULL,
  `travel_date` DATE NOT NULL,
  `number_of_people` INT NOT NULL DEFAULT 1,
  `booking_status` ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_bookings_user` (`user_id`),
  INDEX `idx_bookings_package` (`package_id`),
  INDEX `idx_bookings_vehicle` (`vehicle_id`),
  INDEX `idx_bookings_date` (`travel_date`),
  INDEX `idx_bookings_status` (`booking_status`),
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bookings_package` FOREIGN KEY (`package_id`)
    REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bookings_vehicle` FOREIGN KEY (`vehicle_id`)
    REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SERVICES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `icon_class` VARCHAR(100) DEFAULT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DESTINATIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- GALLERY TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) DEFAULT NULL,
  `image_url` VARCHAR(500) NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_gallery_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TESTIMONIALS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(150) NOT NULL,
  `review_text` TEXT NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `profile_image` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- MEDIA FILES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `media_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `original_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_type` VARCHAR(50) NOT NULL,
  `uploaded_by` VARCHAR(150) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_media_type` (`file_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SEED DATA - Default Admin
-- =====================================================
INSERT INTO `admins` (`name`, `email`, `password_hash`) VALUES
('Admin', 'admin@sooritravels.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Default password: password (change in production!)

-- =====================================================
-- SEED DATA - Sample Services
-- =====================================================
INSERT INTO `services` (`title`, `description`, `icon_class`) VALUES
('worldwide', 'Have expertise with so many different nationalities, even the drivers and guides have traveled all over the world.', 'fas fa-globe'),
('adventures', 'You can participate in adventure programs like surfing, hiking and trekking, wildlife safaris, whale watching, scuba diving, and hot air balloon rides in Sri Lanka.', 'fas fa-hiking'),
('food & drinks', 'Savor exquisite culinary delights with Soori Travels curated food and beverage experiences. Indulge in diverse flavors that complement your journey.', 'fas fa-utensils'),
('affordable hotels', 'Discover comfort without breaking the bank with Soori Travels affordable hotel options. Enjoy quality accommodations at budget-friendly prices.', 'fas fa-hotel'),
('affordable price', 'Experience unparalleled value with Soori Travels affordable prices. Our commitment to budget-friendly options ensures you enjoy top-notch services.', 'fas fa-wallet'),
('24/7 support', 'Count on Soori Travels for round-the-clock support. Our dedicated team is available 24/7 to assist you, ensuring a seamless travel experience.', 'fas fa-headset');

-- =====================================================
-- SEED DATA - Sample Destinations
-- =====================================================
INSERT INTO `destinations` (`name`, `description`, `image_url`) VALUES
('Sigiriya or Sinhagiri', 'An ancient rock fortress located in the northern Matale District near the town of Dambulla', 'images/des-1.jpg'),
('Galle Fort', 'Galle Fort, was built first in 1588 by the Portuguese, then extensively fortified by the Dutch during the 17th century', 'images/des-2.jpg'),
('Sri Dalada Maligawa', 'The Temple of the Sacred Tooth Relic is a Buddhist temple in Kandy which houses the relic of the tooth of the Buddha.', 'images/des-3.jpg'),
('Horton Plains National Park', 'Horton Plains is a national park in the central highlands of Sri Lanka that was designated in 1988.', 'images/des-4.jpg'),
('Anuradhapura', 'Anuradhapura is a major city located in north central plain of Sri Lanka. With a huge ancient history along with sinhalese and buddhists.', 'images/des-5.jpg'),
('Royal Botanical Garden Peradeniya', 'Royal Botanic Gardens, Peradeniya are about 5.5 km to the west of the city of Kandy in the Central Province of Sri Lanka.', 'images/des-6.jpg'),
('Polonnaruwa', 'The modern town of Polonnaruwa is also known as New Town, and the other part of Polonnaruwa remains as the royal ancient city of the Kingdom of Polonnaruwa.', 'images/des-7.jpg');

-- =====================================================
-- SEED DATA - Sample Packages
-- =====================================================
INSERT INTO `packages` (`title`, `description`, `price`, `duration_days`, `location`, `thumbnail_url`) VALUES
('WILDLIFE PARADISE', 'A visit to Sri Lanka is a must for all wildlife enthusiasts. The many national parks in Sri Lanka are home to majestic creatures', 850.00, 8, 'Negombo, Habarana, Kandy, Nuwara Eliya, Yala, Mirissa', 'images/Wildlife-Paradise.jpg'),
('SRI LANKA HIGHLIGHTS', 'Experience the highlights of Sri Lanka in this well-planned itinerary that showcases the jewels of our cultural heritage', 550.00, 5, 'Colombo, Kandy, Nuwara Eliya, Galle', 'images/Sri-Lanka-Highlights.jpg'),
('CINNAMON WELLNESS RETREAT', 'Detox, Restore, Rejuvenate with a wellness getaway that will soothe your body, mind and soul', 1200.00, 10, 'Colombo, Kandy, Ella', 'images/Cinnamon-Wellness.jpg'),
('NORTH & EAST COAST EXPLORER', 'The North and East of Sri Lanka are blessed with many beaches and our tours introduce you to the attractions of the unexplored North and East.', 950.00, 9, 'Anuradhapura, Jaffna, Trincomalee', 'images/North-East-Coast-Explorer.jpg'),
('TURQUOISE SEAS', 'With wide stretches of sand or palm-fringed secluded bays, Sri Lanka beaches are lapped by shallow lagoons or high curling waves', 1400.00, 13, 'Southern Coast, Eastern Coast', 'images/Turquoise-Seas-m.jpg'),
('FAMILY FRIENDLY SRI LANKA', 'Ever considered taking an Island vacation this summer? A family friendly island awaits your discovery.', 1100.00, 12, 'Colombo, Kandy, Nuwara Eliya, Yala, Galle', 'images/Family-Friendly-Srilanka.png'),
('SOLO ROADS OF SRI LANKA', 'Travelling alone does not have to mean you are alone! Here is your chance to immerse yourself into a tropical island adventure.', 1350.00, 13, 'Colombo, Sigiriya, Kandy, Ella, Mirissa', 'images/Solo-tour.png'),
('HAPPILY EVER AFTER', 'Experience the best of tropical paradise with our 7 day honeymoon tour which will take you through the amazing sights of Sri Lanka', 750.00, 7, 'Colombo, Kandy, Nuwara Eliya, Bentota', 'images/Wedded-Bliss-m (1).jpg'),
('THE WANDERER', 'There is no better way than to enjoy the awesome scenery of Sri Lanka than by trekking through its beautiful landscapes', 900.00, 9, 'Colombo, Sigiriya, Knuckles, Ella', 'images/The-Wanderer-m.jpg'),
('CENTRAL HIGHLANDS', 'Spend nine days experiencing the beauty of Sri Lanka hill country.', 900.00, 9, 'Kandy, Nuwara Eliya, Ella, Horton Plains', 'images/Central-Highlands.jpg'),
('HILL COMFORT', 'The hill country of Sri Lanka reflects the aspirations of 19th & 20th century British colonisers to create a home away from home in the tropics.', 700.00, 7, 'Kandy, Nuwara Eliya, Ella', 'images/Hill-Comfort-m.jpg'),
('ROYAL HERITAGE', 'Experience the beauty of Sri Lankan heritage and culture while enjoying the sophisticated luxury of boutique properties during your tour.', 1050.00, 10, 'Colombo, Anuradhapura, Sigiriya, Kandy', 'images/Royal-Heritage-m.jpg'),
('CLASSIC CULTURAL', 'Experience the history and culture of Sri Lanka in this tour that covers the most of what this ancient island has to offer.', 1050.00, 10, 'Colombo, Dambulla, Sigiriya, Polonnaruwa, Kandy', 'images/Classic-Cultural.jpg');

-- =====================================================
-- SEED DATA - Sample Gallery
-- =====================================================
INSERT INTO `gallery` (`image_url`, `category`) VALUES
('images/gallery-img-1.jpg', 'travel spot'),
('images/gallery-img-2.jpg', 'travel spot'),
('images/gallery-img-3.jpg', 'travel spot'),
('images/gallery-img-4.jpg', 'travel spot'),
('images/gallery-img-5.jpg', 'travel spot'),
('images/gallery-img-6.jpg', 'travel spot'),
('images/gallery-img-7.jpg', 'travel spot'),
('images/gallery-img-8.jpg', 'travel spot'),
('images/gallery-img-9.jpg', 'travel spot');

-- =====================================================
-- SEED DATA - Sample Testimonials
-- =====================================================
INSERT INTO `testimonials` (`customer_name`, `review_text`, `rating`, `profile_image`) VALUES
('John Doe', 'Amazing experience with Soori Travels! The tour was well organized and the guide was very knowledgeable about Sri Lanka.', 5, 'images/pic-1.png'),
('Jane Smith', 'We had an unforgettable family vacation. The accommodations were excellent and the itinerary was perfect.', 5, 'images/pic-2.png'),
('Mike Johnson', 'Best travel agency in Sri Lanka. Professional service and great attention to detail throughout the trip.', 5, 'images/pic-3.png'),
('Sarah Williams', 'Highly recommend Soori Travels for anyone visiting Sri Lanka. They made our honeymoon truly special.', 5, 'images/pic-4.png');

-- =====================================================
-- SEED DATA - Sample Vehicles
-- =====================================================
INSERT INTO `vehicles` (`vehicle_name`, `vehicle_type`, `seat_capacity`, `vehicle_image`, `status`) VALUES
('Toyota HiAce Tour Van', 'Tour Van', 10, NULL, 'available'),
('Rosa Mini Bus', 'Mini Bus', 25, NULL, 'available'),
('Toyota Land Cruiser', 'Luxury SUV', 6, NULL, 'available'),
('Volvo Coach Bus', 'Coach Bus', 45, NULL, 'available'),
('Suzuki Alto', 'Car', 4, NULL, 'available'),
('Toyota Prius', 'Car', 4, NULL, 'available');
