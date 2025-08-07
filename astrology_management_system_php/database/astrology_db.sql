-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 07:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `astrology_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `astrologer_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `astrologer_id`, `appointment_date`, `message`, `status`, `created_at`, `updated_at`) VALUES
(5, 3, 2, '2025-05-08 00:00:00', 'erree', 'pending', '2025-05-18 22:59:15', '2025-05-18 22:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `astrologers`
--

CREATE TABLE `astrologers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `astrologers`
--

INSERT INTO `astrologers` (`id`, `user_id`, `bio`, `expertise`, `profile_image`, `created_at`, `updated_at`) VALUES
(2, 6, 'yes', 'yes', '1747589115_s6.png', '2025-05-18 22:55:15', '2025-05-18 22:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `astrologer_id` int(11) DEFAULT NULL,
  `feedback` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generated_horoscope`
--

CREATE TABLE `generated_horoscope` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `zodiac` varchar(20) DEFAULT NULL,
  `general_message` text DEFAULT NULL,
  `today_message` text DEFAULT NULL,
  `advice_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `generated_horoscope`
--

INSERT INTO `generated_horoscope` (`id`, `user_id`, `zodiac`, `general_message`, `today_message`, `advice_message`, `created_at`) VALUES
(1, 3, 'Taurus', 'Taurus, grounded and dependable, you enjoy stability.', 'Good day to handle finances. Be patient.', 'Avoid being too rigid in thoughts.', '2025-05-18 18:13:41'),
(2, 3, 'Taurus', 'Taurus, grounded and dependable, you enjoy stability.', 'Good day to handle finances. Be patient.', 'Avoid being too rigid in thoughts.', '2025-05-18 18:39:14'),
(3, 3, 'Taurus', 'Taurus, grounded and dependable, you enjoy stability.', 'Good day to handle finances. Be patient.', 'Avoid being too rigid in thoughts.', '2025-05-18 18:39:46'),
(4, 3, 'Taurus', 'Taurus, grounded and dependable, you enjoy stability.', 'Good day to handle finances. Be patient.', 'Avoid being too rigid in thoughts.', '2025-05-21 17:13:30'),
(5, 3, 'Taurus', 'Taurus, grounded and dependable, you enjoy stability.', 'Good day to handle finances. Be patient.', 'Avoid being too rigid in thoughts.', '2025-05-21 17:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `horoscopes`
--

CREATE TABLE `horoscopes` (
  `id` int(11) NOT NULL,
  `astrologer_id` int(11) NOT NULL,
  `zodiac_sign` varchar(50) NOT NULL,
  `type` enum('daily','weekly','monthly') NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `horoscope_details`
--

CREATE TABLE `horoscope_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dob` date NOT NULL,
  `birth_time` time NOT NULL,
  `birth_place` varchar(255) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `horoscope_details`
--

INSERT INTO `horoscope_details` (`id`, `user_id`, `dob`, `birth_time`, `birth_place`, `gender`, `created_at`) VALUES
(1, 3, '2025-05-13', '01:21:00', 'puri', 'female', '2025-05-18 23:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','astrologer','user') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(3, 'user', 'user1@gmail.com', '$2y$10$LRynmHubzmFdRhGsRwEEe.lHOnUNjA0YYQTi1YQi8oA96ikZ.9NVO', 'user', '2025-05-18 21:31:25', '2025-05-18 21:31:25'),
(4, 'Admin', 'admin@gmail.com', '$2y$10$zsXP8jM13y9W5BW74xgpauAk8uYlAS77QNPXQ/qqMPsbD4gu9f66O', 'admin', '2025-05-18 21:31:50', '2025-05-18 21:31:50'),
(6, 'astro', 'astro@example.com', '$2y$10$FrYpq3LTztq9GBYGby9LxekLMieADJvViie8A7YgHlqssBx.Eq20y', 'astrologer', '2025-05-18 21:57:48', '2025-05-18 21:57:48'),
(7, 'Kkkk', 'k@gmail.com', '$2y$10$odQwfi6VWTQdlx8ii9fV0.lIYJqE8d8XwByneEejRhBy2wtx19gEu', 'user', '2025-05-21 22:31:03', '2025-05-21 22:31:03');

-- --------------------------------------------------------

--
-- Table structure for table `zodiac_signs`
--

CREATE TABLE `zodiac_signs` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_range` varchar(50) NOT NULL,
  `element` varchar(50) DEFAULT NULL,
  `modality` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zodiac_signs`
--

INSERT INTO `zodiac_signs` (`id`, `name`, `date_range`, `element`, `modality`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Aries', 'March 21 - April 19', 'Fire', 'Cardinal', 'Aries are courageous and energetic.', 'aries.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(2, 'Taurus', 'April 20 - May 20', 'Earth', 'Fixed', 'Taurus are reliable and patient.', 'taurus.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(3, 'Gemini', 'May 21 - June 20', 'Air', 'Mutable', 'Geminis are adaptable and outgoing.', 'gemini.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(4, 'Cancer', 'June 21 - July 22', 'Water', 'Cardinal', 'Cancer is emotional and intuitive.', 'cancer.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(5, 'Leo', 'July 23 - August 22', 'Fire', 'Fixed', 'Leo is confident and charismatic.', 'leo.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(6, 'Virgo', 'August 23 - September 22', 'Earth', 'Mutable', 'Virgos are analytical and kind.', 'virgo.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(7, 'Libra', 'September 23 - October 22', 'Air', 'Cardinal', 'Libra is diplomatic and gracious.', 'libra.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(8, 'Scorpio', 'October 23 - November 21', 'Water', 'Fixed', 'Scorpio is resourceful and brave.', 'scorpio.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(9, 'Sagittarius', 'November 22 - December 21', 'Fire', 'Mutable', 'Sagittarius is optimistic and adventurous.', 'sagittarius.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(10, 'Capricorn', 'December 22 - January 19', 'Earth', 'Cardinal', 'Capricorn is disciplined and wise.', 'capricorn.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(11, 'Aquarius', 'January 20 - February 18', 'Air', 'Fixed', 'Aquarius is innovative and independent.', 'aquarius.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03'),
(12, 'Pisces', 'February 19 - March 20', 'Water', 'Mutable', 'Pisces are compassionate and artistic.', 'pisces.png', '2025-05-18 21:18:03', '2025-05-18 21:18:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `astrologer_id` (`astrologer_id`);

--
-- Indexes for table `astrologers`
--
ALTER TABLE `astrologers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `astrologer_id` (`astrologer_id`);

--
-- Indexes for table `generated_horoscope`
--
ALTER TABLE `generated_horoscope`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `horoscopes`
--
ALTER TABLE `horoscopes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `astrologer_id` (`astrologer_id`);

--
-- Indexes for table `horoscope_details`
--
ALTER TABLE `horoscope_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `zodiac_signs`
--
ALTER TABLE `zodiac_signs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `astrologers`
--
ALTER TABLE `astrologers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `generated_horoscope`
--
ALTER TABLE `generated_horoscope`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `horoscopes`
--
ALTER TABLE `horoscopes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `horoscope_details`
--
ALTER TABLE `horoscope_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `zodiac_signs`
--
ALTER TABLE `zodiac_signs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`astrologer_id`) REFERENCES `astrologers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `astrologers`
--
ALTER TABLE `astrologers`
  ADD CONSTRAINT `astrologers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`astrologer_id`) REFERENCES `astrologers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `horoscopes`
--
ALTER TABLE `horoscopes`
  ADD CONSTRAINT `horoscopes_ibfk_1` FOREIGN KEY (`astrologer_id`) REFERENCES `astrologers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `horoscope_details`
--
ALTER TABLE `horoscope_details`
  ADD CONSTRAINT `horoscope_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
