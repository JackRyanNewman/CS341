-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2023 at 08:17 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `_ymca`
--

-- --------------------------------------------------------

--
-- Table structure for table `adult`
--

CREATE TABLE `adult` (
  `peopleID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `userName` char(20) NOT NULL,
  `password` char(60) NOT NULL,
  `type` char(6) NOT NULL,
  `accountStatus` tinyint(1) NOT NULL,
  `firstName` char(20) NOT NULL,
  `lastName` char(30) NOT NULL,
  `DOB` date NOT NULL,
  `sex` char(1) NOT NULL,
  `address` char(70) NOT NULL,
  `ZIP` smallint(5) UNSIGNED ZEROFILL NOT NULL,
  `email` char(60) NOT NULL,
  `spouseID` mediumint(8) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adult`
--

INSERT INTO `adult` (`peopleID`, `userName`, `password`, `type`, `accountStatus`, `firstName`, `lastName`, `DOB`, `sex`, `address`, `ZIP`, `email`, `spouseID`) VALUES
(00000001, 'User', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'User', 1, 'Jackson', 'Mishuk', '2003-03-19', 'M', '11111 Adress row, Rochester MN', 45678, 'jman@gmail.com', 00000002),
(00000002, 'Member', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'Member', 1, 'Jack', 'Newman', '0420-11-16', 'M', '11111 Adress row, IN YOUR WALLS', 65535, 'jackman@gmail.com', 00000001),
(00000003, 'Staff', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'Staff', 1, 'Travis', 'Wiesner', '2000-06-09', 'M', '1800 steamer, stanley steemer makes your home cleaner', 09876, 'mail@mailmail.com', 00000004),
(00000004, 'Admin', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'Admin', 1, 'Abrar', 'Nizam', '2000-06-09', 'M', 'YOUR MOM', 09876, 'mail@mailmail.com', 00000003),
(00000005, 'JaneDoe', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'Member', 1, 'Jane', 'Doe', '2001-12-13', 'F', 'this is an address', 12345, 'jane@gmail.com', 00000000),
(00000006, 'LukeAnderson', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'User', 1, 'Luke', 'Anderson', '2000-06-15', 'M', 'this is an address', 12345, 'luke@gmail.com', 00000007),
(00000007, 'AiniAnderson', '$2y$10$K0CpL8JhcJeOYC1PR2XEd.lWAwc.w.uX.vJLcY8nMAngmiQBcZ/H.', 'Member', 1, 'Aini', 'Anderson', '2000-06-15', 'F', 'this is an address', 12345, 'aini@gmail.com', 00000006);

-- --------------------------------------------------------

--
-- Table structure for table `child`
--

CREATE TABLE `child` (
  `childID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `accountStatus` tinyint(4) NOT NULL,
  `firstName` char(20) NOT NULL,
  `lastName` char(20) NOT NULL,
  `parent1ID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `parent2ID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `DOB` date NOT NULL,
  `sex` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `programID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `programName` char(20) NOT NULL,
  `programStatus` tinyint(4) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`programID`, `programName`, `programStatus`, `description`) VALUES
(00000001, 'New Program', 1, 'This is a new program'),
(00000003, 'TestProg', 1, 'Testing...'),
(00000004, 'Session Tester 9000', 1, 'Tests sessions'),
(00000005, 'Swimming', 1, 'All swimming classes'),
(00000006, 'Rolling classes', 1, 'Rollin');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `roomID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `roomNum` smallint(6) UNSIGNED NOT NULL,
  `locationName` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`roomID`, `roomNum`, `locationName`) VALUES
(00000001, 100, 'Pool Area');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sessionID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `sessionName` char(20) NOT NULL,
  `sessionStatus` tinyint(4) NOT NULL,
  `roomID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `spotID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `programID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `currCapacity` tinyint(4) NOT NULL,
  `capacity` tinyint(4) NOT NULL,
  `description` mediumtext NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `feeMem` smallint(6) NOT NULL,
  `feeNonMem` smallint(6) NOT NULL,
  `preReq` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `minAge` tinyint(4) NOT NULL,
  `maxAge` tinyint(4) NOT NULL,
  `week` mediumint(7) UNSIGNED ZEROFILL NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sessionID`, `sessionName`, `sessionStatus`, `roomID`, `spotID`, `programID`, `currCapacity`, `capacity`, `description`, `startDate`, `endDate`, `feeMem`, `feeNonMem`, `preReq`, `minAge`, `maxAge`, `week`, `startTime`, `endTime`) VALUES
(00000032, 'Test', 0, 00000001, 00000001, 00000004, 0, 5, 'Tester...', '2023-04-23', '2023-04-29', 8, 16, 00000000, 6, 99, 1110011, '13:30:00', '15:00:00'),
(00000051, 'Pike', 2, 00000001, 00000001, 00000005, 2, 5, 'Pre', '2023-02-05', '2023-02-25', 9, 18, 00000000, 6, 99, 0100000, '11:15:00', '11:30:00'),
(00000052, 'Shark', 1, 00000001, 00000001, 00000005, 2, 8, 'Shark', '2023-03-12', '2023-05-20', 48, 96, 00000051, 6, 99, 0000001, '17:00:00', '17:40:00'),
(00000054, 'Shark', 1, 00000001, 00000002, 00000005, 1, 5, 'Shark', '2023-03-12', '2023-05-20', 96, 192, 00000051, 6, 99, 1010000, '17:00:00', '17:40:00'),
(00000055, 'Shark', 1, 00000001, 00000002, 00000005, 1, 8, 'Shark', '2023-04-16', '2023-05-20', 48, 96, 00000051, 6, 99, 0001000, '17:00:00', '17:40:00'),
(00000056, 'Shark', 1, 00000001, 00000002, 00000005, 1, 8, 'Shark', '2023-04-16', '2023-05-20', 65, 130, 00000051, 6, 99, 1010000, '18:00:00', '18:40:00'),
(00000057, 'Log Rolling', 1, 00000001, 00000001, 00000006, 1, 1, 'Rollin', '2023-04-16', '2023-05-20', 100, 200, 00000000, 6, 99, 0001000, '17:00:00', '17:40:00'),
(00000058, 'Shark', 1, 00000001, 00000001, 00000005, 1, 8, 'Shark', '2023-05-21', '2023-06-24', 48, 96, 00000051, 6, 99, 0000001, '17:00:00', '17:40:00'),
(00000059, 'Shark', 1, 00000001, 00000001, 00000005, 1, 8, 'Shark', '2023-05-21', '2023-06-24', 65, 130, 00000051, 6, 99, 1010000, '18:00:00', '18:40:00'),
(00000060, 'Log Rolling', 1, 00000001, 00000002, 00000006, 1, 1, 'Rollin', '2023-05-21', '2023-06-24', 100, 200, 00000000, 6, 99, 0000001, '17:00:00', '17:40:00'),
(00000061, 'Log Rolling', 1, 00000001, 00000002, 00000006, 2, 2, 'Rollin', '2023-05-21', '2023-06-24', 100, 200, 00000000, 6, 99, 1000000, '18:00:00', '18:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions_adult_junct`
--

CREATE TABLE `sessions_adult_junct` (
  `peopleID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `sessionID` mediumint(8) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions_adult_junct`
--

INSERT INTO `sessions_adult_junct` (`peopleID`, `sessionID`) VALUES
(00000005, 00000051),
(00000006, 00000051),
(00000005, 00000052),
(00000006, 00000052),
(00000006, 00000054),
(00000005, 00000055),
(00000006, 00000056),
(00000006, 00000057),
(00000005, 00000058),
(00000005, 00000061),
(00000006, 00000059),
(00000006, 00000060),
(00000007, 00000061);

-- --------------------------------------------------------

--
-- Table structure for table `sessions_child_junct`
--

CREATE TABLE `sessions_child_junct` (
  `childID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `sessionID` mediumint(8) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spots`
--

CREATE TABLE `spots` (
  `spotID` mediumint(8) UNSIGNED ZEROFILL NOT NULL,
  `spotName` char(10) NOT NULL,
  `roomID` mediumint(8) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spots`
--

INSERT INTO `spots` (`spotID`, `spotName`, `roomID`) VALUES
(00000001, 'Pool 1', 00000001),
(00000002, 'Pool 2', 00000001);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adult`
--
ALTER TABLE `adult`
  ADD PRIMARY KEY (`peopleID`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD KEY `userName_2` (`userName`,`password`,`accountStatus`,`firstName`,`lastName`),
  ADD KEY `spouseID` (`spouseID`);

--
-- Indexes for table `child`
--
ALTER TABLE `child`
  ADD PRIMARY KEY (`childID`),
  ADD KEY `parentID1` (`parent1ID`),
  ADD KEY `parentID2` (`parent2ID`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`programID`),
  ADD KEY `programStatus` (`programStatus`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`roomID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `programCascade` (`programID`),
  ADD KEY `rooms` (`roomID`),
  ADD KEY `spots` (`spotID`);

--
-- Indexes for table `sessions_adult_junct`
--
ALTER TABLE `sessions_adult_junct`
  ADD KEY `sessionID` (`sessionID`),
  ADD KEY `adultID` (`peopleID`);

--
-- Indexes for table `sessions_child_junct`
--
ALTER TABLE `sessions_child_junct`
  ADD KEY `childID` (`childID`),
  ADD KEY `session` (`sessionID`);

--
-- Indexes for table `spots`
--
ALTER TABLE `spots`
  ADD PRIMARY KEY (`spotID`),
  ADD KEY `roomIDConstraint` (`roomID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adult`
--
ALTER TABLE `adult`
  MODIFY `peopleID` mediumint(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `child`
--
ALTER TABLE `child`
  MODIFY `childID` mediumint(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `programID` mediumint(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `roomID` mediumint(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `sessionID` mediumint(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `spots`
--
ALTER TABLE `spots`
  MODIFY `spotID` mediumint(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `child`
--
ALTER TABLE `child`
  ADD CONSTRAINT `parentID1` FOREIGN KEY (`parent1ID`) REFERENCES `adult` (`peopleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `parentID2` FOREIGN KEY (`parent2ID`) REFERENCES `adult` (`peopleID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `programCascade` FOREIGN KEY (`programID`) REFERENCES `programs` (`programID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rooms` FOREIGN KEY (`roomID`) REFERENCES `rooms` (`roomID`),
  ADD CONSTRAINT `spots` FOREIGN KEY (`spotID`) REFERENCES `spots` (`spotID`);

--
-- Constraints for table `sessions_child_junct`
--
ALTER TABLE `sessions_child_junct`
  ADD CONSTRAINT `childID` FOREIGN KEY (`childID`) REFERENCES `child` (`childID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `session` FOREIGN KEY (`sessionID`) REFERENCES `sessions` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spots`
--
ALTER TABLE `spots`
  ADD CONSTRAINT `roomIDConstraint` FOREIGN KEY (`roomID`) REFERENCES `rooms` (`roomID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
