-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2024 at 03:01 PM
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
(2, 3, '2024-10-07 06:32:29', 100.00, '1', 'cash on delivery', 'for_delivery', '2024-10-15 11:10:30'),
(3, 3, '2024-10-07 07:56:48', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-07 14:59:00'),
(4, 2, '2024-10-07 08:06:27', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-07 15:06:49'),
(5, 2, '2024-10-07 08:15:13', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-07 15:16:08'),
(6, 2, '2024-10-10 07:57:00', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-11 14:36:35'),
(7, 2, '2024-10-11 07:51:03', 100.00, '1', 'cash on delivery', 'canceled', '2024-10-11 20:37:41'),
(8, 2, '2024-10-11 13:58:14', 3000.00, '30', 'gcash', 'canceled', '2024-10-14 12:23:59'),
(9, 3, '2024-10-14 06:44:08', 1000.00, '10', 'cash on delivery', 'canceled', '2024-10-14 13:45:30');

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
  `tracking_status` enum('packing','shipped','delivered') DEFAULT 'packing',
  `placed_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL,
  `shipping_status` enum('not shipped','shipped') DEFAULT 'not shipped',
  `refund_requested` tinyint(1) DEFAULT 0,
  `refund_status` enum('not_refunded','refunded') DEFAULT 'not_refunded'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `method`, `address`, `product_names`, `product_quantities`, `total_price`, `receipt_image`, `payment_status`, `tracking_status`, `placed_on`, `status`, `shipping_status`, `refund_requested`, `refund_status`) VALUES
(2, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, 'gcash (2).jpg', 'completed', 'packing', '2024-09-13 15:55:39', 'delivered', 'not shipped', 0, 'not_refunded'),
(3, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '278249465_518965119829242_3292343790797041615_n.jpg', 'completed', 'packing', '2024-09-13 16:46:34', 'delivered', 'not shipped', 0, 'not_refunded'),
(4, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-13 16:47:45', 'delivered', 'not shipped', 0, 'not_refunded'),
(5, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-14 16:12:47', NULL, 'not shipped', 0, 'not_refunded'),
(6, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-14 16:13:23', NULL, 'not shipped', 0, 'not_refunded'),
(7, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-14 16:16:16', NULL, 'not shipped', 0, 'not_refunded'),
(8, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-14 16:16:39', 'delivered', 'not shipped', 0, 'not_refunded'),
(10, 3, 'gcash', 'No address provided', 'test', '1', 100.00, 'Screenshot 2024-08-20 060155.png', 'completed', 'packing', '2024-09-18 16:09:30', NULL, 'not shipped', 0, 'not_refunded'),
(11, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-18 16:10:35', NULL, 'not shipped', 0, 'not_refunded'),
(12, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-18 16:11:38', NULL, 'not shipped', 0, 'not_refunded'),
(13, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-18 16:11:54', NULL, 'not shipped', 0, 'not_refunded'),
(14, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-18 16:12:04', NULL, 'not shipped', 0, 'not_refunded'),
(15, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-18 16:14:44', NULL, 'not shipped', 0, 'not_refunded'),
(16, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-18 16:15:14', NULL, 'not shipped', 0, 'not_refunded'),
(17, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-18 16:15:24', NULL, 'not shipped', 0, 'not_refunded'),
(18, 3, 'gcash', 'No address provided', 'test', '1', 100.00, 'Screenshot 2024-08-20 060155.png', 'completed', 'packing', '2024-09-18 16:15:54', NULL, 'not shipped', 0, 'not_refunded'),
(20, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:28:25', NULL, 'not shipped', 0, 'not_refunded'),
(21, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-19 15:28:45', NULL, 'not shipped', 0, 'not_refunded'),
(22, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-19 15:31:49', NULL, 'not shipped', 0, 'not_refunded'),
(23, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:51:43', NULL, 'not shipped', 0, 'not_refunded'),
(24, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:54:22', NULL, 'not shipped', 0, 'not_refunded'),
(25, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 15:59:57', NULL, 'not shipped', 0, 'not_refunded'),
(26, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 16:01:07', NULL, 'not shipped', 0, 'not_refunded'),
(27, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 16:04:59', NULL, 'not shipped', 0, 'not_refunded'),
(28, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', '', 'packing', '2024-09-19 16:06:48', NULL, 'not shipped', 0, 'not_refunded'),
(29, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-19 16:10:39', NULL, 'not shipped', 0, 'not_refunded'),
(30, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:13:22', NULL, 'not shipped', 0, 'not_refunded'),
(31, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:20:48', NULL, 'not shipped', 0, 'not_refunded'),
(32, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:24:24', NULL, 'not shipped', 0, 'not_refunded'),
(33, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:25:01', NULL, 'not shipped', 0, 'not_refunded'),
(34, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:28:15', NULL, 'not shipped', 0, 'not_refunded'),
(35, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:29:20', NULL, 'not shipped', 0, 'not_refunded'),
(36, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-09-20 11:30:17', NULL, 'not shipped', 0, 'not_refunded'),
(41, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:17:33', NULL, 'not shipped', 0, 'not_refunded'),
(42, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:26:08', NULL, 'not shipped', 0, 'not_refunded'),
(43, 3, 'cash on delivery', 'No address provided', 'test1', '2', 200.00, '', 'completed', 'packing', '2024-10-01 16:31:16', NULL, 'not shipped', 0, 'not_refunded'),
(44, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:32:04', NULL, 'not shipped', 0, 'not_refunded'),
(45, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:45:02', NULL, 'not shipped', 0, 'not_refunded'),
(46, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:48:40', NULL, 'not shipped', 0, 'not_refunded'),
(47, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:54:13', NULL, 'not shipped', 0, 'not_refunded'),
(48, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:55:33', NULL, 'not shipped', 0, 'not_refunded'),
(49, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-01 16:58:55', NULL, 'not shipped', 0, 'not_refunded'),
(50, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 15:59:26', NULL, 'not shipped', 0, 'not_refunded'),
(51, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:06:01', NULL, 'not shipped', 0, 'not_refunded'),
(52, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:08:13', NULL, 'not shipped', 0, 'not_refunded'),
(53, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:11:03', NULL, 'not shipped', 0, 'not_refunded'),
(54, 2, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-03 16:14:51', 'delivered', 'not shipped', 0, 'not_refunded'),
(56, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'packing', '2024-10-07 14:10:15', NULL, 'not shipped', 0, 'not_refunded'),
(57, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '', 'completed', 'shipped', '2024-10-07 14:31:15', NULL, 'not shipped', 0, 'not_refunded'),
(59, 3, 'cash on delivery', 'No address provided', 'test', '1', 100.00, '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', 'completed', 'delivered', '2024-10-07 15:00:22', 'delivered', 'not shipped', 0, 'not_refunded');

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
(5, 'test', 'test', 'test', 'test', 100.00, 100.00, 'test', 'csph_title.png', 'csph_title.png', 'csph_title.png', 'Car Exhaust', 18, '2024-10-10'),
(6, 'test2', 'test2', 'test2', 'test2', 100.00, 100.00, 'test2', '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', 'Motorcycle Exhaust', 6, '2024-10-23'),
(7, 'rens', 'gdgdg', 'dgdgd', 'gdgdg', 100.00, 100.00, 'dgdg', '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', '55811a63-8cb3-4e80-a803-b3f00955a642.jfif', 'Air Filter', 98, '2024-10-31');

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
(39, 69, 'king ina', '098625267282982', 'janlangpo', 'uploads/67098bc04b7a6-4aa2807d-8d35-4935-8999-759485e46d21-20180904180613519_profile.png', 'test2', 1, 100.00, 2, '2024-10-11 20:34:08');

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
(21, 2, 'dadada', 4, 'uploaded_img/IMG_1090.jpg', '2024-10-14 13:40:30');

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
(7, 'rider', 'hperformanceexhausta@gmail.com', 'bc7cafbd1f9bcb7a3065a603b98d5c45e60c67d9', '', '', '', '', '', '', '', '', 'rider', '2024-10-15 09:41:39', '', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `refund_products`
--
ALTER TABLE `refund_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
