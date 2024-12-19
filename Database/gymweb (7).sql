-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 05:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_role` enum('Admin','Normal') NOT NULL DEFAULT 'Normal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `username`, `password`, `admin_role`, `created_at`) VALUES
(1, 'Gaurav Malla', 'gaurav', '0000', 'Admin', '2024-06-18 10:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `membership_requests`
--

CREATE TABLE `membership_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `membership_type` enum('Basic','Premium','Standard') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `payment_method` enum('Cash','Online') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('confirmed','pending','canceled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, NULL, 'New order ID 5 has been placed by user ID 33.', '2024-08-03 13:08:32'),
(2, NULL, 'New order ID 6 has been placed by user ID 33.', '2024-08-03 13:10:12'),
(3, 33, 'Your order ID 1 has been confirmed!', '2024-08-03 13:17:12'),
(4, 33, 'Your order ID 4 has been confirmed!', '2024-08-03 13:18:07'),
(5, 33, 'Your order ID 3 has been confirmed!', '2024-08-03 13:18:08'),
(6, 33, 'Your order ID 6 has been confirmed!', '2024-08-03 13:18:09'),
(7, 33, 'Your order ID 6 has been confirmed!', '2024-08-03 13:18:10'),
(8, 33, 'Your order ID 5 has been confirmed!', '2024-08-03 13:18:11'),
(9, 33, 'Your order ID 5 has been confirmed!', '2024-08-03 13:18:12'),
(10, NULL, 'New order ID 7 has been placed by user ID 33.', '2024-08-03 15:23:49'),
(11, 33, 'Your order ID 5 has been confirmed!', '2024-08-03 15:23:54'),
(12, NULL, 'New order ID 8 has been placed by user ID 33.', '2024-08-03 15:34:59'),
(13, NULL, 'New order ID 9 has been placed by user ID 33.', '2024-08-03 15:37:09'),
(14, 33, 'Your order ID 9 has been confirmed!', '2024-08-03 15:37:40'),
(15, 33, 'Your order ID 9 has been confirmed!', '2024-08-03 15:38:17'),
(16, NULL, 'New order ID 10 has been placed by user ID 33.', '2024-08-03 15:39:22'),
(17, 33, 'Your order ID 9 has been confirmed!', '2024-08-03 15:39:37'),
(18, 33, 'Your order ID 10 has been confirmed!', '2024-08-03 15:39:39'),
(19, NULL, 'New order ID 11 has been placed by user ID 33.', '2024-08-04 14:40:27'),
(20, NULL, 'New order ID 12 has been placed by user ID 33.', '2024-08-04 15:24:34'),
(21, NULL, 'New order ID 13 has been placed by user ID 33.', '2024-08-04 15:25:20'),
(22, NULL, 'New order ID 14 has been placed by user ID 33.', '2024-08-04 15:38:08'),
(23, NULL, 'New order ID 15 has been placed by user ID 33.', '2024-08-04 15:43:11'),
(24, NULL, 'New order ID 16 has been placed by user ID 33.', '2024-08-04 15:47:12'),
(25, NULL, 'New order ID 17 has been placed by user ID 33.', '2024-08-08 12:55:05'),
(26, NULL, 'New order ID 18 has been placed by user ID 33.', '2024-08-12 06:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `end_date` datetime NOT NULL,
  `promo_code` varchar(50) NOT NULL,
  `discount_percentage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `end_date`, `promo_code`, `discount_percentage`) VALUES
(3, 'offer get 20% off', '2024-08-13 10:45:00', 'gaurav20', 20);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','confirmed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `address`, `phone`, `province`, `city`, `village`, `payment_method`, `status`, `created_at`) VALUES
(1, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-03 13:00:25'),
(3, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-03 13:04:20'),
(4, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-03 13:06:04'),
(5, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-03 13:08:32'),
(6, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-03 13:10:12'),
(8, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Card', '', '2024-08-03 15:34:59'),
(9, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-03 15:37:09'),
(10, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Online', 'confirmed', '2024-08-03 15:39:22'),
(11, 33, 'kathmandu', '8976457878', 'vbnjkl;lkj', 'sdrft', 'ahsuigcus', 'Cash', '', '2024-08-04 14:40:27'),
(12, 33, 'kapan,kathmandu nepal', '1234567890', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', '', '2024-08-04 15:24:34'),
(13, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', '', '2024-08-04 15:25:20'),
(14, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'sdrft', 'ahsuigcus', 'Online', 'confirmed', '2024-08-04 15:38:08'),
(15, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Online', 'canceled', '2024-08-04 15:43:11'),
(16, 33, 'kapan,kathmandu nepal', '9866887799', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-04 15:47:12'),
(17, 33, 'kathmandu', '1234567890', 'bagmati', 'sdrft', 'kathmandu', 'Cash', 'confirmed', '2024-08-08 12:55:05'),
(18, 33, 'kathmandu', '9896457878', 'bagmati', 'kathmandu', 'kathmandu', 'Cash', 'confirmed', '2024-08-12 06:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 5, 1, 45000.00),
(2, 4, 5, 1, 45000.00),
(3, 5, 5, 1, 45000.00),
(4, 6, 6, 1, 5000.00),
(6, 8, 6, 1, 5000.00),
(7, 9, 5, 1, 45000.00),
(8, 10, 6, 1, 5000.00),
(9, 11, 6, 1, 5000.00),
(10, 12, 5, 1, 45000.00),
(11, 13, 5, 8, 45000.00),
(12, 14, 5, 1, 45000.00),
(13, 14, 6, 1, 5000.00),
(14, 15, 5, 1, 45000.00),
(15, 16, 7, 1, 10000.00),
(16, 17, 5, 1, 45000.00),
(17, 18, 5, 1, 45000.00),
(18, 18, 7, 1, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `sold` int(11) NOT NULL DEFAULT 0,
  `available` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `category`, `price`, `discount`, `description`, `image`, `quantity`, `sold`, `available`) VALUES
(1, 'dumbell', 'machines', 4500.00, 10.00, 'hello', 'barbell.webp', 0, 0, 0),
(2, 'dumbell', 'machines', 45000.00, 10.00, 'hy', 'benchpress.jpg', 0, 0, 0),
(3, 'bench press', 'machines', 4500.00, 10.00, 'asdfghj', 'legcurlmachine.jpeg', 0, 0, 0),
(4, 'dumbell', 'machines', 4500.00, 10.00, 'fghjk', 'dumbells.jpeg', 10, 0, 0),
(5, 'bench press', 'machines', 45000.00, 10.00, 'fghjponb XKCBSALKNCLKS', 'Exercisebike.jpeg', 100, 16, 2),
(6, 'bench press', 'machines', 5000.00, 10.00, 'oigu', 'treadmills.jpg', 10, 7, 0),
(7, 'bench press', 'machines', 10000.00, 50.00, 'fitnepal', 'benchpress.jpg', 100, 2, 98),
(8, 'dumbells', 'machines', 2000.00, 1.00, 'fitnepal', 'dumbells.jpeg', 10, 0, 10),
(9, 'treadmill', 'machines', 5000.00, 2.00, 'fitnepal', 'treadmills.jpg', 25, 0, 25);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_capacity` int(11) NOT NULL DEFAULT 50,
  `current_capacity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `start_time`, `end_time`, `max_capacity`, `current_capacity`) VALUES
(1, '05:00:00', '08:00:00', 50, -5),
(2, '08:00:00', '11:00:00', 50, -6),
(3, '11:00:00', '14:00:00', 50, 0),
(4, '14:00:00', '17:00:00', 50, 0),
(5, '17:00:00', '20:00:00', 50, -6),
(6, '20:00:00', '22:00:00', 50, -2);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `schedule_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `username`, `password`, `user_type`) VALUES
(2, 'gaurav malla', 'gauravmalla27@gmail.com', 'gaurav000', '0000', 'admin'),
(7, 'hari', 'hari@gmail.com', 'hari', '3333', 'admin'),
(29, 'hello hwe', 'hello@gmail.com', 'hello2', 'llll', 'user'),
(30, 'hah haha', 'a@gmail.com', 'a', 'aaaa', 'user'),
(31, 'abc xyz', 'abc@gmail.com', 'abc', '$2y$10$uhXrxS2rBsMQwEz4Cbhpi.BQvMCJlNmyArNEeWCAYQD.B4KhjQzO2', 'user'),
(33, 'gaurab malla', 'gaurab@gmail.com', 'gaurab', '$2y$10$8THqjLGeVtGMaaTInp6cEuHaYVNW50b5jpDFjot1ZdFPendC3tRc2', 'user'),
(36, 'ram laxmn', 'ram@gmail.com', 'ram123', '$2y$10$lnWlV7PqIbNzmFBCr4eQg.UjJcSBwmLjVlH/uUHA3myrqBFroZm4a', 'user'),
(37, 'saroj ranjitkar', 'saroj@gmail.com', 'saroj@gmail.com', '$2y$10$ThMX0wDpKaox0vZN5DXz3edMJJfGS7Fv/fChDc1ZFZ/lXSU29h2nm', 'admin'),
(38, 'gaurav kakhsk', 'gauravma@gmail.com', 'gauramalla9', '$2y$10$Jk3Hd6IHlK6XgOgMDBMdaeK.UVO.kZHPVitCIVXntuV4AnmxEGvQW', 'user'),
(39, 'malla gaurav', 'gauravmalla444@gmail.com', 'gaurav4444', '$2y$10$J9aNDiRzt0Omr.TBBBTAK.FLndMDjBUCKzpKvO5doOhZCCRZcawCu', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- Indexes for table `membership_requests`
--
ALTER TABLE `membership_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `membership_requests`
--
ALTER TABLE `membership_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
