-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2023 at 09:55 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `customer_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `invoice_number` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `invoice_number`, `user`, `comment`, `date`) VALUES
(7, 3, 1, 'test test', '2023-10-20 10:12:19'),
(8, 4, 1, 'Wrong Charges.', '2023-10-20 10:15:23'),
(9, 4, 1, 'Apologies, charges were correct.', '2023-10-20 10:15:33'),
(10, 3, 1, 'Wrong weight on load.', '2023-10-20 10:16:01'),
(13, 4, 1, 'Test inserttt edited', '2023-10-26 00:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`) VALUES
(1, 'test company inc.');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_number` int(11) NOT NULL,
  `load_number` int(11) DEFAULT NULL,
  `invoice_status` varchar(50) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_due_date` date DEFAULT NULL,
  `billing_contact` varchar(50) DEFAULT NULL,
  `freight_charges` decimal(10,2) DEFAULT NULL,
  `fuel_surcharge` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_number`, `load_number`, `invoice_status`, `invoice_date`, `invoice_due_date`, `billing_contact`, `freight_charges`, `fuel_surcharge`) VALUES
(3, 1003552, 'Pending', '2023-10-15', '2023-11-15', 'Jane Smith', 1300.60, 160.35),
(4, 1003551, 'Pending', '2023-11-11', '2024-01-02', 'Edvinas Test', 1234.44, 222.55),
(7, 1003552, 'Pending', '0000-00-00', '0000-00-00', 'Edvinas', 1000.20, 200.50),
(10, 1003552, 'Pending', '2023-10-27', '2023-11-10', 'Edvinas Test2', 600.00, 60.00);

-- --------------------------------------------------------

--
-- Table structure for table `loads`
--

CREATE TABLE `loads` (
  `load_number` int(11) NOT NULL,
  `load_status` text NOT NULL,
  `load_mode` text NOT NULL,
  `customer_quote_total` decimal(10,2) NOT NULL,
  `customer_invoice_total` decimal(10,2) NOT NULL,
  `total_weight` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loads`
--

INSERT INTO `loads` (`load_number`, `load_status`, `load_mode`, `customer_quote_total`, `customer_invoice_total`, `total_weight`) VALUES
(1003551, 'In Transit', 'Truckload', 1153.77, 1153.77, 2345.80),
(1003552, 'Completed', 'LTL', 3388.47, 3388.47, 6041.75),
(1003553, 'Delivered', 'Truckload', 1500.50, 1500.50, 3445.80),
(1003554, 'Assigned', 'LTL', 3328.47, 3328.47, 1399.90),
(1003556, 'In Transit', 'LTL', 1500.77, 1600.79, 2000.75);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(70) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `can_create_loads` tinyint(1) DEFAULT NULL,
  `username` varchar(70) DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `can_create_loads`, `username`, `password`, `company`) VALUES
(1, 'Testas', 'testas@gmail.com', '+1111144442', 1, 'testas', 'testas123', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Invoice Number` (`invoice_number`),
  ADD KEY `User` (`user`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_number`),
  ADD KEY `Load Number` (`load_number`);

--
-- Indexes for table `loads`
--
ALTER TABLE `loads`
  ADD PRIMARY KEY (`load_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company` (`company`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `loads`
--
ALTER TABLE `loads`
  MODIFY `load_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003557;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`invoice_number`) REFERENCES `invoices` (`invoice_number`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`User`) REFERENCES `users` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`load_number`) REFERENCES `loads` (`load_number`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company`) REFERENCES `companies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
