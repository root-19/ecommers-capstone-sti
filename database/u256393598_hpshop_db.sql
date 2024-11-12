-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2024 at 02:23 AM
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
-- Database: `u256393598_hpshop_db`
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
(2, 3, '2024-10-07 06:32:29', 100.00, '1', 'cash on delivery', 'for_delivery', '2024-10-15 11:10:30'),
(3, 3, '2024-10-07 07:56:48', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-07 14:59:00'),
(4, 2, '2024-10-07 08:06:27', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-07 15:06:49'),
(5, 2, '2024-10-07 08:15:13', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-07 15:16:08'),
(6, 2, '2024-10-10 07:57:00', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-11 14:36:35'),
(7, 2, '2024-10-11 07:51:03', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-11 20:37:41'),
(8, 2, '2024-10-11 13:58:14', 3000.00, '30', 'gcash', 'canceled', '2024-10-14 12:23:59'),
(9, 3, '2024-10-14 06:44:08', 1000.00, '10', 'cash on delivery', 'canceled', '2024-10-14 13:45:30'),
(14, 3, '2024-10-27 09:09:33', 1650.00, '1', 'cash on delivery', 'canceled', '2024-10-27 16:16:05'),
(15, 2, '2024-11-07 08:49:38', 1199.00, '1', 'cash on delivery', 'canceled', '2024-11-07 16:52:46'),
(16, 3, '2024-11-07 09:05:10', 1199.00, '1', 'cash on delivery', 'canceled', '2024-11-07 17:05:17'),
(17, 7, '2024-11-07 09:09:56', 1199.00, '1', 'cash on delivery', 'canceled', '2024-11-07 17:13:03');

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
(102, 10, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 1786, 4, '4.jfif'),
(106, 11, 21, 'J151 Magna Flow Black Series Style Exhaust Universal Car Accessories Muffler Tip Round Stainles', 11386, 1, '51.png'),
(107, 11, 6, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 1650, 1, '10.png'),
(157, 3, 5, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 1199, 1, '7.webp');

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
(1, 0, 'test', 'wasieacuna@gmail.com', '5436464643', 'test kupal'),
(2, 0, 'Brianna Belton', 'pageranktechnology@gmail.com', '1201201200', 'Greeting of the day,\r\n\r\nwww.hpperformanceexhaust.com\r\n \r\nWe offer the following Services at affordable Cost:\r\n\r\nLike: - Website Design, Graphic Design & Re-Design. Web Development, Mobile Apps Development or want some additional features with latest technological trends?\r\n\r\nAre you thinking to upgrade or build new website/mobile app? Or if you want to get idea, how much it would cost you?\r\n\r\nReply me back with your requirements.\r\n\r\nKindest Regards,\r\nBrianna Belton\r\n\r\n\r\n\r\n\r\n\r\nIf you don’t want me'),
(3, 0, 'Sam Georgia', 'sam@getonglobe.com', '+1 (917) 310', 'Hi [hpperformanceexhaust.com],\r\n\r\nI am Sam (Online Marketing Executive), checking your website see you have a good design and it looks great, but it&#39;s not ranking on Google and other major search engines.\r\n\r\nWe can place your website on Google&#39;s 1st page. Yahoo, Facebook, LinkedIn, YouTube, Instagram, Pinterest etc.).\r\n\r\nLet me know if you are interested, then I can send our Packages and Pricelist.\r\n\r\nCheers,\r\nSam Georgia - (Sr SEO consultant)\r\nwww.GetOnGlobe.com\r\nCall: +1 (917) 310-3348');

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
  `tracking_status` enum('packing','shipped','delivered') DEFAULT 'packing',
  `placed_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL,
  `shipping_status` enum('not shipped','shipped') DEFAULT 'not shipped',
  `refund_requested` tinyint(1) DEFAULT 0,
  `refund_status` enum('not_refunded','refunded') DEFAULT 'not_refunded',
  `hidden_status` tinyint(1) DEFAULT 0,
  `payment_segundary` enum('pending','completed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `method`, `address`, `product_names`, `product_quantities`, `total_price`, `receipt_image`, `payment_status`, `tracking_status`, `placed_on`, `status`, `shipping_status`, `refund_requested`, `refund_status`, `hidden_status`, `payment_segundary`) VALUES
(20, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'delivered', '2024-09-19 15:28:25', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(21, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:28:45', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(22, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-19 15:31:49', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(23, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-19 15:51:43', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(24, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:54:22', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(25, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:59:57', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(26, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 16:01:07', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(27, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 16:04:59', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(28, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-19 16:06:48', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(29, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 16:10:39', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(30, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:13:22', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(31, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:20:48', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(32, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:24:24', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(33, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:25:01', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(34, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:28:15', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(35, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:29:20', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(36, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:30:17', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(41, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:17:33', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(42, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:26:08', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(43, 3, 'cash on delivery', 'No address provided', 'test1', '2', 200.00, '', 'completed', 'packing', '2024-10-01 16:31:16', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(44, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:32:04', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(45, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:45:02', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(46, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:48:40', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(47, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:54:13', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(48, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:55:33', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(49, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:58:55', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(50, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 15:59:26', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(51, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:06:01', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(52, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:08:13', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(53, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:11:03', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(54, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:14:51', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(56, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-07 14:10:15', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(57, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'shipped', '2024-10-07 14:31:15', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(59, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', 'completed', 'delivered', '2024-10-07 15:00:22', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(80, 10, 'cash on delivery', 'Address: Mama Sit Eatery', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'shipped', '2024-10-16 01:23:37', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(81, 10, 'cash on delivery', 'Address: Mama Sit Eatery', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'packing', '2024-10-16 05:19:40', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(82, 10, 'cash on delivery', 'Address: Mama Sit Eatery', 'Sand Blasting Stainless Steel Exhaust Tail Tips for VW Golf 6 Golf 7 Tiguan R(AK Logo) Exhaust Muffl', '2', 4054.00, '', '', '', '2024-10-17 03:49:35', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(83, 10, 'cash on delivery', 'Address: Mama Sit Eatery', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK), Akrapovic Car Exhaust Muffler Twin Tip Pipe Y-type Glossy Carbon Fiber Dual Red Tip Pipe Tailpipe En', '1, 1', 3317.00, '', 'completed', 'packing', '2024-10-17 05:09:14', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(84, 10, 'cash on delivery', 'Address: Mama Sit Eatery', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-10-17 05:20:56', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(85, 9, 'cash on delivery', 'Address: blk 2 lt 3 gomez kapalaran taytay rizal', 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', '4', 7144.00, '', 'completed', 'shipped', '2024-10-17 05:30:41', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(86, 11, 'cash on delivery', 'Address: 123, St. Luke, Palmera 4, Brgy. Dolores, Taytay, Rizal', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-10-17 16:08:51', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(87, 2, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', '', 'shipped', '2024-10-25 13:19:07', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(88, 2, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-10-25 13:20:55', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(89, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-10-26 16:32:29', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(90, 2, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-10-26 16:33:24', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(92, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'shipped', '2024-10-27 16:02:05', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(101, 7, 'cash on delivery', 'No address provided', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, 'Segunda.jpg', 'completed', 'shipped', '2024-11-07 17:15:51', 'delivered', 'not shipped', 0, 'not_refunded', 0, 'pending'),
(102, 7, 'gcash', 'No address provided', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, 'gcash.jpg', 'completed', 'packing', '2024-11-07 17:20:43', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(103, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 13:57:05', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(104, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 14:27:12', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(105, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 14:38:28', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(106, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 14:44:18', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(107, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 19:46:48', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(108, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 20:38:05', NULL, 'not shipped', 0, 'not_refunded', 1, 'pending'),
(109, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 20:38:43', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(110, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 20:44:08', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(111, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 20:44:54', NULL, 'not shipped', 0, 'not_refunded', 1, 'pending'),
(112, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 20:45:29', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(113, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 20:52:07', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(114, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 20:54:33', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(115, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Carbon Fiber Glossy Gold Exhaust Tip Dual Pipe OD:76/89/101mm Tip', '1', 2118.00, '', '', 'delivered', '2024-11-08 20:58:49', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(116, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-08 21:06:27', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(117, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 21:20:21', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(118, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-08 21:37:14', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(119, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 21:41:50', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(120, 3, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-08 21:42:24', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(121, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-09 16:06:54', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(122, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-09 16:21:56', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(124, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-09 16:25:43', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(125, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-09 16:33:07', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(126, 2, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-09 16:33:38', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(127, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-09 16:34:56', NULL, 'not shipped', 0, 'not_refunded', 0, 'pending'),
(128, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-09 16:38:44', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(129, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-09 16:50:11', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(130, 2, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', '', 'delivered', '2024-11-09 16:56:02', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(131, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-09 17:01:58', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(132, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', '', 'delivered', '2024-11-09 17:04:08', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(133, 2, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-09 17:08:29', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(134, 2, 'cash on delivery', 'Address: janlangpo', 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', '1', 1199.00, '', 'completed', 'delivered', '2024-11-09 17:10:53', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed'),
(135, 3, 'cash on delivery', 'Address: janlangpo', 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', '1', 1650.00, '', 'completed', 'delivered', '2024-11-09 17:15:58', NULL, 'not shipped', 0, 'not_refunded', 0, 'completed');

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
(4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 1786.00, 2586.00, 'Product: Universal Exhaust PipeModified exhaust pipe type\r\nApplicable models\r\nAdaptation Model\r\nMatching relationship\r\nWeight: about 1.5 kg\r\nCondition: 100% Brand New\r\nMaterial: Stainless Steel/Carbon Fiber', '4.jfif', '5.jfif', '6.jfif', 'Motorcycle Exhaust', 10, '2024-08-22'),
(5, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 'Akropovic', 'Tip', 'Car', 1199.00, 1549.00, 'Akrapovic Exhaust Single Tip\r\nCarbon Fiber Glossy Universal\r\nSmall Inlet: 2 inch  Outlet: 2.5 inch  Length: 6.7 inches\r\nMedium Inlet: 2.5 inch  Outlet: 3 inch  Length 6.7 inches\r\nLarge Inlet: 3 inch  Outlet: 3.5 inch  Length 6.7 inches\r\nPlease check the size before buying.\r\n', '7.webp', '8.webp', '9.webp', 'Exhaust Auxiliaries', 28, '2024-08-22'),
(6, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 'Akropovic', 'Tip', 'Car', 1650.00, 2250.00, 'The adapter of this product allows the installation of pipe diameters of 36mm-51mm. *Durable, no rust, no corrosion.\r\nWorks as long as your elbow is 36-51mm in diameter.\r\nPackaging includes:\r\n1 x Exhaust Pipe\r\n1*Silencer\r\n1* 36-51mm adapter', '10.png', '11.png', '11.png', 'Exhaust Auxiliaries', 6, '2024-08-22'),
(7, '1Piece Akrapovic Car Carbon Fiber Muffler Tip Y Shape Double Exit Exhaust Pipe Mufflers Nozzle Decor', 'Akropovic', 'Exhaust', 'Car', 2149.00, 2507.00, '&#34;Material: Carbon +304 Stainless Steel\r\n\r\nWeight: 1.75kg\r\n\r\nInlet Size: 48 51 54 57 60 63 67 70 73 76 80 MM\r\n\r\nOutlet Size: 76 89 101 114MM\r\n\r\nThe length about :240MM\r\n\r\nPackage Include: 1 Piece x Dual Exhaust Pipes with the clamp&#34;', '12.png', '13.png', '14.png', 'Car Exhaust', 6, '2024-08-29'),
(8, 'Akrapovic Carbon Fiber Glossy Gold Exhaust Tip Dual Pipe OD:76/89/101mm Tip', 'Akropovic', 'Exhaust Tip', 'Car', 2118.00, 2471.00, 'High quality carbon fiber exhaust tips for Any Cars.\r\nSuper light weight.\r\nExcellent fitment and Easy installation.\r\nAggressive new look.\r\nOEM & OE quality standard, as good as the origines.\r\nThe best price of all the products on the same quality-level.\r\nThe shortest time for goods-preparation and the soonest delivery.\r\nIndependent R&D department and excellent after-sale service.\r\n', '18.webp', '19.webp', '20.webp', 'Exhaust Auxiliaries', 6, '2024-08-29'),
(9, 'Akrapovic Car Exhaust Muffler Twin Tip Pipe Y-type Glossy Carbon Fiber Dual Red Tip Pipe Tailpipe En', 'Akropovic', 'Exhaust Tip', 'Car', 2118.00, 2471.00, 'Excellent fitment and Easy installation.\r\nAggressive new look.\r\nOEM & OE quality standard, as good as the origines.\r\nThe best price of all the products on the same quality-level.\r\nThe shortest time for goods-preparation and the soonest delivery.\r\nIndependent R&D department and excellent after-sale service.', '21.webp', '22.webp', '23.webp', 'Car Exhaust', 6, '2024-08-29'),
(10, 'Sand Blasting Stainless Steel Exhaust Tail Tips for VW Golf 6 Golf 7 Tiguan R(AK Logo) Exhaust Muffl', 'Akropovic', 'Exhaust Tip', 'Car', 2027.00, 2530.00, 'Material:  SUS 304 Stainless Steel \r\nFinish: Sand blasting                                  \r\nInlet: 51/54/57/60/63/67/70/73/77/80mm  \r\nOutlet: 89/101/114mm', '15.webp', '16.webp', '17.webp', 'Exhaust Auxiliaries', 6, '2024-08-29'),
(11, 'Car general 3 &#34;Imported 4&#34; Exhaust Tailpipe Stainless Steel Exhaust Pipe Automotive Exhaust Silencer', 'Borla', 'Exhaust', 'Car', 2550.00, 3299.00, 'Product Description\r\nBrand Name:None\r\nOrigin:CN(Origin)\r\nMaterial Type:Stainless Steel\r\nSpecification :\r\nOverall length : 30.48cm/12&#34;\r\nInlet size : 7.62cm/3&#34;\r\nOutlet size : 10.16cm/4&#34;\r\nSlant Tip Angle: Approximately 20°\r\nFitment : Fit For Most Vehicles With 3 inch Outside Diameter Pipe ( For Reference.)', '24.webp', '25.webp', '26.webp', 'Car Exhaust', 0, '2024-07-11'),
(12, 'BORLA Car Exhaust Muffler Oval Straight BORLA Muffler (5 x 9)', 'BorlaS', 'Exhaust', 'Car', 5050.00, 5990.00, 'Universal muffler. Reversible design.\r\nMulticore internal flow tube construction\r\nDesigned for Venues requiring a muffler, but can be used on other applications', '27.webp', '28.webp', '29.webp', 'Car Exhaust', 0, '2024-07-11'),
(13, 'BORLA medium muffler tip chrome silver (each)', 'Borla', 'Exhaust Tip', 'Car', 2290.00, 2800.00, 'BORLA MUFFLER TIP\r\nhigh grade stainless steel\r\nsilver\r\nborla logo embossed\r\n\r\ninlet: 2.5”\r\noutler: 4”\r\nlength: 10”', '30.png', '30.png', '30.png', 'Exhaust Auxiliaries', 0, '2024-07-11'),
(14, 'BORLA Black Muffler Tip', 'Borla', 'Exhaust Tip', 'Car', 2390.00, 2900.00, 'BORLA MUFFLER TIP\r\nhigh grade stainless steel\r\nblack\r\nborla logo embossed\r\n\r\ninlet: 2.5”\r\noutler: 4”\r\nlength: 10”', '31.webp', '32.webp', '31.webp', 'Exhaust Auxiliaries', 0, '2024-07-11'),
(15, 'BORLA ATAK 3 inches INLET', 'Borla', 'Exhaust', 'Car', 11500.00, 12999.00, '&#34;Borla ATAK\r\nPerfect for any Cars\r\nThailand Copy\r\n\r\nInlet: 3”\r\nOutlet: 3”\r\nLength: 18”&#34;\r\n', '36.webp', '37.webp', '38.webp', 'Car Exhaust', 0, '2024-07-12'),
(16, 'BORLA Free Flow Muffler', 'Borla', 'Exhaust', 'Motorcycle', 2990.00, 3590.00, 'BORLA Free Flow Muffler\r\nhigh grade stainless steel\r\nchrome silver\r\nborla logo embossed\r\n\r\ninlet: 2”\r\noutler: 3”\r\nlength: 16”', '39.webp', '40.webp', '41.webp', 'Motorcycle Exhaust', 0, '2024-07-12'),
(17, 'Borla Long Tip Black & Silver for Diesel', 'Borla', 'Exhaust Tip', 'Motorcycle', 5550.00, 6150.00, 'Borla Tip\r\nColor: Black & Silver\r\nDiesel Pipe / Muffler\r\nPerfect for SUV & Pickup Cars\r\nThailand Copy\r\n\r\nInlet: 2.5”\r\nOutlet: 4”\r\nLength: 13.5”', '33.png', '34.webp', '35.webp', 'Exhaust Auxiliaries', 0, '2024-07-12'),
(18, 'HKS Medium CF Orig Muffler Jasma Can Deep Free flow Japan titanium burnt high quality Stainless', 'HKS', 'Exhaust', 'Motorcycle', 2599.00, 3100.00, 'High Quality Universal Muffler\r\n- Made in Japan\r\n- Material:  Stainless with CF TIP\r\n- Super Deep Full Sound\r\n- Not Can Like Sound\r\n- Actual Pictures are Posted', '42.webp', '43.webp', '44.webp', 'Motorcycle Exhaust', 0, '2024-07-18'),
(19, 'HKS Small Hypershort Jasma Quality Hipower Muffler Freeflow Free flow cars accessories spoon greddy', 'HKS', 'Exhaust', 'Car', 1599.00, 1899.00, '- Made in Japan\r\n- Material:  Stainless Coated with Titanium\r\n- Super Deep Full Sound\r\n- Not Can Like Sound\r\n- Actual Pictures are Posted\r\n- Titanium Burnt Design\r\n- None Fade Design\r\n- Fast Shipping and Cash On Delivery\r\n- Size indicated on Picture\r\n\r\nSample cars: 600cc-1.3cc\r\nWigo,Eon,Yaris,Jazz,City,Brio,Mirage hatch, I10,Getz, Picanto, swift, Pride, Charade, Spark and other car like. ', '45.webp', '46.webp', '47.webp', 'Car Exhaust', 0, '2024-07-18'),
(20, 'HKS Car Exhaust Dry BAS Sound Booster For MATIC And MANUAL Cars', 'HKS', 'Exhaust', 'Car', 1112.00, 1812.00, 'Fullstainless\r\nUsing welding with argon welding\r\nDeep strainer using drill pipe without glaswool\r\n2 inch Inlet\r\n3 inch Outlet\r\nFull length - / + 25cm\r\nDry/random bass sound specs\r\nItems price includes shipping fee that should be borne by the buyer.', '48.webp', '49.webp', '50.webp', 'Car Exhaust', 0, '2024-07-18'),
(21, 'J151 Magna Flow Black Series Style Exhaust Universal Car Accessories Muffler Tip Round Stainles', 'Magna Flow', 'Exhaust', 'Car', 11386.00, 12499.00, 'J151 Magna Flow Black Series Style Exhaust Universal Car Accessories Muffler Tip Round Stainless Steel For Jeep Wrangler JK\r\n', '51.png', '52.webp', '53.webp', 'Car Exhaust', 0, '2024-08-01'),
(22, 'Magna Flow Sensor Air Flow Meter Sensor For Mitsubishi Magna Pajero Nimbus Uf Triton', 'Magna Flow', 'Meter Sensor', 'Car', 2648.00, 2948.00, 'For Mitsubishi: Magna, Numbus, Pajero, Starwagon, Triton, Verada', '54.webp', '55.webp', '56.webp', 'Air Filter', 0, '2024-08-01'),
(23, 'K&N Universal Automobile Car Filber Air Filter Auto Air Intake Reusable', 'K&N', 'Air Filter', 'Car', 415.00, 649.00, 'The filter will improve overall air filtration to optimize throttle response, improve torque and fuel / gas mileage.\r\nIt is washable and will last the lifetime of your engine\r\nHelp to draw more air into your engine so as to improve throttle response and enhance horsepower\r\nThe fine mesh increases the amount of air flow.\r\nStraightens the flow and stops sucking hot air from high temperature engine compartment\r\nEasy to install (directly bolt on)', '57.webp', '58.webp', '59.webp', 'Air Filter', 0, '2024-08-01'),
(24, 'K&N 99-5000 Aerosol Recharger Filter Care Service Kit, Air Filter Cleaning Kit', 'K&N', 'Service Kit', 'Motorcycle', 1786.00, 1999.00, 'CLEANS ALL OILED K&N AIR FILTERS\r\n\r\nRESTORES PERFORMANCE: Helps restore your filter to exceptional performance by removing filter clogging build up\r\nINCLUDES POWER KLEEN: A powerful, highly effective degreaser that quickly dissolves filter build up and old oil, allowing filter grime to be rinsed away with waterINCLUDES K&N RED FILTER OIL: K&N red filter oil remains suspended in the pleats of the cotton filter material of High-Flow Air Filters, allowing for exceptional contaminant capture\r\nCLEANING STEPS: Spray on Power Kleen, Rinse with water, Allow filter to dry, Apply fresh filter oil\r\n\r\nRENEWED EFFICIENCY: Restores air flow efficiency so your K&N air filter performs like new', '60.webp', '61.webp', '62.webp', 'Air Filter', 0, '2024-08-01');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `automobile_type` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `code_number` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `product_id`, `name`, `manufacturer`, `type`, `automobile_type`, `category`, `quantity`, `price`, `purchase_date`, `code_number`) VALUES
(214, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 10, 1786, '2024-11-12', '426667'),
(215, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 10, 1786, '2024-11-12', '371841'),
(216, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 10, 1786, '2024-11-12', '612016'),
(217, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 10, 1786, '2024-11-12', '557948'),
(218, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 10, 1786, '2024-11-12', '615884');

-- --------------------------------------------------------

--
-- Table structure for table `receive_order`
--

CREATE TABLE `receive_order` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `automobile_type` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `receive_date` date DEFAULT NULL,
  `code_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receive_order`
--

INSERT INTO `receive_order` (`id`, `product_id`, `name`, `manufacturer`, `type`, `automobile_type`, `category`, `price`, `quantity`, `receive_date`, `code_number`) VALUES
(5, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 1786.00, 100, '2024-11-06', 764824),
(6, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 1786.00, 136, '2024-11-06', 144683),
(7, 5, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 'Akropovic', 'Tip', 'Car', 'Exhaust Auxiliaries', 1199.00, 10, '2024-11-06', 114499),
(8, 6, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 'Akropovic', 'Tip', 'Car', 'Exhaust Auxiliaries', 1650.00, 9, '2024-11-06', 915431),
(9, 7, '1Piece Akrapovic Car Carbon Fiber Muffler Tip Y Shape Double Exit Exhaust Pipe Mufflers Nozzle Decor', 'Akropovic', 'Exhaust', 'Car', 'Car Exhaust', 2149.00, 9, '2024-11-06', 236504),
(10, 8, 'Akrapovic Carbon Fiber Glossy Gold Exhaust Tip Dual Pipe OD:76/89/101mm Tip', 'Akropovic', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2118.00, 9, '2024-11-06', 273795),
(11, 9, 'Akrapovic Car Exhaust Muffler Twin Tip Pipe Y-type Glossy Carbon Fiber Dual Red Tip Pipe Tailpipe En', 'Akropovic', 'Exhaust Tip', 'Car', 'Car Exhaust', 2118.00, 7, '2024-11-06', 246108),
(12, 10, 'Sand Blasting Stainless Steel Exhaust Tail Tips for VW Golf 6 Golf 7 Tiguan R(AK Logo) Exhaust Muffl', 'Akropovic', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2027.00, 1, '2024-11-06', 883011),
(13, 11, 'Car general 3 &#34;Imported 4&#34; Exhaust Tailpipe Stainless Steel Exhaust Pipe Automotive Exhaust ', 'Borla', 'Exhaust', 'Car', 'Car Exhaust', 2550.00, 10, '2024-11-06', 760264),
(14, 12, 'BORLA Car Exhaust Muffler Oval Straight BORLA Muffler (5 x 9)', 'BorlaS', 'Exhaust', 'Car', 'Car Exhaust', 5050.00, 0, '2024-11-06', 764075),
(15, 13, 'BORLA medium muffler tip chrome silver (each)', 'Borla', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2290.00, 13, '2024-11-06', 820412),
(16, 14, 'BORLA Black Muffler Tip', 'Borla', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2390.00, 10, '2024-11-06', 110226),
(17, 15, 'BORLA ATAK 3 inches INLET', 'Borla', 'Exhaust', 'Car', 'Car Exhaust', 11500.00, 5, '2024-11-06', 871253),
(18, 16, 'BORLA Free Flow Muffler', 'Borla', 'Exhaust', 'Motorcycle', 'Motorcycle Exhaust', 2990.00, 10, '2024-11-06', 377485),
(19, 17, 'Borla Long Tip Black & Silver for Diesel', 'Borla', 'Exhaust Tip', 'Motorcycle', 'Exhaust Auxiliaries', 5550.00, 8, '2024-11-06', 602456),
(20, 18, 'HKS Medium CF Orig Muffler Jasma Can Deep Free flow Japan titanium burnt high quality Stainless', 'HKS', 'Exhaust', 'Motorcycle', 'Motorcycle Exhaust', 2599.00, 27, '2024-11-06', 786215),
(21, 19, 'HKS Small Hypershort Jasma Quality Hipower Muffler Freeflow Free flow cars accessories spoon greddy', 'HKS', 'Exhaust', 'Car', 'Car Exhaust', 1599.00, 8, '2024-11-06', 978679),
(22, 20, 'HKS Car Exhaust Dry BAS Sound Booster For MATIC And MANUAL Cars', 'HKS', 'Exhaust', 'Car', 'Car Exhaust', 1112.00, 6, '2024-11-06', 916315),
(23, 21, 'J151 Magna Flow Black Series Style Exhaust Universal Car Accessories Muffler Tip Round Stainles', 'Magna Flow', 'Exhaust', 'Car', 'Car Exhaust', 11386.00, 3, '2024-11-06', 271458),
(24, 22, 'Magna Flow Sensor Air Flow Meter Sensor For Mitsubishi Magna Pajero Nimbus Uf Triton', 'Magna Flow', 'Meter Sensor', 'Car', 'Air Filter', 2648.00, 10, '2024-11-06', 184261),
(25, 23, 'K&N Universal Automobile Car Filber Air Filter Auto Air Intake Reusable', 'K&N', 'Air Filter', 'Car', 'Air Filter', 415.00, 10, '2024-11-06', 292615),
(26, 24, 'K&N 99-5000 Aerosol Recharger Filter Care Service Kit, Air Filter Cleaning Kit', 'K&N', 'Service Kit', 'Motorcycle', 'Air Filter', 1786.00, 15, '2024-11-06', 301621),
(27, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 1786.00, 270, '2024-11-06', 389128),
(28, 5, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 'Akropovic', 'Tip', 'Car', 'Exhaust Auxiliaries', 1199.00, 18, '2024-11-06', 268538),
(29, 6, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 'Akropovic', 'Tip', 'Car', 'Exhaust Auxiliaries', 1650.00, 18, '2024-11-06', 337175),
(30, 7, '1Piece Akrapovic Car Carbon Fiber Muffler Tip Y Shape Double Exit Exhaust Pipe Mufflers Nozzle Decor', 'Akropovic', 'Exhaust', 'Car', 'Car Exhaust', 2149.00, 18, '2024-11-06', 248683),
(31, 8, 'Akrapovic Carbon Fiber Glossy Gold Exhaust Tip Dual Pipe OD:76/89/101mm Tip', 'Akropovic', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2118.00, 18, '2024-11-06', 553925),
(32, 9, 'Akrapovic Car Exhaust Muffler Twin Tip Pipe Y-type Glossy Carbon Fiber Dual Red Tip Pipe Tailpipe En', 'Akropovic', 'Exhaust Tip', 'Car', 'Car Exhaust', 2118.00, 14, '2024-11-06', 771245),
(33, 10, 'Sand Blasting Stainless Steel Exhaust Tail Tips for VW Golf 6 Golf 7 Tiguan R(AK Logo) Exhaust Muffl', 'Akropovic', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2027.00, 2, '2024-11-06', 386568),
(34, 11, 'Car general 3 &#34;Imported 4&#34; Exhaust Tailpipe Stainless Steel Exhaust Pipe Automotive Exhaust ', 'Borla', 'Exhaust', 'Car', 'Car Exhaust', 2550.00, 20, '2024-11-06', 697720),
(35, 12, 'BORLA Car Exhaust Muffler Oval Straight BORLA Muffler (5 x 9)', 'BorlaS', 'Exhaust', 'Car', 'Car Exhaust', 5050.00, 0, '2024-11-06', 858629),
(36, 13, 'BORLA medium muffler tip chrome silver (each)', 'Borla', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2290.00, 26, '2024-11-06', 990860),
(37, 14, 'BORLA Black Muffler Tip', 'Borla', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2390.00, 20, '2024-11-06', 430887),
(38, 15, 'BORLA ATAK 3 inches INLET', 'Borla', 'Exhaust', 'Car', 'Car Exhaust', 11500.00, 10, '2024-11-06', 249410),
(39, 16, 'BORLA Free Flow Muffler', 'Borla', 'Exhaust', 'Motorcycle', 'Motorcycle Exhaust', 2990.00, 20, '2024-11-06', 212345),
(40, 17, 'Borla Long Tip Black & Silver for Diesel', 'Borla', 'Exhaust Tip', 'Motorcycle', 'Exhaust Auxiliaries', 5550.00, 16, '2024-11-06', 532062),
(41, 18, 'HKS Medium CF Orig Muffler Jasma Can Deep Free flow Japan titanium burnt high quality Stainless', 'HKS', 'Exhaust', 'Motorcycle', 'Motorcycle Exhaust', 2599.00, 54, '2024-11-06', 645856),
(42, 19, 'HKS Small Hypershort Jasma Quality Hipower Muffler Freeflow Free flow cars accessories spoon greddy', 'HKS', 'Exhaust', 'Car', 'Car Exhaust', 1599.00, 16, '2024-11-06', 890381),
(43, 20, 'HKS Car Exhaust Dry BAS Sound Booster For MATIC And MANUAL Cars', 'HKS', 'Exhaust', 'Car', 'Car Exhaust', 1112.00, 12, '2024-11-06', 902163),
(44, 21, 'J151 Magna Flow Black Series Style Exhaust Universal Car Accessories Muffler Tip Round Stainles', 'Magna Flow', 'Exhaust', 'Car', 'Car Exhaust', 11386.00, 6, '2024-11-06', 313395),
(45, 22, 'Magna Flow Sensor Air Flow Meter Sensor For Mitsubishi Magna Pajero Nimbus Uf Triton', 'Magna Flow', 'Meter Sensor', 'Car', 'Air Filter', 2648.00, 20, '2024-11-06', 685754),
(46, 23, 'K&N Universal Automobile Car Filber Air Filter Auto Air Intake Reusable', 'K&N', 'Air Filter', 'Car', 'Air Filter', 415.00, 20, '2024-11-06', 213689),
(47, 24, 'K&N 99-5000 Aerosol Recharger Filter Care Service Kit, Air Filter Cleaning Kit', 'K&N', 'Service Kit', 'Motorcycle', 'Air Filter', 1786.00, 45, '2024-11-06', 425073),
(48, 10, 'Sand Blasting Stainless Steel Exhaust Tail Tips for VW Golf 6 Golf 7 Tiguan R(AK Logo) Exhaust Muffl', 'Akropovic', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2027.00, 6, '2024-11-11', 314678),
(49, 5, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 'Akropovic', 'Tip', 'Car', 'Exhaust Auxiliaries', 1199.00, 6, '2024-11-11', 792558),
(50, 6, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 'Akropovic', 'Tip', 'Car', 'Exhaust Auxiliaries', 1650.00, 6, '2024-11-11', 391180),
(51, 7, '1Piece Akrapovic Car Carbon Fiber Muffler Tip Y Shape Double Exit Exhaust Pipe Mufflers Nozzle Decor', 'Akropovic', 'Exhaust', 'Car', 'Car Exhaust', 2149.00, 6, '2024-11-11', 748587),
(52, 8, 'Akrapovic Carbon Fiber Glossy Gold Exhaust Tip Dual Pipe OD:76/89/101mm Tip', 'Akropovic', 'Exhaust Tip', 'Car', 'Exhaust Auxiliaries', 2118.00, 6, '2024-11-11', 397757),
(53, 9, 'Akrapovic Car Exhaust Muffler Twin Tip Pipe Y-type Glossy Carbon Fiber Dual Red Tip Pipe Tailpipe En', 'Akropovic', 'Exhaust Tip', 'Car', 'Car Exhaust', 2118.00, 6, '2024-11-11', 188052),
(54, 4, 'Akrapovic M1 with DB killer/Silencer included 51mm inlet', 'sssss', 'test', 'Motorcycle', 'Motorcycle Exhaust', 1786.00, 10, '2024-11-12', 884089);

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `concern` text NOT NULL,
  `gcash_number` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `refunds`
--

INSERT INTO `refunds` (`id`, `order_id`, `concern`, `gcash_number`, `address`, `image_path`, `product_name`, `product_quantity`, `total_price`, `user_id`, `created_at`) VALUES
(37, 69, 'dadada', 'adad', 'dada', NULL, '', 0, 0.00, 2, '2024-10-11 20:27:33'),
(38, 69, 'dadada', 'adad', 'dada', NULL, '', 0, 0.00, 2, '2024-10-11 20:33:41'),
(39, 69, 'king ina', '098625267282982', 'janlangpo', 'uploads/67098bc04b7a6-4aa2807d-8d35-4935-8999-759485e46d21-20180904180613519_profile.png', 'test2', 1, 100.00, 2, '2024-10-11 20:34:08'),
(40, 90, 'ss', 'sSsSsdsfsfsf', 'sss', NULL, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 1, 1199.00, 2, '2024-10-26 16:36:11'),
(41, 91, 'aaa', 'dadada', 'aa', NULL, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 1, 1199.00, 3, '2024-10-27 16:16:20'),
(42, 91, 'aaa', 'dadada', 'aa', NULL, 'Akrapovic Exhaust Single Tip Muffler Tail Pipe Akrapovic Carbon Fiber Glossy Universal Car (BLACK)', 1, 1199.00, 3, '2024-10-27 16:16:34'),
(43, 93, 'ssss', '098625267282982', 'sadsa', NULL, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 1, 1650.00, 3, '2024-11-07 16:53:13'),
(44, 93, 'ssss', '098625267282982', 'sadsa', NULL, 'MATTE BLACK AKRAPOVIC E20 DUAL TIP MUFFLER EXHAUST PIPE / Muffler Akra', 1, 1650.00, 3, '2024-11-07 16:53:28');

-- --------------------------------------------------------

--
-- Table structure for table `refund_products`
--

CREATE TABLE `refund_products` (
  `id` int(11) NOT NULL,
  `refund_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantities` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review_message` text NOT NULL,
  `rating` int(1) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `review_message`, `rating`, `image_path`, `created_at`) VALUES
(1, 2, 'zczcz', 4, 'uploaded_img/Primera.jpg', '2024-10-14 13:22:47'),
(2, 2, 'zczcz', 4, 'uploaded_images/Primera.jpg', '2024-10-14 13:26:53'),
(3, 2, 'tangina', 1, 'uploaded_images/Tercera.jpg', '2024-10-14 13:27:16'),
(4, 2, 'tangina', 1, 'uploaded_img/Tercera.jpg', '2024-10-14 13:27:36'),
(5, 2, 'tangina', 1, 'uploaded_img/Tercera.jpg', '2024-10-14 13:30:13'),
(6, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:30:49'),
(7, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:31:13'),
(8, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:31:48'),
(9, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:33:01'),
(10, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:33:09'),
(11, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:33:46'),
(12, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:35:24'),
(13, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:37:18'),
(14, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:37:43'),
(15, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:37:59'),
(16, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:38:14'),
(17, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:38:26'),
(18, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:39:54'),
(19, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:40:07'),
(20, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:40:20'),
(21, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:40:30'),
(22, 2, 'tang ina ', 4, 'uploaded_img/MelRibeiro - Overview.gif', '2024-11-08 13:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `discount` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last` varchar(200) NOT NULL,
  `pin_point` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `house_number`, `street`, `subdivision`, `address`, `city`, `country`, `mobile`, `pin_code`, `user_type`, `created_at`, `last`, `pin_point`) VALUES
(2, 'admin', 'wasieacuna@gmail.com', 'd9b3f01220869f2095809214fef10899f9b2234b', '3432', '32ddf', 'rosal streets', 'janlangpo', 'antipolo', 'Philippines', '09510608496', '1990', 'user', '2024-09-08 13:09:46', 'acuna', 'lumang bayan executive village,30 rosal street mayamot    '),
(3, 'admin', 'hperformanceexhaust@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', '', '', 'janlangpo', 'antipolo', 'Philippines', '09510608496', '1990', 'admin', '2024-09-08 13:14:26', '', ''),
(7, 'rider', 'rider@gmail.com', 'bc7cafbd1f9bcb7a3065a603b98d5c45e60c67d9', '', '', '', '', '', '', '', '', 'rider', '2024-10-15 09:41:39', '', ''),
(8, 'Yves', 'canedo.kyc@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Blk 39 Lt7', 'Begonia', 'Simona', 'Block 39 Lot 7 Simona Subdivision Taytay Rizal', 'Taytay', '', '09473442229', '1920', 'user', '2024-10-15 16:31:10', '', 'H4JP+PXP Taytay, Rizal'),
(9, 'Canedo', 'curl.yves06@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 'blk 2 lt 3', 'gomez', 'kapalaran', 'blk 2 lt 3 gomez kapalaran taytay rizal', 'Taytay', '', '09090909091', '1920', 'user', '2024-10-16 01:12:43', '', 'H46Q+CP Taytay, Rizal'),
(10, 'yves', 'curl.yves24@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Blk 39 Lt7', 'Begonia', 'Simona', 'Mama Sit Eatery', 'Taytay', '', '09765323222', '1920', 'user', '2024-10-16 01:15:04', '', 'Mama Sit Eatery'),
(11, 'San Diego', 'sandiegoelvin07@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '123', 'St. Luke', 'Palmera 4', '123, St. Luke, Palmera 4, Brgy. Dolores, Taytay, Rizal', 'Taytay', '', '09299775070', '1920', 'user', '2024-10-17 16:06:10', '', '123');

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
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(1, 9, 22, 'Magna Flow Sensor Air Flow Meter Sensor For Mitsubishi Magna Pajero Nimbus Uf Triton', 2648, '54.webp');

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
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `receive_order`
--
ALTER TABLE `receive_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `refund_products`
--
ALTER TABLE `refund_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refund_id` (`refund_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `receive_order`
--
ALTER TABLE `receive_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `refund_products`
--
ALTER TABLE `refund_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `receive_order`
--
ALTER TABLE `receive_order`
  ADD CONSTRAINT `receive_order_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refund_products`
--
ALTER TABLE `refund_products`
  ADD CONSTRAINT `refund_products_ibfk_1` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
