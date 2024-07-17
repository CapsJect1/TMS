-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 09:44 AM
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
-- Database: `tms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Name` varchar(250) DEFAULT NULL,
  `EmailId` varchar(250) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Name`, `EmailId`, `MobileNumber`, `Password`, `updationDate`) VALUES
(1, 'admin', 'Administrator', 'admin@gmail.com', 7894561239, 'f925916e2754e5e03f75dd58a5733251', '2024-01-10 11:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reference_num` varchar(100) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `ship` varchar(100) NOT NULL,
  `regular` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `senior_pwd` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `arriv_date` text NOT NULL,
  `arriv_time` text NOT NULL,
  `dept_date` text NOT NULL,
  `dept_time` text NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `payment` float DEFAULT NULL,
  `proof` longblob DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_tickets`
--

CREATE TABLE `saved_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `travel_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `BookingId` int(11) NOT NULL,
  `PackageId` int(11) DEFAULT NULL,
  `UserEmail` varchar(100) DEFAULT NULL,
  `FromDate` varchar(100) DEFAULT NULL,
  `ToDate` varchar(100) DEFAULT NULL,
  `Comment` mediumtext DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL,
  `CancelledBy` varchar(5) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblenquiry`
--

CREATE TABLE `tblenquiry` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `MobileNumber` char(10) DEFAULT NULL,
  `Subject` varchar(100) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblenquiry`
--

INSERT INTO `tblenquiry` (`id`, `FullName`, `EmailId`, `MobileNumber`, `Subject`, `Description`, `PostingDate`, `Status`) VALUES
(2, 'Kishan Twaerea', 'kishan@gmail.com', '6797947987', 'Enquiry', 'Any Offer for North Trip', '2024-01-18 06:31:38', 1),
(3, 'Jacaob', 'Jai@gmail.com', '1646689721', 'Any offer for North', 'Any Offer for north', '2024-01-19 06:32:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblissues`
--

CREATE TABLE `tblissues` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(100) DEFAULT NULL,
  `Issue` varchar(100) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `AdminremarkDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblissues`
--

INSERT INTO `tblissues` (`id`, `UserEmail`, `Issue`, `Description`, `PostingDate`, `AdminRemark`, `AdminremarkDate`) VALUES
(7, 'test@gmail.com', 'Refund', 'I want my refund', '2024-01-25 06:56:29', NULL, '2024-01-30 05:20:14'),
(10, 'test@gmail.com', 'Other', 'Test Sample', '2024-01-31 05:24:40', NULL, NULL),
(13, 'garima12@gmail.com', 'Booking Issues', 'I want some information ragrding booking', '2024-02-03 13:06:00', 'Infromation provided', '2024-02-03 13:06:26'),
(15, NULL, NULL, NULL, '2024-07-13 08:20:56', NULL, NULL),
(16, NULL, NULL, NULL, '2024-07-13 08:33:38', NULL, NULL),
(17, NULL, NULL, NULL, '2024-07-17 07:40:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT '',
  `detail` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `type`, `detail`) VALUES
(1, 'terms', '																				<p align=\"justify\"><span style=\"color: rgb(153, 0, 0); font-size: small; font-weight: 700;\">terms and condition page</span></p>\r\n										\r\n										'),
(2, 'privacy', '										<span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat</span>\r\n										'),
(3, 'aboutus', '										<div><span style=\"color: rgb(0, 0, 0); font-family: Georgia; font-size: 15px; text-align: justify; font-weight: bold;\">Welcome to Tourism Management System!!!</span></div><span style=\"font-family: &quot;courier new&quot;;\"><span style=\"color: rgb(0, 0, 0); font-size: 15px; text-align: justify;\">Since then, our courteous and committed team members have always ensured a pleasant and enjoyable tour for the clients. This arduous effort has enabled TMS to be recognized as a dependable Travel Solutions provider with three offices Delhi.</span><span style=\"color: rgb(80, 80, 80); font-size: 13px;\">&nbsp;We have got packages to suit the discerning traveler\'s budget and savor. Book your dream vacation online. Supported quality and proposals of our travel consultants, we have a tendency to welcome you to decide on from holidays packages and customize them according to your plan.</span></span>\r\n										'),
(11, 'contact', '																				<span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Address------J-890 Dwarka House New Delhi-110096</span>');

