-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql202.infinityfree.com
-- Generation Time: Jan 29, 2025 at 11:22 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38179053_event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `max_capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `date`, `max_capacity`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'scrum bbb', 'Learn about scrum master in the event details event', '2025-01-28', 5, '2025-01-25 17:28:58', '2025-01-27 03:35:44', 1),
(3, 'programming', 'problem solving in the event learn new things', '2025-01-26', 120, '2025-01-25 17:30:44', '2025-01-27 03:10:39', 1),
(4, 'programming contest', 'testing purpose contest', '2025-01-28', 5, '2025-01-25 18:04:31', '2025-01-25 18:05:06', 3),
(5, 'java workshop', 'workshop detail would be set here ', '2025-01-28', 2, '2025-01-25 18:05:42', '2025-01-25 18:05:49', 3),
(10, 'Test event', 'Test des lorem ipsum\r\n', '2025-01-30', 5, '2025-01-26 18:16:27', '2025-01-28 04:38:54', 1),
(11, 'TechFest 2025', 'Join the biggest technology festival where innovators, developers, and tech enthusiasts come together to explore the latest trends, AI breakthroughs, and smart gadgets.', '2025-01-29', 10, '2025-01-29 14:51:04', '2025-01-29 14:51:04', 1),
(12, 'Summer Music Bash', 'An unforgettable evening filled with live performances from your favorite artists, food trucks, and a spectacular fireworks display.', '2025-01-30', 10, '2025-01-29 14:53:48', '2025-01-29 14:53:48', 1),
(13, 'Art & Craft Expo 2025', 'Discover unique handmade crafts, paintings, and sculptures from talented local artists. Workshops and DIY sessions included!', '2025-01-30', 10, '2025-01-29 14:54:22', '2025-01-29 14:54:22', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
