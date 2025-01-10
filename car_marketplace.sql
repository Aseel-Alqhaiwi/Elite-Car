-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 10:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `name`, `brand`, `price`, `description`, `image`, `created_at`) VALUES
(1, 'Toyota Corolla', 'Toyota', 20000.00, 'Year: 2023\r\nHorsepower: 169 hp\r\nEngine: 2.0L 4-cylinder\r\nTransmission: CVT (Continuously Variable Transmission)\r\nMPG: Up to 31 city / 40 highway\r\nSeating Capacity: 5\r\nFeatures: Apple CarPlay, Android Auto, Toyota Safety Sense', 'toyota_corolla.png', '2024-12-09 18:11:32'),
(2, 'Toyota Camry', 'Toyota', 26000.00, 'Year: 2023\r\nHorsepower: 203 hp\r\nEngine: 2.5L 4-cylinder or 3.5L V6\r\nTransmission: 8-speed automatic\r\nMPG: Up to 28 city / 39 highway\r\nSeating Capacity: 5\r\nFeatures: Adaptive cruise control, lane-keeping assist, premium audio\r\n', 'camry.jpg', '2024-12-12 17:10:31'),
(3, 'Toyota RAV4', 'Toyota', 28000.00, 'Year: 2023\r\nHorsepower: 203 hp\r\nEngine: 2.5L 4-cylinder\r\nTransmission: 8-speed automatic\r\nMPG: Up to 27 city / 35 highway\r\nSeating Capacity: 5\r\nFeatures: AWD option, Toyota Safety Sense, power liftgate', 'Rav4.jpg', '2024-12-12 17:29:19'),
(5, 'Toyota Highlander', 'Toyota', 36000.00, 'Year: 2023\r\nHorsepower: 265 hp\r\nEngine: 2.4L turbocharged 4-cylinder\r\nTransmission: 8-speed automatic\r\nMPG: Up to 22 city / 29 highway\r\nSeating Capacity: 7-8\r\nFeatures: Panoramic sunroof, premium leather seats, advanced safety features', 'Toyota-Highlander.jpg', '2024-12-12 21:38:28'),
(6, 'Toyota Tacoma', 'Toyota', 27000.00, 'Year: 2020\r\nHorsepower: 159 hp (base engine), 278 hp (V6)\r\nEngine: 2.7L 4-cylinder or 3.5L V6\r\nTransmission: 6-speed automatic or manual\r\nMPG: Up to 20 city / 23 highway\r\nSeating Capacity: 5\r\nFeatures: Off-road capabilities, towing capacity up to 6,800 lbs', 'Toyota-Tacoma.jpg', '2024-12-12 21:42:29'),
(7, 'Toyota Land Cruiser', 'Toyota', 87000.00, 'Year: 2025\r\nHorsepower: 409 hp\r\nEngine: 3.5L twin-turbo V6\r\nTransmission: 10-speed automatic\r\nMPG: Up to 17 city / 22 highway\r\nSeating Capacity: 7-8\r\nFeatures: Luxury interior, advanced off-road capabilities', 'Toyota-Land-Cruiser.png', '2024-12-12 21:48:02'),
(8, 'Honda Civic', 'Honda', 24000.00, 'Year: 2023\r\nHorsepower: 158 hp\r\nEngine: 2.0L 4-cylinder\r\nTransmission: CVT\r\nMPG: Up to 31 city / 40 highway\r\nSeating Capacity: 5\r\nFeatures: Apple CarPlay, Android Auto, Honda Sensing safety suite', 'honda-civic.jpg', '2024-12-12 22:06:52'),
(9, 'Nissan Rogue', 'Nissan', 28000.00, 'Year: 2022\r\nHorsepower: 201 hp\r\nEngine: 1.5L turbocharged 3-cylinder\r\nTransmission: CVT\r\nMPG: Up to 30 city / 37 highway\r\nSeating Capacity: 5\r\nFeatures: Advanced safety features, panoramic sunroof, versatile cargo space', 'Nissan-Rogue.jpg', '2024-12-12 22:30:28'),
(10, 'Honda Accord', 'Honda', 26000.00, 'Year: 2022\r\nHorsepower: 192 hp\r\nEngine: 1.5L turbocharged 4-cylinder\r\nTransmission: CVT\r\nMPG: Up to 30 city / 38 highway\r\nSeating Capacity: 5\r\nFeatures: Dual-zone climate control, touchscreen infotainment, adaptive cruise control', 'Honda-Accord.png', '2024-12-12 22:40:06'),
(11, 'Honda CR-V', 'Honda', 28000.00, 'Year: 2021\r\nHorsepower: 190 hp\r\nEngine: 1.5L turbocharged 4-cylinder\r\nTransmission: CVT\r\nMPG: Up to 28 city / 34 highway\r\nSeating Capacity: 5\r\nFeatures: All-wheel drive, power tailgate, Honda Sensing suite', 'Honda-CR-V.jpg', '2024-12-12 22:43:27'),
(12, 'Honda Pilot', 'Honda', 32000.00, 'Year: 2020\r\nHorsepower: 280 hp\r\nEngine: 3.5L V6\r\nTransmission: 9-speed automatic\r\nMPG: Up to 20 city / 27 highway\r\nSeating Capacity: 7-8\r\nFeatures: Tri-zone climate control, rear entertainment system, advanced safety features', 'Honda-Pilot.png', '2024-12-12 22:49:20'),
(13, 'Honda Fit', 'Honda', 16000.00, 'Year: 2019\r\nHorsepower: 130 hp\r\nEngine: 1.5L 4-cylinder\r\nTransmission: CVT or 6-speed manual\r\nMPG: Up to 33 city / 40 highway\r\nSeating Capacity: 5\r\nFeatures: Magic Seat for versatile cargo space, compact design, excellent fuel efficiency', 'Honda-Fit.jpg', '2024-12-12 22:49:48'),
(14, 'Nissan Altima', 'Nissan', 26000.00, 'Year: 2023\r\nHorsepower: 188 hp\r\nEngine: 2.5L 4-cylinder\r\nTransmission: CVT\r\nMPG: Up to 28 city / 39 highway\r\nSeating Capacity: 5\r\nFeatures: All-wheel drive option, ProPILOT Assist, touchscreen infotainment', 'Nissan-Altima.png', '2024-12-12 22:53:22'),
(15, 'Honda Odyssey', 'Honda', 29000.00, 'Year: 2018\r\nHorsepower: 280 hp\r\nEngine: 3.5L V6\r\nTransmission: 10-speed automatic\r\nMPG: Up to 19 city / 28 highway\r\nSeating Capacity: 7-8\r\nFeatures: Rear entertainment system, cabin intercom, advanced driver-assistance systems', 'Honda-Odyssey.jpg', '2024-12-12 22:58:25'),
(16, 'Nissan Sentra', 'Nissan', 20000.00, 'Year: 2021\r\nHorsepower: 149 hp\r\nEngine: 2.0L 4-cylinder\r\nTransmission: CVT\r\nMPG: Up to 29 city / 39 highway\r\nSeating Capacity: 5\r\nFeatures: Nissan Safety Shield 360, premium interior design, excellent fuel economy', 'Nissan-Sentra.png', '2024-12-12 23:05:02'),
(17, 'Nissan Pathfinder', 'Nissan', 34000.00, 'Year: 2020\r\nHorsepower: 284 hp\r\nEngine: 3.5L V6\r\nTransmission: CVT\r\nMPG: Up to 20 city / 27 highway\r\nSeating Capacity: 7\r\nFeatures: Intelligent 4x4 system, tow capacity up to 6,000 lbs, tri-zone climate control', 'Nissan-Pathfinder.jpg', '2024-12-12 23:07:46'),
(18, 'Nissan Leaf', 'Nissan', 12000.00, 'Year: 2019\r\nHorsepower: 147 hp (base) or 214 hp (Plus model)\r\nEngine: Electric motor\r\nRange: Up to 150 miles (base) or 226 miles (Plus model)\r\nTransmission: Single-speed automatic\r\nSeating Capacity: 5\r\nFeatures: Zero-emission electric vehicle, advanced safety systems, ProPILOT Assist', 'Nissan-Leaf.jpg', '2024-12-12 23:10:20'),
(19, 'Nissan Maxima', 'Nissan', 26000.00, 'Year: 2018\r\nHorsepower: 300 hp\r\nEngine: 3.5L V6\r\nTransmission: CVT\r\nMPG: Up to 21 city / 30 highway\r\nSeating Capacity: 5\r\nFeatures: Sporty design, luxury interior, NissanConnect infotainment system', 'Nissan-Maxima.jpg', '2024-12-12 23:13:42');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Aa lm', 'qqbv5500@gmail.com', 'Test', '2024-12-12 12:20:04');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `car_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 2, 5, 'This is a reliable and fuel efficient car.', '2024-12-11 10:12:25'),
(3, 6, 5, 5, 'This is a good choice\r\n', '2024-12-13 14:32:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `car_id`, `amount`, `status`, `created_at`) VALUES
(1, 2, 1, 20000.00, 'pending', '2024-12-11 10:05:19'),
(2, 2, 1, 20000.00, 'pending', '2024-12-11 10:18:34'),
(3, 2, 1, 20000.00, 'pending', '2024-12-11 10:23:42'),
(4, 2, 1, 20000.00, 'pending', '2024-12-11 10:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(2, 'Aseel Alqhaiwi', 'a9elalqhaiwi@gmail.com', '$2y$10$8mm7QcY8m6pc3Ae3dd4HLens2cOJtgKYlW7dsGOchWfjbB2pWc4GG', '2024-12-09 18:54:26'),
(5, 'Ahmad ali', 'qqbv5500@gmail.com', '$2y$10$bh1oAHCuREfpnQn/Eya4dOFtV5gaxM3Gij5Y/NQ84XxbpkmN4yFjW', '2024-12-13 14:31:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
