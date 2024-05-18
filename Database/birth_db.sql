-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2024 at 08:16 AM
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
-- Database: `birth_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `birthrecords`
--

CREATE TABLE `birthrecords` (
  `BirthRecordID` int(11) NOT NULL,
  `ChildName` varchar(255) NOT NULL,
  `FatherName` varchar(255) NOT NULL,
  `MotherName` varchar(255) NOT NULL,
  `BirthDate` date NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `MotherNIC` varchar(15) NOT NULL,
  `FatherNIC` varchar(15) NOT NULL,
  `PaymentMethod` varchar(100) NOT NULL,
  `DistrictID` int(11) DEFAULT NULL,
  `TehsilID` int(11) DEFAULT NULL,
  `UnionCouncilID` int(11) DEFAULT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `birthrecords`
--

INSERT INTO `birthrecords` (`BirthRecordID`, `ChildName`, `FatherName`, `MotherName`, `BirthDate`, `Gender`, `MotherNIC`, `FatherNIC`, `PaymentMethod`, `DistrictID`, `TehsilID`, `UnionCouncilID`, `UserID`) VALUES
(2, 'Haider', 'Khadim Hussain', 'Ayesha', '2024-05-17', 'Male', '32402-8443661-2', '32402-8443661-3', 'Online', 1, 1, 2, 3),
(3, 'Haider', 'Khadim Hussain', 'Ayesha', '2024-05-17', 'Male', '32402-8443661-2', '32402-8443661-3', 'Online', 1, 1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `deathrecords`
--

CREATE TABLE `deathrecords` (
  `DeathRecordID` int(11) NOT NULL,
  `DeceasedName` varchar(100) NOT NULL,
  `FatherName` varchar(100) NOT NULL,
  `FatherNIC` varchar(15) NOT NULL,
  `DeathDate` date NOT NULL,
  `CauseOfDeath` text NOT NULL,
  `NICNumber` varchar(15) NOT NULL,
  `DistrictID` int(11) NOT NULL,
  `TehsilID` int(11) NOT NULL,
  `UnionCouncilID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deathrecords`
--

INSERT INTO `deathrecords` (`DeathRecordID`, `DeceasedName`, `FatherName`, `FatherNIC`, `DeathDate`, `CauseOfDeath`, `NICNumber`, `DistrictID`, `TehsilID`, `UnionCouncilID`, `UserID`) VALUES
(1, 'None', 'haider', '32402-8443661-3', '2024-05-18', 'None', '32402-8443661-3', 1, 1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `DistrictID` int(11) NOT NULL,
  `DistrictName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`DistrictID`, `DistrictName`) VALUES
(1, 'Rajanpur');

-- --------------------------------------------------------

--
-- Table structure for table `tehsils`
--

CREATE TABLE `tehsils` (
  `TehsilID` int(11) NOT NULL,
  `TehsilName` varchar(100) NOT NULL,
  `DistrictID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tehsils`
--

INSERT INTO `tehsils` (`TehsilID`, `TehsilName`, `DistrictID`) VALUES
(1, 'Jampur', 1);

-- --------------------------------------------------------

--
-- Table structure for table `unioncouncils`
--

CREATE TABLE `unioncouncils` (
  `UnionCouncilID` int(11) NOT NULL,
  `UnionCouncilName` varchar(100) NOT NULL,
  `TehsilID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unioncouncils`
--

INSERT INTO `unioncouncils` (`UnionCouncilID`, `UnionCouncilName`, `TehsilID`) VALUES
(2, 'Talai Wala', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `cnic` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `age`, `cnic`) VALUES
(3, 'nasir12', '$2y$10$ZGIxjuiP.nsAPlqFrAm8.uhpQFcEO1oP5.GpnpIeluch.IVAI7CQO', 'nasiryt.827@gmail.com', '31765268278', 23, '32402-8443638-1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `birthrecords`
--
ALTER TABLE `birthrecords`
  ADD PRIMARY KEY (`BirthRecordID`),
  ADD KEY `DistrictID` (`DistrictID`),
  ADD KEY `TehsilID` (`TehsilID`),
  ADD KEY `UnionCouncilID` (`UnionCouncilID`),
  ADD KEY `FK_UserID` (`UserID`);

--
-- Indexes for table `deathrecords`
--
ALTER TABLE `deathrecords`
  ADD PRIMARY KEY (`DeathRecordID`),
  ADD KEY `DistrictID` (`DistrictID`),
  ADD KEY `TehsilID` (`TehsilID`),
  ADD KEY `UnionCouncilID` (`UnionCouncilID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`DistrictID`);

--
-- Indexes for table `tehsils`
--
ALTER TABLE `tehsils`
  ADD PRIMARY KEY (`TehsilID`),
  ADD KEY `DistrictID` (`DistrictID`);

--
-- Indexes for table `unioncouncils`
--
ALTER TABLE `unioncouncils`
  ADD PRIMARY KEY (`UnionCouncilID`),
  ADD KEY `TehsilID` (`TehsilID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `birthrecords`
--
ALTER TABLE `birthrecords`
  MODIFY `BirthRecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deathrecords`
--
ALTER TABLE `deathrecords`
  MODIFY `DeathRecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `DistrictID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tehsils`
--
ALTER TABLE `tehsils`
  MODIFY `TehsilID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unioncouncils`
--
ALTER TABLE `unioncouncils`
  MODIFY `UnionCouncilID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `birthrecords`
--
ALTER TABLE `birthrecords`
  ADD CONSTRAINT `FK_UserID` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `birthrecords_ibfk_1` FOREIGN KEY (`DistrictID`) REFERENCES `districts` (`DistrictID`),
  ADD CONSTRAINT `birthrecords_ibfk_2` FOREIGN KEY (`TehsilID`) REFERENCES `tehsils` (`TehsilID`),
  ADD CONSTRAINT `birthrecords_ibfk_3` FOREIGN KEY (`UnionCouncilID`) REFERENCES `unioncouncils` (`UnionCouncilID`);

--
-- Constraints for table `deathrecords`
--
ALTER TABLE `deathrecords`
  ADD CONSTRAINT `deathrecords_ibfk_1` FOREIGN KEY (`DistrictID`) REFERENCES `districts` (`DistrictID`),
  ADD CONSTRAINT `deathrecords_ibfk_2` FOREIGN KEY (`TehsilID`) REFERENCES `tehsils` (`TehsilID`),
  ADD CONSTRAINT `deathrecords_ibfk_3` FOREIGN KEY (`UnionCouncilID`) REFERENCES `unioncouncils` (`UnionCouncilID`),
  ADD CONSTRAINT `deathrecords_ibfk_4` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `tehsils`
--
ALTER TABLE `tehsils`
  ADD CONSTRAINT `tehsils_ibfk_1` FOREIGN KEY (`DistrictID`) REFERENCES `districts` (`DistrictID`);

--
-- Constraints for table `unioncouncils`
--
ALTER TABLE `unioncouncils`
  ADD CONSTRAINT `unioncouncils_ibfk_1` FOREIGN KEY (`TehsilID`) REFERENCES `tehsils` (`TehsilID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