-- --------------------------------------------------------

--
-- Table structure for table `tbltourpackages`
--

CREATE TABLE `tbltourpackages` (
  `PackageId` int(11) NOT NULL,
  `PackageName` varchar(200) DEFAULT NULL,
  `PackageType` varchar(150) DEFAULT NULL,
  `PackageLocation` varchar(100) DEFAULT NULL,
  `PackagePrice` int(11) DEFAULT NULL,
  `PackageFetures` varchar(255) DEFAULT NULL,
  `PackageDetails` mediumtext DEFAULT NULL,
  `PackageImage` varchar(100) DEFAULT NULL,
  `Creationdate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltourpackages`
--

INSERT INTO `tbltourpackages` (`PackageId`, `PackageName`, `PackageType`, `PackageLocation`, `PackagePrice`, `PackageFetures`, `PackageDetails`, `PackageImage`, `Creationdate`, `UpdationDate`) VALUES
(2, 'Bhutan Holidays - Thimphu and Paro Special', 'Family Package', 'Bhutan', 3000, 'Free Wi-fi, Free Breakfast, Free Pickup and drop facility ', 'Visit to Tiger\'s Nest Monastery | Complimentary services of a Professional Guide', 'BHUTAN-THIMPU-PARO-PUNAKHA-TOUR-6N-7D.jpeg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(3, 'Soulmate Special Bali - 7 Nights', 'Couple Package', 'Indonesia(Bali)', 5000, 'Free Pickup and drop facility, Free Wi-fi , Free professional guide', 'Airport transfers by private car | Popular Sightseeing included | Suitable for Couple and budget travelers', '1583140977_5_11.jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(4, 'Kerala - A Lovers Paradise - Value Added', 'Family Package', 'Kerala', 1000, 'Free Wi-fi, Free pick up and drop facility,', 'Visit Matupetty Dam, tea plantation and a spice garden | View sunset in Kanyakumari | AC Car at disposal for 2hrs extra (once per city)', 'images (2).jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(5, 'Short Trip To Dubai', 'Family', 'Dubai', 4500, 'Free pick up and drop facility, Free Wi-fi, Free breakfast', 'A Holiday Package for the entire family.', 'unnamed.jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(6, 'Sikkim Delight with Darjeeling (customizable)', 'Group', 'Sikkim', 3500, 'Free Breakfast, Free Pick up drop facility', 'Changu Lake and New Baba Mandir excursion | View the sunrise from Tiger Hill | Get Blessed at the famous Rumtek Monastery', 'download (2).jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(7, '6 Days in Guwahati and Shillong With Cherrapunji Excursion', 'Family Package', 'Guwahati(Sikkim)', 4500, 'Breakfast,  Accommodation » Pick-up » Drop » Sightseeing', 'After arrival at Guwahati airport meet our representative & proceed for Shillong. Shillong is the capital and hill station of Meghalaya, also known as Abode of Cloud, one of the smallest states in India. En route visit Barapani lake. By afternoon reach at Shillong. Check in to the hotel. Evening is leisure. Spent time as you want. Visit Police bazar. Overnight stay at Shillong.', '95995.jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(8, 'Grand Week in North East - Lachung, Lachen and Gangtok', 'Domestic Packages', 'Sikkim', 4500, 'Free Breakfast, Free Wi-fi', 'Changu Lakeand New Baba Mandir excursion | Yumthang Valley tour | Gurudongmar Lake excursion | Night stay in Lachen', 'download (3).jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(9, 'Gangtok & Darjeeling Holiday (Without Flights)', 'Family Package', 'Sikkim', 1000, 'Free Wi-fi, Free pickup and drop facility', 'Ideal tour for Family | Sightseeing in Gangtok and Darjeeling | Full day excursion to idyllic Changu Lake | Visit to Ghoom Monastery', '1540382781_shutterstock_661867435.jpg.jpg', '2024-07-15 05:21:58', '2024-01-30 05:20:56'),
(11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-06 02:13:03', NULL),
(13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-06 02:15:54', NULL),
(15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-06 02:35:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `MobileNumber` char(10) DEFAULT NULL,
  `EmailId` varchar(70) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `UserType` varchar(50) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `fname`, `lname`, `MobileNumber`, `EmailId`, `Password`, `RegDate`, `UpdationDate`, `UserType`) VALUES
(1, 'Manju Srivatav', '', '', '4456464654', 'manju@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:40', 'customer'),
(2, 'Kishan', '', '', '9871987979', 'kishan@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:48', 'customer'),
(3, 'Salvi Chandra', '', '', '1398756416', 'salvi@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:48', 'customer'),
(4, 'Abir', '', '', '4789756456', 'abir@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:48', 'customer'),
(5, 'Test', '', '', '1987894654', 'test@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-01-16 06:33:20', '2024-01-31 02:00:48', 'customer'),
(9, 'Test Sample', '', '', '4654654564', 'testsample@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-31 06:32:51', NULL, 'customer'),
(10, 'Garima Singh', '', '', '1425362540', 'garima12@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-02-03 13:03:43', '2024-02-03 13:04:02', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `ticker`
--

CREATE TABLE `ticker` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `number_of_passengers` int(11) NOT NULL DEFAULT 1,
  `ticket_category_id` int(11) DEFAULT NULL,
  `date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticker`
--

INSERT INTO `ticker` (`id`, `name`, `status`, `number_of_passengers`, `ticket_category_id`, `date`) VALUES
(11, 'Test', 'Booked', 1, 13, '2024-07-06'),
(12, 'Test', 'Booked', 1, 11, '2024-07-06'),
(13, 'Test', 'Booked', 1, 11, '2024-07-06'),
(14, 'Test', 'Booked', 1, 11, '2024-07-06'),
(15, 'Test', 'Booked', 1, 10, '2024-07-06'),
(16, 'Test', 'Booked', 1, 11, '2024-07-06'),
(17, 'Test', 'Booked', 1, 11, '0000-00-00'),
(18, 'Test', 'Booked', 1, 11, NULL),
(19, 'Test', 'Booked', 1, 10, '2024-07-06'),
(20, 'Test', 'Booked', 1, 11, '2024-07-06'),
(21, 'Test', 'Booked', 1, 10, '2024-07-06');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_category`
--

CREATE TABLE `ticket_category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `rate` decimal(10,2) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_category`
--

INSERT INTO `ticket_category` (`id`, `name`, `description`, `rate`, `isActive`) VALUES
(10, 'Passenger rates', 'mga tahu\r\n', 100.00, 1),
(11, 'Rolling rates', 'na\r\n', 10.00, 1),
(12, 'child rate', 'sd', 100.00, 1),
(13, 'asa', 'sasa', 1221.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_tickets`
--
ALTER TABLE `saved_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`BookingId`);

--
-- Indexes for table `tblenquiry`
--
ALTER TABLE `tblenquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblissues`
--
ALTER TABLE `tblissues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltourpackages`
--
ALTER TABLE `tbltourpackages`
  ADD PRIMARY KEY (`PackageId`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `EmailId` (`EmailId`),
  ADD KEY `EmailId_2` (`EmailId`);

--
-- Indexes for table `ticker`
--
ALTER TABLE `ticker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_category_id` (`ticket_category_id`);

--
-- Indexes for table `ticket_category`
--
ALTER TABLE `ticket_category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_tickets`
--
ALTER TABLE `saved_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `BookingId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblenquiry`
--
ALTER TABLE `tblenquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblissues`
--
ALTER TABLE `tblissues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbltourpackages`
--
ALTER TABLE `tbltourpackages`
  MODIFY `PackageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ticker`
--
ALTER TABLE `ticker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ticket_category`
--
ALTER TABLE `ticket_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `saved_tickets`
--
ALTER TABLE `saved_tickets`
  ADD CONSTRAINT `fk_saved_tickets_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `ticker` (`id`),
  ADD CONSTRAINT `fk_saved_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`id`);

--
-- Constraints for table `ticker`
--
ALTER TABLE `ticker`
  ADD CONSTRAINT `ticker_ibfk_1` FOREIGN KEY (`ticket_category_id`) REFERENCES `ticket_category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
