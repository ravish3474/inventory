-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2019 at 12:33 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessory`
--

CREATE TABLE `accessory` (
  `accessory_id` int(5) NOT NULL,
  `po_id` int(5) NOT NULL,
  `supplier_id` int(3) NOT NULL,
  `cat_id` int(3) NOT NULL,
  `accessory_size` varchar(200) NOT NULL,
  `accessory_color` varchar(200) NOT NULL,
  `accessory_in_price` double(20,2) NOT NULL,
  `accessory_in_total` double(20,2) NOT NULL,
  `accessory_piece` double(20,2) NOT NULL,
  `accessory_yard` double(20,2) NOT NULL,
  `accessory_use` double(20,2) NOT NULL,
  `accessory_balance` double(20,2) NOT NULL,
  `accessory_date_create` datetime NOT NULL,
  `accessory_user_create` int(3) NOT NULL,
  `accessory_date_update` datetime NOT NULL,
  `accessory_user_update` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cat`
--

CREATE TABLE `cat` (
  `cat_id` int(3) NOT NULL,
  `type_id` int(1) NOT NULL,
  `cat_code` varchar(200) NOT NULL,
  `cat_name_en` varchar(200) NOT NULL,
  `cat_name_th` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cat`
--

INSERT INTO `cat` (`cat_id`, `type_id`, `cat_code`, `cat_name_en`, `cat_name_th`) VALUES
(1, 1, 'AK PRO', 'AK PRO', 'AK PRO'),
(2, 1, 'AK PRO MAX', 'AK PRO MAX', 'AK PRO MAX'),
(6, 1, 'AK PRO MAX LIGHT', 'AK PRO MAX LIGHT', 'AK PRO MAX LIGHT');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `employee_name` varchar(200) NOT NULL,
  `employee_email` varchar(200) NOT NULL,
  `employee_tel` varchar(10) NOT NULL,
  `employee_position_id` int(2) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `employee_password` varchar(200) NOT NULL,
  `employee_auth` varchar(20) NOT NULL,
  `employee_image` varchar(200) NOT NULL,
  `employee_login_stat` int(1) NOT NULL,
  `employee_login_time` datetime NOT NULL,
  `employee_last_login` datetime NOT NULL,
  `employee_stat` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `employee_name`, `employee_email`, `employee_tel`, `employee_position_id`, `customer_id`, `employee_password`, `employee_auth`, `employee_image`, `employee_login_stat`, `employee_login_time`, `employee_last_login`, `employee_stat`) VALUES
(0001, 'aim', 'm@jogsportswear.com', '0891590124', 99, 0, 'c8758b517083196f05ac29810b924aca', '2011', '20181206181517.jpg', 1, '2019-04-11 17:19:04', '2019-04-11 15:51:32', 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE `employee_position` (
  `employee_position_id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `employee_position_name` varchar(200) NOT NULL,
  `employee_position_sort` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee_position`
--

INSERT INTO `employee_position` (`employee_position_id`, `employee_position_name`, `employee_position_sort`) VALUES
(01, 'CEO', 1),
(02, 'Sale', 2),
(03, 'Designer', 3),
(04, 'Production', 4),
(05, 'Sale Pro.', 5),
(06, ' Administrative', 6),
(07, 'Ass Production', 7),
(99, 'Administrator', 99);

-- --------------------------------------------------------

--
-- Table structure for table `fabric`
--

CREATE TABLE `fabric` (
  `fabric_id` int(5) NOT NULL,
  `po_id` int(5) NOT NULL,
  `supplier_id` int(3) NOT NULL,
  `cat_id` int(3) NOT NULL,
  `fabric_color` varchar(200) NOT NULL,
  `fabric_no` varchar(200) NOT NULL,
  `fabric_box` varchar(200) NOT NULL,
  `fabric_in_weight` double(10,2) NOT NULL,
  `fabric_in_yard` double(10,2) NOT NULL,
  `fabric_in_price` double(20,2) NOT NULL,
  `fabric_in_total` double(20,2) NOT NULL,
  `fabric_used` double(10,2) NOT NULL,
  `fabric_balance` double(10,2) NOT NULL,
  `fabric_total` double(10,2) NOT NULL,
  `fabric_amount` double(20,2) NOT NULL,
  `fabric_date_create` datetime NOT NULL,
  `fabric_user_create` int(3) NOT NULL,
  `fabric_date_update` datetime NOT NULL,
  `fabric_user_update` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `po_detail`
--

CREATE TABLE `po_detail` (
  `po_detail_id` int(5) NOT NULL,
  `po_id` int(5) NOT NULL,
  `po_type_id` int(5) NOT NULL,
  `cat_id` int(5) NOT NULL,
  `po_detail_color` varchar(200) NOT NULL,
  `po_detail_no` varchar(200) NOT NULL,
  `po_detail_box` varchar(200) NOT NULL,
  `po_detail_piece` double(20,2) NOT NULL,
  `po_detail_weight` double(20,2) NOT NULL,
  `po_detail_yard` double(20,2) NOT NULL,
  `po_detail_price` double(20,2) NOT NULL,
  `po_detail_total` double(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `po_detail`
--

INSERT INTO `po_detail` (`po_detail_id`, `po_id`, `po_type_id`, `cat_id`, `po_detail_color`, `po_detail_no`, `po_detail_box`, `po_detail_piece`, `po_detail_weight`, `po_detail_yard`, `po_detail_price`, `po_detail_total`) VALUES
(2, 1, 1, 1, '11', '22', '33', 44.00, 55.00, 66.00, 77.00, 88.00),
(3, 1, 1, 1, '11', '22', '33', 44.00, 55.00, 66.00, 77.00, 88.00),
(4, 1, 1, 1, '11', '22', '33', 44.00, 55.00, 66.00, 77.00, 88.00),
(5, 1, 1, 1, '88', '77', '66', 55.00, 44.00, 33.00, 22.00, 11.00);

-- --------------------------------------------------------

--
-- Table structure for table `po_head`
--

CREATE TABLE `po_head` (
  `po_id` int(5) NOT NULL,
  `po_no` varchar(200) NOT NULL,
  `supplier_id` int(3) NOT NULL,
  `po_date` date NOT NULL,
  `po_user` int(3) NOT NULL,
  `po_total` double(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `po_head`
--

INSERT INTO `po_head` (`po_id`, `po_no`, `supplier_id`, `po_date`, `po_user`, `po_total`) VALUES
(1, '111111', 2, '2019-04-11', 1, 0.00),
(2, '111111', 3, '2019-04-11', 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(3) NOT NULL,
  `supplier_code` varchar(200) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `supplier_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_code`, `supplier_name`, `supplier_address`) VALUES
(1, 'EX12623', 'EX12623', 'test address'),
(2, 'EX12523', 'EX12523', 'test address'),
(3, 'AQ328', 'AQ328', 'test address'),
(4, 'PCM-1214', 'MEN-SHUEN', 'test address');

-- --------------------------------------------------------

--
-- Table structure for table `used_detail`
--

CREATE TABLE `used_detail` (
  `used_detail_id` int(8) NOT NULL,
  `store_type` int(5) NOT NULL,
  `store_id` int(5) NOT NULL,
  `used_num` double(20,2) NOT NULL,
  `used_price` double(20,2) NOT NULL,
  `used_total` double(20,2) NOT NULL,
  `used_order_code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `used_head`
--

CREATE TABLE `used_head` (
  `used_id` int(5) NOT NULL,
  `used_code` varchar(200) NOT NULL,
  `used_order_code` varchar(200) NOT NULL,
  `used_date` datetime NOT NULL,
  `used_user` int(3) NOT NULL,
  `used_update` datetime NOT NULL,
  `used_update_user` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessory`
--
ALTER TABLE `accessory`
  ADD PRIMARY KEY (`accessory_id`);

--
-- Indexes for table `cat`
--
ALTER TABLE `cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `employee_position`
--
ALTER TABLE `employee_position`
  ADD PRIMARY KEY (`employee_position_id`);

--
-- Indexes for table `fabric`
--
ALTER TABLE `fabric`
  ADD PRIMARY KEY (`fabric_id`);

--
-- Indexes for table `po_detail`
--
ALTER TABLE `po_detail`
  ADD PRIMARY KEY (`po_detail_id`);

--
-- Indexes for table `po_head`
--
ALTER TABLE `po_head`
  ADD PRIMARY KEY (`po_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `used_detail`
--
ALTER TABLE `used_detail`
  ADD PRIMARY KEY (`used_detail_id`);

--
-- Indexes for table `used_head`
--
ALTER TABLE `used_head`
  ADD PRIMARY KEY (`used_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accessory`
--
ALTER TABLE `accessory`
  MODIFY `accessory_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat`
--
ALTER TABLE `cat`
  MODIFY `cat_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_position`
--
ALTER TABLE `employee_position`
  MODIFY `employee_position_id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `fabric`
--
ALTER TABLE `fabric`
  MODIFY `fabric_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `po_detail`
--
ALTER TABLE `po_detail`
  MODIFY `po_detail_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `po_head`
--
ALTER TABLE `po_head`
  MODIFY `po_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `used_detail`
--
ALTER TABLE `used_detail`
  MODIFY `used_detail_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `used_head`
--
ALTER TABLE `used_head`
  MODIFY `used_id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
