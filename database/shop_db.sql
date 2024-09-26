-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 01:24 AM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(2, 'karl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

-- --------------------------------------------------------

--
-- Table structure for table `archived_messages`
--

CREATE TABLE `archived_messages` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_content` text DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_orders`
--

CREATE TABLE `archived_orders` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `details` text DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_products`
--

CREATE TABLE `archived_products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `automobile_type` varchar(100) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `selling_price` varchar(100) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `image_01` varchar(255) DEFAULT NULL,
  `image_02` varchar(255) DEFAULT NULL,
  `image_03` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archived_products`
--

INSERT INTO `archived_products` (`id`, `name`, `manufacturer`, `type`, `automobile_type`, `price`, `selling_price`, `details`, `image_01`, `image_02`, `image_03`, `category`, `quantity`, `date`, `deleted_at`) VALUES
(1, 'test1', 'test1', 'test1', 'test1', '100.00', '100.00', 'test1', 'csph_title.png', 'csph_title.png', 'csph_title.png', 'Motorcycle Exhaust', 97, NULL, '2024-09-26 12:41:41'),
(2, 'test1', 'test1', 'test1', 'test1', '100.00', '100.00', 'test1', 'csph_title.png', 'csph_title.png', 'csph_title.png', 'Motorcycle Exhaust', 97, NULL, '2024-09-26 12:42:06'),
(3, 'test', 'test', 'test', 'test', '100.00', '100.00', 'test', 'csph_title.png', 'csph_title.png', 'csph_title.png', 'Car Exhaust', 100, NULL, '2024-09-26 12:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `archived_users`
--

CREATE TABLE `archived_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archived_users`
--

INSERT INTO `archived_users` (`id`, `user_id`, `name`, `email`, `deleted_at`) VALUES
(1, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:52:04'),
(2, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:53:15'),
(3, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:53:16'),
(4, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:53:17'),
(5, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:53:17'),
(6, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:53:17'),
(7, 6, 'yanna', 'dejucoslizette@gmail.com', '2024-09-26 12:53:51'),
(8, 4, 'rider', 'rider@gmail.com', '2024-09-26 12:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `canceled_orders`
--

CREATE TABLE `canceled_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `placed_on` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_quantities` text NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'canceled',
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `canceled_orders`
--

INSERT INTO `canceled_orders` (`id`, `user_id`, `placed_on`, `total_price`, `product_quantities`, `method`, `status`, `deleted_at`) VALUES
(1, 3, '2024-09-20 06:12:31', 100.00, '1', 'gcash', 'canceled', '2024-09-26 14:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(49, 3, 4, 'test1', 100, 2, 'csph_title.png');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `order_id`, `user_id`, `invoice_number`, `total_amount`, `date_created`) VALUES
(1, 20, 2, 'INV-66EC4319CD278', 100.00, '2024-09-19 15:28:25'),
(2, 21, 2, 'INV-66EC432DB7388', 100.00, '2024-09-19 15:28:45'),
(3, 22, 2, 'INV-66EC43E5115D0', 100.00, '2024-09-19 15:31:49'),
(4, 26, 2, '', 100.00, '2024-09-19 16:01:07'),
(5, 27, 2, '', 100.00, '2024-09-19 16:04:59'),
(6, 28, 2, '', 100.00, '2024-09-19 16:06:48');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(1, 0, 'test', 'wasieacuna@gmail.com', '5436464643', 'test kupal');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `method` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `product_names` text NOT NULL,
  `product_quantities` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','completed') DEFAULT 'pending',
  `placed_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `method`, `address`, `product_names`, `product_quantities`, `total_price`, `receipt_image`, `payment_status`, `placed_on`, `status`) VALUES
(2, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, 'gcash (2).jpg', 'completed', '2024-09-13 15:55:39', 'delivered'),
(3, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '278249465_518965119829242_3292343790797041615_n.jpg', 'completed', '2024-09-13 16:46:34', 'delivered'),
(4, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-13 16:47:45', 'delivered'),
(5, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-14 16:12:47', NULL),
(6, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-14 16:13:23', NULL),
(7, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-14 16:16:16', NULL),
(8, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-14 16:16:39', 'delivered'),
(10, 3, 'gcash', 'No address provided', 'test', '1', 100.00, 'Screenshot 2024-08-20 060155.png', 'completed', '2024-09-18 16:09:30', NULL),
(11, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', '2024-09-18 16:10:35', NULL),
(12, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-18 16:11:38', NULL),
(13, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', '2024-09-18 16:11:54', NULL),
(14, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-18 16:12:04', NULL),
(15, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-18 16:14:44', NULL),
(16, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-18 16:15:14', NULL),
(17, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-18 16:15:24', NULL),
(18, 3, 'gcash', 'No address provided', 'test', '1', 100.00, 'Screenshot 2024-08-20 060155.png', 'completed', '2024-09-18 16:15:54', NULL),
(20, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 15:28:25', NULL),
(21, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', '2024-09-19 15:28:45', NULL),
(22, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', '2024-09-19 15:31:49', NULL),
(23, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 15:51:43', NULL),
(24, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 15:54:22', NULL),
(25, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 15:59:57', NULL),
(26, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 16:01:07', NULL),
(27, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 16:04:59', NULL),
(28, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', '2024-09-19 16:06:48', NULL),
(29, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-19 16:10:39', NULL),
(30, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:13:22', NULL),
(31, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:20:48', NULL),
(32, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:24:24', NULL),
(33, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:25:01', NULL),
(34, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:28:15', NULL),
(35, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:29:20', NULL),
(36, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', '2024-09-20 11:30:17', NULL),
(39, 2, 'cash on delivery', 'No address provided', 'test1', '1', 100.00, '', 'pending', '2024-09-25 13:37:41', 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `automobile_type` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `details` text NOT NULL,
  `image_01` varchar(255) NOT NULL,
  `image_02` varchar(255) NOT NULL,
  `image_03` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `manufacturer`, `type`, `automobile_type`, `price`, `selling_price`, `details`, `image_01`, `image_02`, `image_03`, `category`, `quantity`, `date`) VALUES
(5, 'test', 'test', 'test', 'test', 100.00, 100.00, 'test', 'csph_title.png', 'csph_title.png', 'csph_title.png', 'Car Exhaust', 100, '2024-10-10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `house_number` varchar(10) NOT NULL,
  `street` varchar(50) NOT NULL,
  `subdivision` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `user_type` enum('admin','user','rider') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `house_number`, `street`, `subdivision`, `address`, `city`, `country`, `mobile`, `pin_code`, `user_type`, `created_at`) VALUES
(2, 'admin', 'wasieacuna@gmail.com', 'd9b3f01220869f2095809214fef10899f9b2234b', '', '', '', 'janlangpo', 'antipolo', 'Philippines', '09510608496', '1990', 'admin', '2024-09-08 13:09:46'),
(3, 'admin', 'hperformanceexhaust@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', '', '', 'janlangpo', 'antipolo', 'Philippines', '09510608496', '1990', 'rider', '2024-09-08 13:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archived_messages`
--
ALTER TABLE `archived_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archived_orders`
--
ALTER TABLE `archived_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archived_products`
--
ALTER TABLE `archived_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `archived_users`
--
ALTER TABLE `archived_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `canceled_orders`
--
ALTER TABLE `canceled_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `archived_messages`
--
ALTER TABLE `archived_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archived_orders`
--
ALTER TABLE `archived_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archived_products`
--
ALTER TABLE `archived_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `archived_users`
--
ALTER TABLE `archived_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `canceled_orders`
--
ALTER TABLE `canceled_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
