-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 17, 2019 at 03:52 AM
-- Server version: 10.2.25-MariaDB
-- PHP Version: 7.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+05:30";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u984042799_vsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `vs_admins`
--

CREATE TABLE `vs_admins` (
  `AID` int(10) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `Password` varchar(70) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Department` varchar(50) NOT NULL,
  `Mobile` varchar(14) NOT NULL,
  `isFaculty` tinyint(1) NOT NULL,
  `isCoordinator` tinyint(1) NOT NULL,
  `isCampaigner` tinyint(1) NOT NULL,
  `EventID` int(10) DEFAULT NULL,
  `TotalFeeCollected` int(7) NOT NULL,
  `RegisterTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_admins`
--

-- --------------------------------------------------------

--
-- Table structure for table `vs_events`
--

CREATE TABLE `vs_events` (
  `EVID` int(10) NOT NULL,
  `EVCode` varchar(30) NOT NULL,
  `EVName` varchar(30) NOT NULL,
  `EVDepartment` varchar(30) NOT NULL,
  `EVRounds` int(1) NOT NULL,
  `EVPrice` int(3) NOT NULL DEFAULT 50,
  `isSinglePrice` tinyint(1) NOT NULL DEFAULT 0,
  `isTeamEvent` tinyint(1) NOT NULL DEFAULT 0,
  `MinMembers` int(3) NOT NULL DEFAULT 1,
  `MaxMembers` int(3) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_events`
--

INSERT INTO `vs_events` (`EVID`, `EVCode`, `EVName`, `EVDepartment`, `EVRounds`, `EVPrice`, `isSinglePrice`, `isTeamEvent`, `MinMembers`, `MaxMembers`) VALUES
(1, 'COMP-PAPER', 'Paper Presentation', 'Computer', 1, 50, 0, 1, 1, 2),
(2, 'COMP-POST', 'Poster Presentation', 'Computer', 1, 50, 0, 1, 1, 3),
(3, 'COMP-PROG', 'Programming Date', 'Computer', 3, 50, 0, 0, 1, 1),
(4, 'COMP-PITCH', 'Pitchers', 'Computer', 2, 50, 0, 1, 2, 3),
(5, 'COMP-PRAGM', 'Pragmatist of Wall Street', 'Computer', 3, 50, 0, 1, 2, 3),
(6, 'COMP-PUZZ', 'Puzzle with Snake and Ladder', 'Computer', 1, 50, 0, 1, 2, 3),
(7, 'COMP-PLAC', 'Placement Drive', 'Computer', 3, 50, 0, 0, 1, 1),
(8, 'CIVIL-PAPER', 'Paper Presentation', 'Civil', 1, 50, 0, 1, 1, 2),
(9, 'CIVIL-POST', 'Poster Presentation', 'Civil', 1, 50, 0, 1, 1, 3),
(10, 'CIVIL-MODEL', 'Model Presentation', 'Civil', 1, 50, 0, 1, 1, 3),
(11, 'CIVIL-AH2O', 'Absolute H2O', 'Civil', 1, 50, 0, 1, 1, 3),
(12, 'CIVIL-EPLA', 'E-Placement', 'Civil', 3, 50, 0, 0, 1, 1),
(13, 'CIVIL-CHKR', 'Chakravyuh', 'Civil', 3, 50, 0, 1, 4, 4),
(14, 'ELEC-PAPER', 'Paper Presentation', 'Electrical', 1, 50, 0, 1, 1, 3),
(15, 'ELEC-POST', 'Poster Presentation', 'Electrical', 1, 50, 0, 1, 1, 3),
(16, 'ELEC-MODEL', 'Model Presentation', 'Electrical', 1, 50, 0, 1, 1, 4),
(17, 'ELEC-QUIZ', 'E-Quiz', 'Electrical', 4, 50, 0, 1, 2, 3),
(18, 'ELEC-EGOG', 'E-Google', 'Electrical', 3, 50, 0, 0, 1, 1),
(19, 'ELEC-AQUA', 'Aqua Robo', 'Electrical', 1, 50, 0, 1, 3, 5),
(20, 'ELEC-VIRT', 'Virtual Placement', 'Electrical', 3, 50, 0, 0, 1, 1),
(21, 'ELEC-BUZZ', 'Buzz Wire', 'Electrical', 2, 50, 0, 0, 1, 1),
(22, 'MECH-PAPER', 'Paper Presentation', 'Mechanical', 1, 50, 0, 1, 1, 2),
(23, 'MECH-POST', 'Poster Presentation', 'Mechanical', 1, 50, 0, 1, 1, 2),
(24, 'MECH-MODEL', 'Model Presentation', 'Mechanical', 1, 50, 0, 1, 1, 4),
(25, 'MECH-JUNKY', 'Junk Yard', 'Mechanical', 2, 200, 1, 1, 1, 4),
(26, 'MECH-LATH', 'Lathe War', 'Mechanical', 1, 50, 0, 1, 2, 2),
(27, 'CHEM-PAPER', 'Paper Presentation', 'Chemical', 1, 50, 0, 1, 1, 2),
(28, 'CHEM-POST', 'Poster Presentation', 'Chemical', 1, 50, 0, 1, 1, 2),
(29, 'CHEM-MODEL', 'Model Presentation', 'Chemical', 1, 50, 0, 1, 1, 4),
(30, 'CHEM-OQUIZ', 'Chem-O-Quiz', 'Chemical', 4, 50, 0, 1, 2, 3),
(31, 'CHEM-OLIVE', 'Chem-O-Live', 'Chemical', 2, 50, 0, 1, 1, 3),
(32, 'CHEM-CRYST', 'Chem-O-Cryst', 'Chemical', 1, 50, 0, 1, 1, 2),
(33, 'CHEM-CONTR', 'Contraption', 'Chemical', 1, 50, 0, 1, 1, 5),
(34, 'CHEM-HEPTA', 'Hepta League(Cricket)', 'Chemical', 1, 150, 1, 1, 6, 7),
(35, 'SCIH-MUZF', 'Musing Fizik', 'Science and Humanities', 1, 50, 0, 1, 1, 6),
(36, 'BVOC-BCODE', 'Blind Coding', 'BVOC. Software', 2, 50, 0, 0, 1, 1),
(37, 'BVOC-TECH', 'Techno Castle', 'BVOC. Software', 1, 50, 0, 1, 2, 2),
(38, 'BVOC-QUIZ', 'Social Media Quiz', 'BVOC. Software', 1, 50, 0, 1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vs_event_reg`
--

CREATE TABLE `vs_event_reg` (
  `ERID` int(10) NOT NULL,
  `PID` int(10) NOT NULL,
  `EVID` int(10) NOT NULL,
  `ERCode` varchar(30) NOT NULL,
  `FCode` varchar(11) NOT NULL,
  `RegAdmin` int(10) DEFAULT NULL,
  `isTeam` tinyint(1) NOT NULL,
  `isTeamLeader` tinyint(1) NOT NULL,
  `isPaid` tinyint(1) NOT NULL,
  `PayAdmin` int(10) DEFAULT NULL,
  `PayType` varchar(10) DEFAULT NULL,
  `PayTime` timestamp NULL DEFAULT NULL,
  `isAttended` tinyint(1) NOT NULL,
  `AttendAdmin` int(10) DEFAULT NULL,
  `AttendTime` timestamp NULL DEFAULT NULL,
  `EventRegTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vs_event_rounds`
--

CREATE TABLE `vs_event_rounds` (
  `RID` int(10) NOT NULL,
  `ERID` int(10) DEFAULT NULL,
  `WonRounds` int(1) NOT NULL,
  `isWinRound1` tinyint(1) NOT NULL,
  `isLockedRound1` tinyint(1) NOT NULL,
  `isWinRound2` tinyint(1) NOT NULL,
  `isLockedRound2` tinyint(1) NOT NULL,
  `isWinRound3` tinyint(1) NOT NULL,
  `isLockedRound3` tinyint(1) NOT NULL,
  `isWinRound4` tinyint(1) NOT NULL,
  `isLockedRound4` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_event_rounds`
--

-- --------------------------------------------------------

--
-- Table structure for table `vs_login`
--

CREATE TABLE `vs_login` (
  `LID` int(10) NOT NULL,
  `AID` int(10) NOT NULL,
  `ApiToken` varchar(20) NOT NULL,
  `LastLogin` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_login`
--

-- --------------------------------------------------------

--
-- Table structure for table `vs_participants`
--

CREATE TABLE `vs_participants` (
  `PID` int(10) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `College` varchar(70) NOT NULL,
  `Department` varchar(30) NOT NULL,
  `Semester` int(1) NOT NULL,
  `Mobile` varchar(14) NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `RegisterTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vs_session_log`
--

CREATE TABLE `vs_session_log` (
  `SID` int(10) NOT NULL,
  `AID` int(10) DEFAULT NULL,
  `ApiToken` varchar(20) DEFAULT NULL,
  `LogMessage` varchar(200) NOT NULL,
  `LogTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vs_admins`
--
ALTER TABLE `vs_admins`
  ADD PRIMARY KEY (`AID`,`EMail`),
  ADD KEY `EventAdminID` (`EventID`);

--
-- Indexes for table `vs_events`
--
ALTER TABLE `vs_events`
  ADD PRIMARY KEY (`EVID`);

--
-- Indexes for table `vs_event_reg`
--
ALTER TABLE `vs_event_reg`
  ADD PRIMARY KEY (`ERID`),
  ADD KEY `EventID` (`EVID`),
  ADD KEY `ParticipantID` (`PID`),
  ADD KEY `PaymentAdmiin` (`PayAdmin`),
  ADD KEY `AttendAdminID` (`AttendAdmin`),
  ADD KEY `RegisterAdminID` (`RegAdmin`);

--
-- Indexes for table `vs_event_rounds`
--
ALTER TABLE `vs_event_rounds`
  ADD PRIMARY KEY (`RID`),
  ADD KEY `EventRegID` (`ERID`);

--
-- Indexes for table `vs_login`
--
ALTER TABLE `vs_login`
  ADD PRIMARY KEY (`LID`),
  ADD KEY `AdminID` (`AID`);

--
-- Indexes for table `vs_participants`
--
ALTER TABLE `vs_participants`
  ADD PRIMARY KEY (`PID`,`EMail`);

--
-- Indexes for table `vs_session_log`
--
ALTER TABLE `vs_session_log`
  ADD PRIMARY KEY (`SID`),
  ADD KEY `Admin` (`AID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vs_admins`
--
ALTER TABLE `vs_admins`
  MODIFY `AID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_events`
--
ALTER TABLE `vs_events`
  MODIFY `EVID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `vs_event_reg`
--
ALTER TABLE `vs_event_reg`
  MODIFY `ERID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_event_rounds`
--
ALTER TABLE `vs_event_rounds`
  MODIFY `RID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_login`
--
ALTER TABLE `vs_login`
  MODIFY `LID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_participants`
--
ALTER TABLE `vs_participants`
  MODIFY `PID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vs_session_log`
--
ALTER TABLE `vs_session_log`
  MODIFY `SID` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vs_admins`
--
ALTER TABLE `vs_admins`
  ADD CONSTRAINT `EventAdminID` FOREIGN KEY (`EventID`) REFERENCES `vs_events` (`EVID`);

--
-- Constraints for table `vs_event_reg`
--
ALTER TABLE `vs_event_reg`
  ADD CONSTRAINT `AttendAdminID` FOREIGN KEY (`AttendAdmin`) REFERENCES `vs_admins` (`AID`),
  ADD CONSTRAINT `EventID` FOREIGN KEY (`EVID`) REFERENCES `vs_events` (`EVID`),
  ADD CONSTRAINT `ParticipantID` FOREIGN KEY (`PID`) REFERENCES `vs_participants` (`PID`),
  ADD CONSTRAINT `PaymentAdmiin` FOREIGN KEY (`PayAdmin`) REFERENCES `vs_admins` (`AID`),
  ADD CONSTRAINT `RegisterAdminID` FOREIGN KEY (`RegAdmin`) REFERENCES `vs_admins` (`AID`);

--
-- Constraints for table `vs_event_rounds`
--
ALTER TABLE `vs_event_rounds`
  ADD CONSTRAINT `EventRegID` FOREIGN KEY (`ERID`) REFERENCES `vs_event_reg` (`ERID`);

--
-- Constraints for table `vs_login`
--
ALTER TABLE `vs_login`
  ADD CONSTRAINT `AdminID` FOREIGN KEY (`AID`) REFERENCES `vs_admins` (`AID`);

--
-- Constraints for table `vs_session_log`
--
ALTER TABLE `vs_session_log`
  ADD CONSTRAINT `Admin` FOREIGN KEY (`AID`) REFERENCES `vs_admins` (`AID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
