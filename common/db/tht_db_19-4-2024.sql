-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2024 at 01:20 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tht_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `annualbudget`
--

CREATE TABLE `annualbudget` (
  `budgetID` int(11) NOT NULL,
  `projected_amount` int(11) NOT NULL,
  `yearID` int(11) NOT NULL,
  `authority` int(11) DEFAULT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `annualbudget`
--

INSERT INTO `annualbudget` (`budgetID`, `projected_amount`, `yearID`, `authority`, `status`) VALUES
(1, 0, 1, NULL, 'open'),
(27, 0, 19, NULL, 'open'),
(28, 0, 20, NULL, 'open');

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('ACCOUNTS', '6375', 1710945482),
('ACCOUNTS', '6377', 1710945934),
('ACCOUNTS ASSISTANT', '6379', 1711009831),
('ADMIN', '6373', NULL),
('ADMIN', '6376', 1710945696),
('ADMIN', '6378', 1710945998),
('ADMIN', '6379', 1711009831),
('CHAIRPERSON BR', '6371', 1710254066),
('CHAIRPERSON BR', '6381', 1711121185),
('CHAIRPERSON HQ', '6374', 1710938643),
('GENERAL SECRETARY HQ', '6368', 1655891156),
('GENERAL SECRETARY HQ', '6380', 1711454581),
('MEMBER', '6382', 1711549879),
('MEMBER', '6384', 1711653548),
('MEMBER', '6385', 1712586498),
('SECRETARY', '6380', 1711454581),
('TREASURER BR', '6370', 1656072632),
('TREASURER BR', '6372', 1710254746),
('TREASURER BR', '6382', 1711549879),
('TREASURER HQ', '6369', 1655893616),
('TREASURER HQ', '6383', 1712133628);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('ACCOUNTS', 1, NULL, NULL, NULL, NULL, NULL),
('ACCOUNTS ASSISTANT', 1, NULL, NULL, NULL, NULL, NULL),
('ADMIN', 1, 'The administrator of the system', NULL, NULL, NULL, NULL),
('CHAIRPERSON BR', 1, NULL, NULL, NULL, NULL, NULL),
('CHAIRPERSON HQ', 1, NULL, NULL, NULL, NULL, NULL),
('COORDINATOR BR', 1, NULL, NULL, NULL, NULL, NULL),
('COORDINATOR HQ', 1, NULL, NULL, NULL, NULL, NULL),
('DEPUTY COORDINATOR BR', 1, NULL, NULL, NULL, NULL, NULL),
('DEPUTY COORDINATOR HQ', 1, NULL, NULL, NULL, NULL, NULL),
('DEPUTY GENERAL SECRETARY HQ', 1, NULL, NULL, NULL, NULL, NULL),
('DEPUTY WOMEN\'S COORDINATOR BR', 1, NULL, NULL, NULL, NULL, NULL),
('DEPUTY WOMEN\'S COORDINATOR HQ', 1, NULL, NULL, NULL, NULL, NULL),
('GENERAL SECRETARY BR', 1, NULL, NULL, NULL, NULL, NULL),
('GENERAL SECRETARY HQ', 1, NULL, NULL, NULL, NULL, NULL),
('LABOUR OFFICER', 1, NULL, NULL, NULL, NULL, NULL),
('MEMBER', 1, NULL, NULL, NULL, NULL, NULL),
('MGT SECRETARY', 1, NULL, NULL, NULL, NULL, NULL),
('SECRETARY', 1, NULL, NULL, NULL, NULL, NULL),
('TREASURER BR', 1, NULL, NULL, NULL, NULL, NULL),
('TREASURER HQ', 1, NULL, NULL, NULL, NULL, NULL),
('WOMEN\'S COORDINATOR BR', 1, NULL, NULL, NULL, NULL, NULL),
('WOMEN\'S COORDINATOR HQ', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branchID` int(11) NOT NULL,
  `branchName` varchar(150) NOT NULL,
  `branch_short` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `telphone` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `website` varchar(30) DEFAULT NULL,
  `pobox` varchar(50) DEFAULT NULL,
  `level` varchar(10) NOT NULL DEFAULT 'BR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchID`, `branchName`, `branch_short`, `location`, `email`, `telphone`, `fax`, `website`, `pobox`, `level`) VALUES
(1, 'THTU UDOM', 'THTU-UDOM', 'Dodoma tanzania', NULL, NULL, NULL, NULL, NULL, 'BR'),
(3, 'THTU DIT', 'THTU_DIT', 'DAR ES SALAAM', '', '', '', '', '', 'BR'),
(4, 'THTU ARUSHA', 'THTU-ARU', 'ARUSHA', '', '', '', '', '', 'BR'),
(5, 'THTU Heardquarters', 'THTU_HQ', 'Dar es salaam', NULL, NULL, NULL, NULL, NULL, 'HQ');

-- --------------------------------------------------------

--
-- Table structure for table `branchotherincomes`
--

CREATE TABLE `branchotherincomes` (
  `incomeID` int(11) NOT NULL,
  `incomeType` varchar(200) NOT NULL,
  `amount` double NOT NULL,
  `month` varchar(10) NOT NULL,
  `budget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `branch_annual_budget`
--

CREATE TABLE `branch_annual_budget` (
  `bbID` int(11) NOT NULL,
  `projected_amount` int(11) NOT NULL,
  `budgetID` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `authority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branch_annual_budget`
--

INSERT INTO `branch_annual_budget` (`bbID`, `projected_amount`, `budgetID`, `branch`, `authority`) VALUES
(72, 0, 1, 5, NULL),
(105, 0, 27, 1, NULL),
(106, 0, 27, 3, NULL),
(107, 0, 27, 4, NULL),
(108, 0, 27, 5, NULL),
(109, 0, 28, 1, NULL),
(110, 0, 28, 3, NULL),
(111, 0, 28, 4, NULL),
(112, 0, 28, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_monthly_revenue`
--

CREATE TABLE `branch_monthly_revenue` (
  `revenueID` int(11) NOT NULL,
  `received_amount` float(14,3) DEFAULT NULL,
  `incomeID` int(11) NOT NULL,
  `month` smallint(6) NOT NULL,
  `branchbudget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branch_monthly_revenue`
--

INSERT INTO `branch_monthly_revenue` (`revenueID`, `received_amount`, `incomeID`, `month`, `branchbudget`) VALUES
(204, 500000.000, 114, 1, 105),
(205, 0.000, 114, 1, 106),
(206, 0.000, 114, 1, 107),
(207, 5000000.000, 114, 1, 108);

-- --------------------------------------------------------

--
-- Table structure for table `budgetprojections`
--

CREATE TABLE `budgetprojections` (
  `projID` int(11) NOT NULL,
  `budgetItem` varchar(100) NOT NULL,
  `projected_amount` int(11) NOT NULL,
  `branchbudget` int(11) NOT NULL,
  `status` varchar(15) DEFAULT 'projecting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `budgetyear`
--

CREATE TABLE `budgetyear` (
  `yearID` int(11) NOT NULL,
  `contributionfactor` int(11) NOT NULL DEFAULT 1,
  `startingyear` year(4) DEFAULT current_timestamp(),
  `endingyear` year(4) NOT NULL,
  `title` varchar(15) NOT NULL,
  `operationstatus` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `budgetyear`
--

INSERT INTO `budgetyear` (`yearID`, `contributionfactor`, `startingyear`, `endingyear`, `title`, `operationstatus`) VALUES
(1, 1, 2022, 2023, '2022 - 2023', 'closed'),
(19, 1, 2023, 2024, '2023 - 2024', 'closed'),
(20, 1, 2024, 2025, '2024 - 2025', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `customreferences`
--

CREATE TABLE `customreferences` (
  `crefID` int(11) NOT NULL,
  `refID` int(11) NOT NULL,
  `referenceName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customreferences`
--

INSERT INTO `customreferences` (`crefID`, `refID`, `referenceName`) VALUES
(5, 9, 'General Affairs'),
(6, 10, 'General Affairs 2'),
(7, 11, 'General Affairs 9'),
(8, 12, 'General Affairs 98'),
(9, 13, 'my affairs 7'),
(10, 14, 'Documents');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `fileID` int(11) NOT NULL,
  `fileName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`fileID`, `fileName`) VALUES
(19, 'storage/meetingRepos/62bc5cdb487a8.pdf'),
(20, 'storage/meetingRepos/62bc5d1b002d3.pdf'),
(21, '62bc5dc33518c.pdf'),
(22, '62bc5e9733b5d.pdf'),
(23, '62bc5ffca8abd.pdf'),
(24, '62bc67d4c9ea4.pdf'),
(25, '62bc67e907da0.pdf'),
(26, '62bc67f7a22b3.pdf'),
(27, '62bc683d2b6d1.pdf'),
(28, '62bc68d87bf98.pdf'),
(29, '62bc68f10d431.pdf'),
(30, '62bc691b1090c.pdf'),
(31, '62bc693f24b98.pdf'),
(32, '62bc6981e4d8b.pdf'),
(33, '62bc6a0020d87.pdf'),
(34, '62bc6ae20c8fc.pdf'),
(35, '62bc6b3a40ab5.pdf'),
(36, '62bc6e61d3d8b.pdf'),
(37, '62bc6e7d1ab92.pdf'),
(38, '62bd480b457c5.pdf'),
(39, '62bd5256a9eea.pdf'),
(40, '62bd61016529b.pdf'),
(41, '62bd6187c0d59.pdf'),
(42, '62bd61bae17e1.pdf'),
(43, '62bd624e5976c.pdf'),
(44, '62bd68e6e8af2.pdf'),
(45, '62bd6981923b6.pdf'),
(46, '62bd6a1e28892.pdf'),
(47, '62bd6ae7ec41b.pdf'),
(48, '62bd6b46eb957.pdf'),
(49, '62bd769c9b00a.pdf'),
(50, '62bd77db655d0.pdf'),
(51, '62bd78194ffcc.pdf'),
(52, '62bd7a80521db.pdf'),
(53, '62bd7afe2eba0.pdf'),
(54, '62bd7b19abd89.pdf'),
(55, '62bd7b8e822c2.pdf'),
(56, '62bd7c6ec0939.pdf'),
(57, '62bd7c983937b.pdf'),
(58, '62bd7dc3541eb.pdf'),
(59, '62bd7ddbeac02.pdf'),
(60, '62bd7def4c87f.pdf'),
(61, '62bd7f2e31eae.pdf'),
(62, '62bd7f73af950.pdf'),
(63, '62bd82d452c9b.pdf'),
(64, '62bd8333d497a.pdf'),
(65, '62bd838704071.pdf'),
(66, '62bd8395bab0a.pdf'),
(67, '62bd83c12f5f5.pdf'),
(68, '62bd842a43f04.pdf'),
(69, '62bd8462de22a.pdf'),
(70, '62bd84858ee2b.pdf'),
(71, '62bd84b4e9800.pdf'),
(72, '62bd8503080ab.pdf'),
(73, '62bd853b7ec1f.pdf'),
(74, '62bd858f887c9.pdf'),
(75, '62bd8626d7091.pdf'),
(76, '62bd8650b1030.pdf'),
(77, '62bd867c07b24.pdf'),
(78, '62bd8698018e9.pdf'),
(79, '62bd871453711.pdf'),
(80, '62bd8732443b4.pdf'),
(81, '62bd87b072a1d.pdf'),
(82, '62bd887ba9a33.pdf'),
(83, '62bd88c22dc8d.pdf'),
(84, '62bd88e82c106.pdf'),
(85, '62bd88e9427e3.pdf'),
(86, '62bd891f8316c.pdf'),
(87, '62bd892057704.pdf'),
(88, '62bd89e4deae2.pdf'),
(89, '62bd8aa2dc224.pdf'),
(90, '62bd8c4f43082.xlsx'),
(91, '62bdadc1c50f7.xlsx'),
(92, '62bdb8d300333.xlsx'),
(93, '62bdc3eb60003.xlsx'),
(94, '62bdc4454ac3b.pdf'),
(95, '62bdc95d4117f.pdf'),
(96, '62beb0f87ad2a.pdf'),
(97, '62bf1d4bd6b0a.xlsx'),
(98, '62bf288332e9d.xlsx'),
(99, '62bf3eb24d3c5.xlsx'),
(100, '62bf3eb29cc57.xlsx'),
(101, '62bf3efa3f4ce.pdf'),
(102, '62bf4e3aafe08.pdf'),
(103, '62bf50e7eaa32.xlsx'),
(104, '62bf63df02cd4.pdf'),
(105, '62c14fdb56fed.pdf'),
(106, '62c1515c93e71.pdf'),
(107, '62c153645e2b9.pdf'),
(108, '62c156a2b5ee4.pdf'),
(109, '62c156c7da954.xlsx'),
(110, '62c2932fc5120.pdf'),
(112, '62c2d1892aec5.xlsx'),
(113, '62c2d223bd002.xlsx'),
(114, '62c2d28fc612e.pdf'),
(115, '62c564369a095.xlsx'),
(116, '64f2feceb63d6.pdf'),
(117, '64f2ff6b38d32.pdf'),
(118, '64f2ffb85f540.pdf'),
(119, '64f3008cc5578.pdf'),
(120, '65fbf5428736e.pdf'),
(121, '6613ddee0aa15.png'),
(122, '6613e1df02112.png'),
(123, '6613e475df9c5.png'),
(124, '6614576ce4231.jpg'),
(126, '66153295bf422.png');

-- --------------------------------------------------------

--
-- Table structure for table `itemizedprojections`
--

CREATE TABLE `itemizedprojections` (
  `ipID` int(11) NOT NULL,
  `itemName` varchar(200) NOT NULL,
  `unitcost` int(11) NOT NULL,
  `numUnits` int(11) NOT NULL,
  `totalcost` int(11) NOT NULL,
  `projID` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `meetingID` int(11) NOT NULL,
  `meetingTitle` varchar(200) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `meetingTime` datetime NOT NULL,
  `venue` varchar(100) NOT NULL,
  `announcedBy` int(11) DEFAULT NULL,
  `dateAnnounced` datetime DEFAULT current_timestamp(),
  `announcedFrom` int(11) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`meetingID`, `meetingTitle`, `description`, `type`, `meetingTime`, `venue`, `announcedBy`, `dateAnnounced`, `announcedFrom`, `duration`, `status`) VALUES
(47, 'GENERAL ASSEMBLY  ', '', 1, '2024-04-10 15:14:00', 'Dodoma hotel', 6380, '2024-04-09 15:13:33', 5, 2, 'Upcoming');

-- --------------------------------------------------------

--
-- Table structure for table `meetingattendance`
--

CREATE TABLE `meetingattendance` (
  `attendanceID` int(11) NOT NULL,
  `meetingID` int(11) DEFAULT NULL,
  `memberID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meetingcancel`
--

CREATE TABLE `meetingcancel` (
  `cancelID` int(11) NOT NULL,
  `meetingID` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `memberID` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `fileID` int(11) DEFAULT NULL,
  `canceltime` datetime DEFAULT current_timestamp(),
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meetingdocuments`
--

CREATE TABLE `meetingdocuments` (
  `docID` int(11) NOT NULL,
  `meetingID` int(11) DEFAULT NULL,
  `fileID` int(11) DEFAULT NULL,
  `dateUploaded` datetime DEFAULT current_timestamp(),
  `title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meetingdocuments`
--

INSERT INTO `meetingdocuments` (`docID`, `meetingID`, `fileID`, `dateUploaded`, `title`) VALUES
(12, 47, 126, '2024-04-09 15:20:37', 'agenda');

-- --------------------------------------------------------

--
-- Table structure for table `meetinginvitees`
--

CREATE TABLE `meetinginvitees` (
  `MI_ID` int(11) NOT NULL,
  `meetingID` int(11) DEFAULT NULL,
  `memberID` int(11) DEFAULT NULL,
  `dateInvited` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meetingnames`
--

CREATE TABLE `meetingnames` (
  `typeID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meetingnames`
--

INSERT INTO `meetingnames` (`typeID`, `name`, `description`) VALUES
(1, 'GENERAL ASSEMBLY -HQ', NULL),
(2, 'GENERAL COUNCIL MEETING -HQ', NULL),
(3, 'CENTRAL COMMITTEE MEETING -HQ', NULL),
(4, 'WOMEN\'S GENERAL MEETING -HQ', NULL),
(5, 'WOMEN\'S CENTRAL COMMITTEE MEETING -HQ', NULL),
(6, 'MEMBERS\' GENERAL MEETING -BR', NULL),
(7, 'BRANCH GENERAL COUNCIL MEETING -BR', NULL),
(8, 'BRANCH WOMEN\'S COMMITTEE MEETING -BR', NULL),
(9, 'CUSTOM', 'This type of meeting is a non-standard meeting in which participants are all chosen ones');

-- --------------------------------------------------------

--
-- Table structure for table `meetingparticipants`
--

CREATE TABLE `meetingparticipants` (
  `participantID` int(11) NOT NULL,
  `typeID` int(11) DEFAULT NULL,
  `participant` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meetingparticipants`
--

INSERT INTO `meetingparticipants` (`participantID`, `typeID`, `participant`) VALUES
(5, 1, 'CHAIRPERSON BR'),
(3, 1, 'CHAIRPERSON HQ'),
(8, 1, 'COORDINATOR HQ'),
(9, 1, 'DEPUTY COORDINATOR HQ'),
(2, 1, 'DEPUTY GENERAL SECRETARY HQ'),
(6, 1, 'GENERAL SECRETARY BR'),
(1, 1, 'GENERAL SECRETARY HQ'),
(4, 1, 'TREASURER HQ'),
(7, 1, 'WOMEN\'S COORDINATOR BR'),
(16, 2, 'CHAIRPERSON BR'),
(10, 2, 'CHAIRPERSON HQ'),
(12, 2, 'DEPUTY GENERAL SECRETARY HQ'),
(14, 2, 'DEPUTY WOMEN\'S COORDINATOR HQ'),
(17, 2, 'GENERAL SECRETARY BR'),
(11, 2, 'GENERAL SECRETARY HQ'),
(15, 2, 'TREASURER HQ'),
(13, 2, 'WOMEN\'S COORDINATOR HQ'),
(18, 3, 'CHAIRPERSON HQ'),
(20, 3, 'DEPUTY GENERAL SECRETARY HQ'),
(22, 3, 'DEPUTY WOMEN\'S COORDINATOR HQ'),
(19, 3, 'GENERAL SECRETARY HQ'),
(23, 3, 'TREASURER HQ'),
(21, 3, 'WOMEN\'S COORDINATOR HQ'),
(27, 4, 'DEPUTY WOMEN\'S COORDINATOR BR'),
(25, 4, 'DEPUTY WOMEN\'S COORDINATOR HQ'),
(26, 4, 'WOMEN\'S COORDINATOR BR'),
(24, 4, 'WOMEN\'S COORDINATOR HQ'),
(29, 5, 'DEPUTY WOMEN\'S COORDINATOR HQ'),
(28, 5, 'WOMEN\'S COORDINATOR HQ'),
(31, 6, 'CHAIRPERSON BR'),
(35, 6, 'DEPUTY WOMEN\'S COORDINATOR BR'),
(32, 6, 'GENERAL SECRETARY BR'),
(30, 6, 'MEMBER'),
(33, 6, 'TREASURER BR'),
(34, 6, 'WOMEN\'S COORDINATOR BR'),
(36, 7, 'CHAIRPERSON BR'),
(40, 7, 'DEPUTY WOMEN\'S COORDINATOR BR'),
(37, 7, 'GENERAL SECRETARY BR'),
(38, 7, 'TREASURER BR'),
(39, 7, 'WOMEN\'S COORDINATOR BR'),
(42, 8, 'DEPUTY WOMEN\'S COORDINATOR BR'),
(41, 8, 'WOMEN\'S COORDINATOR BR');

-- --------------------------------------------------------

--
-- Table structure for table `meetingreferences`
--

CREATE TABLE `meetingreferences` (
  `mrefID` int(11) NOT NULL,
  `refID` int(11) NOT NULL,
  `referenceName` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_confirmations`
--

CREATE TABLE `meeting_confirmations` (
  `confID` int(11) NOT NULL,
  `meetingID` int(11) DEFAULT NULL,
  `memberID` int(11) DEFAULT NULL,
  `dateConfirmed` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meeting_confirmations`
--

INSERT INTO `meeting_confirmations` (`confID`, `meetingID`, `memberID`, `dateConfirmed`) VALUES
(18, 47, 6381, '2024-04-09 15:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `memberID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `IndividualNumber` varchar(150) NOT NULL,
  `fname` varchar(200) NOT NULL,
  `mname` varchar(200) DEFAULT NULL,
  `lname` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `branch` int(11) DEFAULT NULL,
  `gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`memberID`, `userID`, `IndividualNumber`, `fname`, `mname`, `lname`, `email`, `phone`, `branch`, `gender`) VALUES
(8, 6381, 'TH001', 'safina', '', 'mussa', 'safina@gmail.com', '0755189737', 4, 'F'),
(12, 6385, 'THTU-2024/THTU-ARU/14084498', 'khalid', '', 'hassan', 'thewinner016@gmail.com', '0755189736', 4, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `monthlyincome`
--

CREATE TABLE `monthlyincome` (
  `incomeID` int(11) NOT NULL,
  `budgetID` int(11) NOT NULL,
  `month` smallint(6) NOT NULL,
  `receivedAmount` int(11) NOT NULL,
  `datereceived` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `monthlyincome`
--

INSERT INTO `monthlyincome` (`incomeID`, `budgetID`, `month`, `receivedAmount`, `datereceived`) VALUES
(102, 1, 1, 10000, '2024-04-01 11:45:15'),
(103, 1, 12, 150000, '2024-04-01 11:46:08'),
(104, 1, 4, 4000, '2024-04-01 11:46:33'),
(105, 1, 2, 4000, '2024-04-01 12:02:01'),
(106, 1, 8, 1000000, '2024-04-02 17:56:42'),
(107, 1, 9, 150000, '2024-04-02 18:02:26'),
(108, 1, 3, 10000000, '2024-04-02 18:04:31'),
(114, 27, 1, 10000000, '2024-04-19 09:25:21');

-- --------------------------------------------------------

--
-- Table structure for table `monthlyspecialcontributions`
--

CREATE TABLE `monthlyspecialcontributions` (
  `contribID` int(11) NOT NULL,
  `contribType` varchar(100) NOT NULL,
  `IndividualAmount` double NOT NULL DEFAULT 0,
  `NoMembers` int(11) NOT NULL DEFAULT 0,
  `income` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `otherincomes`
--

CREATE TABLE `otherincomes` (
  `incomeID` int(11) NOT NULL,
  `incomeType` varchar(200) NOT NULL,
  `amount` double NOT NULL,
  `month` smallint(6) NOT NULL,
  `budget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otherincomes`
--

INSERT INTO `otherincomes` (`incomeID`, `incomeType`, `amount`, `month`, `budget`) VALUES
(29, 'Processing fee', 500000, 1, 1),
(30, 'Processing fee', 100000, 2, 1),
(31, 'Processing fee', 120000, 3, 1),
(32, 'Processing fee', 120000, 4, 1),
(33, 'Processing fee', 20000, 5, 1),
(34, 'Processing fee', 500000, 2, 1),
(35, 'Processing fee', 500000, 2, 1),
(36, 'Processing fee', 500000, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payabletransactions`
--

CREATE TABLE `payabletransactions` (
  `transID` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `Amount` int(11) NOT NULL,
  `dateapplied` date DEFAULT current_timestamp(),
  `authority` int(11) DEFAULT NULL,
  `reference` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receivabletransactions`
--

CREATE TABLE `receivabletransactions` (
  `transID` int(11) NOT NULL,
  `projID` int(11) NOT NULL,
  `receivedamount` int(11) NOT NULL,
  `datereceived` datetime DEFAULT current_timestamp(),
  `authority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `referencedocuments`
--

CREATE TABLE `referencedocuments` (
  `docID` int(6) UNSIGNED ZEROFILL NOT NULL,
  `docTitle` varchar(100) NOT NULL,
  `docType` varchar(20) DEFAULT NULL,
  `referencePrefix` int(11) NOT NULL,
  `fileID` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `meetingID` int(11) DEFAULT NULL,
  `offeredTo` int(11) DEFAULT NULL,
  `dateUploaded` datetime DEFAULT current_timestamp(),
  `reference` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `referencedocuments`
--

INSERT INTO `referencedocuments` (`docID`, `docTitle`, `docType`, `referencePrefix`, `fileID`, `year`, `meetingID`, `offeredTo`, `dateUploaded`, `reference`) VALUES
(000088, 'DOC 1', 'document', 9, 121, 2024, NULL, NULL, '2024-04-08 15:07:10', 'THTU/2024/0088.2024'),
(000089, 'DOC 2', 'document', 9, 122, 2024, NULL, NULL, '2024-04-08 15:23:59', 'THTU/2024/120089.2024'),
(000090, 'DOC 1', 'document', 10, 123, 2024, NULL, NULL, '2024-04-08 15:35:01', 'THTU/0090.2024');

-- --------------------------------------------------------

--
-- Table structure for table `referenceprefixes`
--

CREATE TABLE `referenceprefixes` (
  `prefID` int(11) NOT NULL,
  `prefix` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `branch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `referenceprefixes`
--

INSERT INTO `referenceprefixes` (`prefID`, `prefix`, `type`, `branch`) VALUES
(9, 'THTU/2024/12', 'custom', 5),
(10, 'THTU/', 'custom', 5),
(11, 'THTU/09', 'custom', 5),
(12, 'THTU/098', 'custom', 5),
(13, 'THT/00', 'custom', 5),
(14, 'THTU/2024', 'custom', 5);

-- --------------------------------------------------------

--
-- Table structure for table `repository`
--

CREATE TABLE `repository` (
  `docID` int(11) NOT NULL,
  `docTitle` varchar(150) NOT NULL,
  `docDescription` varchar(255) DEFAULT NULL,
  `file` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `uploadTime` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `repository`
--

INSERT INTO `repository` (`docID`, `docTitle`, `docDescription`, `file`, `userID`, `uploadTime`) VALUES
(10, 'my doc', 'adscasd', 120, 6380, '2024-03-21 11:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `takeover`
--

CREATE TABLE `takeover` (
  `tvID` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `takeover`
--

INSERT INTO `takeover` (`tvID`, `budget`, `amount`) VALUES
(1, 72, 100),
(21, 105, 0),
(22, 106, 0),
(23, 107, 0),
(24, 108, 13678000),
(25, 109, 500000),
(26, 110, 0),
(27, 111, 0),
(28, 112, 13678000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_audit_entry`
--

CREATE TABLE `tbl_audit_entry` (
  `audit_entry_id` int(11) NOT NULL,
  `audit_entry_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `audit_entry_model_name` varchar(100) NOT NULL,
  `audit_entry_operation` varchar(100) NOT NULL,
  `audit_entry_field_name` varchar(100) NOT NULL,
  `audit_entry_old_value` mediumtext DEFAULT NULL,
  `audit_entry_new_value` varchar(1000) DEFAULT NULL,
  `audit_entry_user_id` varchar(100) NOT NULL,
  `audit_entry_ip` varchar(100) NOT NULL,
  `audit_entry_affected_record_reference` mediumtext NOT NULL,
  `audit_entry_affected_record_reference_type` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_audit_entry`
--

INSERT INTO `tbl_audit_entry` (`audit_entry_id`, `audit_entry_timestamp`, `audit_entry_model_name`, `audit_entry_operation`, `audit_entry_field_name`, `audit_entry_old_value`, `audit_entry_new_value`, `audit_entry_user_id`, `audit_entry_ip`, `audit_entry_affected_record_reference`, `audit_entry_affected_record_reference_type`) VALUES
(210282, '0000-00-00 00:00:00', 'Member', 'DELETE', 'N/A', 'N/A', 'N/A', '6381', '::1', 'N/A', 'N/A'),
(210283, '0000-00-00 00:00:00', 'User', 'UPDATE', 'status', '10', '0', '6381', '::1', '6382', 'common\\models\\User'),
(210284, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711529512', '1711653817', '6381', '::1', '6382', 'common\\models\\User'),
(210285, '0000-00-00 00:00:00', 'Member', 'DELETE', 'N/A', 'N/A', 'N/A', '6381', '::1', 'N/A', 'N/A'),
(210286, '0000-00-00 00:00:00', 'User', 'UPDATE', 'status', '0', '9', '6373', '::1', '6382', 'common\\models\\User'),
(210287, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711653817', '1711653917', '6373', '::1', '6382', 'common\\models\\User'),
(210288, '0000-00-00 00:00:00', 'User', 'UPDATE', 'status', '9', '10', '6373', '::1', '6382', 'common\\models\\User'),
(210289, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711653917', '1711653925', '6373', '::1', '6382', 'common\\models\\User'),
(210290, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711651998', '1712132002', '6383', '::1', '6383', 'common\\models\\User'),
(210291, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-03-28 21:53:18', '2024-04-03 11:13:22', '6383', '::1', '6383', 'common\\models\\User'),
(210292, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711481068', '1712132566', '6373', '::1', '6373', 'common\\models\\User'),
(210293, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-03-26 22:24:28', '2024-04-03 11:22:46', '6373', '::1', '6373', 'common\\models\\User'),
(210294, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712132002', '1712133212', '6383', '::1', '6383', 'common\\models\\User'),
(210295, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-03 11:13:22', '2024-04-03 11:33:31', '6383', '::1', '6383', 'common\\models\\User'),
(210296, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712132566', '1712133634', '6373', '::1', '6373', 'common\\models\\User'),
(210297, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-03 11:22:46', '2024-04-03 11:40:34', '6373', '::1', '6373', 'common\\models\\User'),
(210298, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712133212', '1712134216', '6383', '::1', '6383', 'common\\models\\User'),
(210299, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-03 11:33:31', '2024-04-03 11:50:16', '6383', '::1', '6383', 'common\\models\\User'),
(210300, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712133634', '1712135145', '6373', '::1', '6373', 'common\\models\\User'),
(210301, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-03 11:40:34', '2024-04-03 12:05:45', '6373', '::1', '6373', 'common\\models\\User'),
(210302, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711652029', '1712394949', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210303, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-03-28 21:53:49', '2024-04-06 12:15:49', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210304, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712394949', '1712398154', '6380', '172.16.64.22', '6380', 'common\\models\\User'),
(210305, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 12:15:49', '2024-04-06 13:09:14', '6380', '172.16.64.22', '6380', 'common\\models\\User'),
(210306, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712398154', '1712408510', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210307, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 13:09:14', '2024-04-06 16:01:50', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210308, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712408510', '1712414427', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210309, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 16:01:50', '2024-04-06 17:40:27', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210310, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712414427', '1712414578', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210311, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 17:40:27', '2024-04-06 17:42:58', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210312, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712414578', '1712416779', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210313, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 17:42:58', '2024-04-06 18:19:39', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210314, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712416779', '1712416953', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210315, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 18:19:39', '2024-04-06 18:22:33', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210316, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712416953', '1712417087', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210317, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 18:22:33', '2024-04-06 18:24:47', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210318, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712417087', '1712417120', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210319, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 18:24:47', '2024-04-06 18:25:20', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210320, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712417120', '1712417242', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210321, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 18:25:20', '2024-04-06 18:27:22', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210322, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712417242', '1712417575', '6380', '172.16.64.22', '6380', 'common\\models\\User'),
(210323, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 18:27:22', '2024-04-06 18:32:55', '6380', '172.16.64.22', '6380', 'common\\models\\User'),
(210324, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712417575', '1712575725', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210325, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-06 18:32:55', '2024-04-08 14:28:45', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210326, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712134216', '1712576880', '6383', '172.16.64.24', '6383', 'common\\models\\User'),
(210327, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-03 11:50:16', '2024-04-08 14:48:00', '6383', '172.16.64.24', '6383', 'common\\models\\User'),
(210328, '0000-00-00 00:00:00', 'Member', 'UPDATE', 'phone', '0755189736', '0755189737', '6380', '127.0.0.1', '8', 'common\\models\\Member'),
(210329, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712575725', '1712586019', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210330, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-08 14:28:45', '2024-04-08 17:20:19', '6380', '127.0.0.1', '6380', 'common\\models\\User'),
(210331, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712135145', '1712586324', '6373', '127.0.0.1', '6373', 'common\\models\\User'),
(210332, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-03 12:05:45', '2024-04-08 17:25:24', '6373', '127.0.0.1', '6373', 'common\\models\\User'),
(210333, '0000-00-00 00:00:00', 'User', 'INSERT', 'id', 'NA', '6385', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210334, '0000-00-00 00:00:00', 'User', 'INSERT', 'username', 'NA', 'thewinner016@gmail.com', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210335, '0000-00-00 00:00:00', 'User', 'INSERT', 'auth_key', 'NA', 'ciBCOT5Zf6cnBIkLhA6fknfZJbZWNDtP', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210336, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_hash', 'NA', '$2y$13$P4KEQR2xudlcHIZQzwN1dewG10k8gw7gP8KAP28GTc.pJQomDp0.q', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210337, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_reset_token', 'NA', NULL, '6381', '172.16.64.24', 'N/A', 'N/A'),
(210338, '0000-00-00 00:00:00', 'User', 'INSERT', 'status', 'NA', '10', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210339, '0000-00-00 00:00:00', 'User', 'INSERT', 'created_at', 'NA', '1712586497', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210340, '0000-00-00 00:00:00', 'User', 'INSERT', 'updated_at', 'NA', '1712586497', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210341, '0000-00-00 00:00:00', 'User', 'INSERT', 'verification_token', 'NA', '303gCJo2roaXHdXmc4slHsysbuB6EOzX_1712586497', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210342, '0000-00-00 00:00:00', 'User', 'INSERT', 'last_login', 'NA', NULL, '6381', '172.16.64.24', 'N/A', 'N/A'),
(210343, '0000-00-00 00:00:00', 'Member', 'INSERT', 'memberID', 'NA', '12', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210344, '0000-00-00 00:00:00', 'Member', 'INSERT', 'userID', 'NA', '6385', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210345, '0000-00-00 00:00:00', 'Member', 'INSERT', 'IndividualNumber', 'NA', 'THTU-2024/THTU-ARU/14084498', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210346, '0000-00-00 00:00:00', 'Member', 'INSERT', 'fname', 'NA', 'khalid', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210347, '0000-00-00 00:00:00', 'Member', 'INSERT', 'mname', 'NA', '', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210348, '0000-00-00 00:00:00', 'Member', 'INSERT', 'lname', 'NA', 'hassan', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210349, '0000-00-00 00:00:00', 'Member', 'INSERT', 'email', 'NA', 'thewinner016@gmail.com', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210350, '0000-00-00 00:00:00', 'Member', 'INSERT', 'phone', 'NA', '0755189736', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210351, '0000-00-00 00:00:00', 'Member', 'INSERT', 'branch', 'NA', '4', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210352, '0000-00-00 00:00:00', 'Member', 'INSERT', 'gender', 'NA', 'M', '6381', '172.16.64.24', 'N/A', 'N/A'),
(210353, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1711293641', '1712590102', '6381', '172.16.64.24', '6381', 'common\\models\\User'),
(210354, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-03-22 18:43:58', '2024-04-08 18:28:22', '6381', '172.16.64.24', '6381', 'common\\models\\User'),
(210355, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docID', 'NA', '11', '6380', '::1', 'N/A', 'N/A'),
(210356, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docTitle', 'NA', 'document 3', '6380', '::1', 'N/A', 'N/A'),
(210357, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docDescription', 'NA', '', '6380', '::1', 'N/A', 'N/A'),
(210358, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'file', 'NA', '125', '6380', '::1', 'N/A', 'N/A'),
(210359, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'userID', 'NA', '6380', '6380', '::1', 'N/A', 'N/A'),
(210360, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'uploadTime', 'NA', '2024-04-08 23:56:01', '6380', '::1', 'N/A', 'N/A'),
(210361, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712586019', '1712610153', '6380', '::1', '6380', 'common\\models\\User'),
(210362, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-08 17:20:19', '2024-04-09 00:02:33', '6380', '::1', '6380', 'common\\models\\User'),
(210363, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712610153', '1712664714', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210364, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-09 00:02:33', '2024-04-09 15:11:53', '6380', '172.16.64.24', '6380', 'common\\models\\User'),
(210365, '0000-00-00 00:00:00', 'Repository', 'DELETE', 'N/A', 'N/A', 'N/A', '6380', '127.0.0.1', 'N/A', 'N/A'),
(210366, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712590102', '1713473447', '6381', '::1', '6381', 'common\\models\\User'),
(210367, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-08 18:28:22', '2024-04-18 23:50:47', '6381', '::1', '6381', 'common\\models\\User'),
(210368, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712586324', '1713473515', '6373', '::1', '6373', 'common\\models\\User'),
(210369, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-08 17:25:24', '2024-04-18 23:51:55', '6373', '::1', '6373', 'common\\models\\User'),
(210370, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1712664714', '1713475036', '6380', '::1', '6380', 'common\\models\\User'),
(210371, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-09 15:11:53', '2024-04-19 00:17:16', '6380', '::1', '6380', 'common\\models\\User'),
(210372, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1713475036', '1713504682', '6380', '::1', '6380', 'common\\models\\User'),
(210373, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-19 00:17:16', '2024-04-19 08:31:22', '6380', '::1', '6380', 'common\\models\\User'),
(210374, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1713504682', '1713507761', '6380', '::1', '6380', 'common\\models\\User'),
(210375, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-19 08:31:22', '2024-04-19 09:22:41', '6380', '::1', '6380', 'common\\models\\User'),
(210376, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1713507761', '1713507863', '6380', '::1', '6380', 'common\\models\\User'),
(210377, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-19 09:22:41', '2024-04-19 09:24:23', '6380', '::1', '6380', 'common\\models\\User'),
(210378, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1713507863', '1713507975', '6380', '::1', '6380', 'common\\models\\User'),
(210379, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2024-04-19 09:24:23', '2024-04-19 09:26:15', '6380', '::1', '6380', 'common\\models\\User');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '$2y$13$.kIiLCr/VFHDJULiTjtIk.7oKy4UN3dYlX2WKa1eJ3/mNpXhWVW96',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `status`, `created_at`, `updated_at`, `verification_token`, `last_login`) VALUES
(6373, 'THTU_ADMIN', 'AFujXxw5biTnwJcSNrjovcTixG-GGsB8', '$2y$13$Be/h1TGF42BuGX/T1Gx20OwdpP/S4sejcSPAcqL05Qoe4Q/8tOsmO', '', 10, 0, 1713473515, 'RK8L3JdasioISkk4Xe8veLgXDqIwFSUs_1710859274', '2024-04-18 23:51:55'),
(6380, 'hq@gmail.com', 'zQlgozCe0kvbebeAv16j4W1b7x55RQ5c', '$2y$13$uxRoW2KSSm9aDJ/hkgYXM.ZutCPudJCn/ecydzUpRcq/4hDql7hOG', NULL, 10, 1711010350, 1713507975, 'u52BtwjKdXLCFQQ1PfM9HISWAPbVNHr0_1711176383', '2024-04-19 09:26:15'),
(6381, 'day@gmail.com', '6kt3bPhPPoL9mg3wxDodZyNziSPtm_pZ', '$2y$13$x8e5pSAJRbTHVK5i6jEruOdA6t4jHK/5rlU5k0VA123kvBn01YDvq', NULL, 10, 1711119432, 1713473447, 'ChMEfn8yTzjRR3aGMMgalJvGS3mB924P_1711293641', '2024-04-18 23:50:47'),
(6382, 'kitale@gmail.com', 'YtZ2pqcXZMEUNOBxCAclfklPMLPO0wqE', '$2y$13$nPU7JKYuDJKzwINGZ2ljZus7L9.nEhHlXuxwfk1A8NPBYPUosaIIS', NULL, 10, 1711529512, 1711653925, '3MdzqjkDGSxNRU3A96qD6XCu24niYiwu_1711529512', NULL),
(6383, 'kazi@gmail.com', 'y_CkzeGFfnJTrPd5Y_J4Vh0Kr7HXTupZ', '$2y$13$6YaaL.TpMb8YBEROY0/6bujwlr2JC/Ovio/pRsJhONL/XORtbdzHa', NULL, 10, 1711550043, 1712576880, 'prdRshg1XktMI7ee4LIjMJJp7sn505Zv_1711550086', '2024-04-08 14:48:00'),
(6384, 'kitale1@gmail.com', 'Bth79p4gNKAGZ4yIMUCZMI4vOVTuWTO8', '$2y$13$yJ3LuGxeSOLZxe0Mf6/49erTVCZ4iQlh2DBP34gcMYe0Uto/Hvwb.', NULL, 0, 1711653548, 1711653650, 'uKLgET1oq8FWRBKWDhqYH7Ie2X9tLvdi_1711653548', NULL),
(6385, 'thewinner016@gmail.com', 'ciBCOT5Zf6cnBIkLhA6fknfZJbZWNDtP', '$2y$13$P4KEQR2xudlcHIZQzwN1dewG10k8gw7gP8KAP28GTc.pJQomDp0.q', NULL, 10, 1712586497, 1712586497, '303gCJo2roaXHdXmc4slHsysbuB6EOzX_1712586497', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annualbudget`
--
ALTER TABLE `annualbudget`
  ADD PRIMARY KEY (`budgetID`),
  ADD KEY `yearID` (`yearID`),
  ADD KEY `authority` (`authority`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branchID`),
  ADD UNIQUE KEY `branchName` (`branchName`),
  ADD UNIQUE KEY `branch_short` (`branch_short`);

--
-- Indexes for table `branchotherincomes`
--
ALTER TABLE `branchotherincomes`
  ADD PRIMARY KEY (`incomeID`),
  ADD KEY `budget` (`budget`);

--
-- Indexes for table `branch_annual_budget`
--
ALTER TABLE `branch_annual_budget`
  ADD PRIMARY KEY (`bbID`),
  ADD KEY `budgetID` (`budgetID`),
  ADD KEY `branch` (`branch`),
  ADD KEY `authority` (`authority`);

--
-- Indexes for table `branch_monthly_revenue`
--
ALTER TABLE `branch_monthly_revenue`
  ADD PRIMARY KEY (`revenueID`),
  ADD KEY `incomeID` (`incomeID`),
  ADD KEY `branchbudget` (`branchbudget`);

--
-- Indexes for table `budgetprojections`
--
ALTER TABLE `budgetprojections`
  ADD PRIMARY KEY (`projID`),
  ADD UNIQUE KEY `budgetItem` (`budgetItem`,`branchbudget`),
  ADD KEY `branchbudget` (`branchbudget`);

--
-- Indexes for table `budgetyear`
--
ALTER TABLE `budgetyear`
  ADD PRIMARY KEY (`yearID`);

--
-- Indexes for table `customreferences`
--
ALTER TABLE `customreferences`
  ADD PRIMARY KEY (`crefID`),
  ADD KEY `refID` (`refID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`fileID`);

--
-- Indexes for table `itemizedprojections`
--
ALTER TABLE `itemizedprojections`
  ADD PRIMARY KEY (`ipID`),
  ADD UNIQUE KEY `itemName` (`itemName`,`projID`),
  ADD KEY `projID` (`projID`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meetingID`),
  ADD KEY `announcerkey` (`announcedBy`),
  ADD KEY `type` (`type`),
  ADD KEY `announcedFrom` (`announcedFrom`);

--
-- Indexes for table `meetingattendance`
--
ALTER TABLE `meetingattendance`
  ADD PRIMARY KEY (`attendanceID`),
  ADD KEY `meetingID` (`meetingID`),
  ADD KEY `memberID` (`memberID`);

--
-- Indexes for table `meetingcancel`
--
ALTER TABLE `meetingcancel`
  ADD PRIMARY KEY (`cancelID`),
  ADD KEY `meetingID` (`meetingID`),
  ADD KEY `memberID` (`memberID`),
  ADD KEY `fileID` (`fileID`);

--
-- Indexes for table `meetingdocuments`
--
ALTER TABLE `meetingdocuments`
  ADD PRIMARY KEY (`docID`),
  ADD KEY `meetingID` (`meetingID`),
  ADD KEY `fileID` (`fileID`);

--
-- Indexes for table `meetinginvitees`
--
ALTER TABLE `meetinginvitees`
  ADD PRIMARY KEY (`MI_ID`),
  ADD KEY `meetingID` (`meetingID`),
  ADD KEY `memberID` (`memberID`);

--
-- Indexes for table `meetingnames`
--
ALTER TABLE `meetingnames`
  ADD PRIMARY KEY (`typeID`);

--
-- Indexes for table `meetingparticipants`
--
ALTER TABLE `meetingparticipants`
  ADD PRIMARY KEY (`participantID`),
  ADD UNIQUE KEY `typeID` (`typeID`,`participant`),
  ADD KEY `participant` (`participant`);

--
-- Indexes for table `meetingreferences`
--
ALTER TABLE `meetingreferences`
  ADD PRIMARY KEY (`mrefID`),
  ADD KEY `referenceName` (`referenceName`),
  ADD KEY `refID` (`refID`);

--
-- Indexes for table `meeting_confirmations`
--
ALTER TABLE `meeting_confirmations`
  ADD PRIMARY KEY (`confID`),
  ADD KEY `meetingID` (`meetingID`),
  ADD KEY `memberID` (`memberID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`memberID`),
  ADD UNIQUE KEY `IndividualNumber` (`IndividualNumber`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `brkey1` (`branch`),
  ADD KEY `userkey1` (`userID`);

--
-- Indexes for table `monthlyincome`
--
ALTER TABLE `monthlyincome`
  ADD PRIMARY KEY (`incomeID`),
  ADD UNIQUE KEY `budgetID` (`budgetID`,`month`);

--
-- Indexes for table `monthlyspecialcontributions`
--
ALTER TABLE `monthlyspecialcontributions`
  ADD PRIMARY KEY (`contribID`),
  ADD KEY `specialContribkey` (`income`);

--
-- Indexes for table `otherincomes`
--
ALTER TABLE `otherincomes`
  ADD PRIMARY KEY (`incomeID`),
  ADD KEY `budget` (`budget`);

--
-- Indexes for table `payabletransactions`
--
ALTER TABLE `payabletransactions`
  ADD PRIMARY KEY (`transID`),
  ADD KEY `item` (`item`),
  ADD KEY `authority` (`authority`);

--
-- Indexes for table `receivabletransactions`
--
ALTER TABLE `receivabletransactions`
  ADD PRIMARY KEY (`transID`),
  ADD KEY `authority` (`authority`),
  ADD KEY `projID` (`projID`);

--
-- Indexes for table `referencedocuments`
--
ALTER TABLE `referencedocuments`
  ADD PRIMARY KEY (`docID`),
  ADD KEY `referencePrefix` (`referencePrefix`),
  ADD KEY `fileID` (`fileID`),
  ADD KEY `meetingID` (`meetingID`),
  ADD KEY `offeredTo` (`offeredTo`);

--
-- Indexes for table `referenceprefixes`
--
ALTER TABLE `referenceprefixes`
  ADD PRIMARY KEY (`prefID`),
  ADD UNIQUE KEY `prefix` (`prefix`),
  ADD KEY `branch` (`branch`);

--
-- Indexes for table `repository`
--
ALTER TABLE `repository`
  ADD PRIMARY KEY (`docID`),
  ADD KEY `file` (`file`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `takeover`
--
ALTER TABLE `takeover`
  ADD PRIMARY KEY (`tvID`),
  ADD KEY `budget` (`budget`);

--
-- Indexes for table `tbl_audit_entry`
--
ALTER TABLE `tbl_audit_entry`
  ADD PRIMARY KEY (`audit_entry_id`),
  ADD KEY `audit_entry_operation` (`audit_entry_operation`),
  ADD KEY `audit_entry_user_id` (`audit_entry_user_id`),
  ADD KEY `audit_entry_ip` (`audit_entry_ip`),
  ADD KEY `audit_entry_model_name` (`audit_entry_model_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annualbudget`
--
ALTER TABLE `annualbudget`
  MODIFY `budgetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `branchotherincomes`
--
ALTER TABLE `branchotherincomes`
  MODIFY `incomeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `branch_annual_budget`
--
ALTER TABLE `branch_annual_budget`
  MODIFY `bbID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `branch_monthly_revenue`
--
ALTER TABLE `branch_monthly_revenue`
  MODIFY `revenueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `budgetprojections`
--
ALTER TABLE `budgetprojections`
  MODIFY `projID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `budgetyear`
--
ALTER TABLE `budgetyear`
  MODIFY `yearID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `customreferences`
--
ALTER TABLE `customreferences`
  MODIFY `crefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `itemizedprojections`
--
ALTER TABLE `itemizedprojections`
  MODIFY `ipID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meetingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `meetingattendance`
--
ALTER TABLE `meetingattendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `meetingcancel`
--
ALTER TABLE `meetingcancel`
  MODIFY `cancelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `meetingdocuments`
--
ALTER TABLE `meetingdocuments`
  MODIFY `docID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `meetinginvitees`
--
ALTER TABLE `meetinginvitees`
  MODIFY `MI_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `meetingnames`
--
ALTER TABLE `meetingnames`
  MODIFY `typeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `meetingparticipants`
--
ALTER TABLE `meetingparticipants`
  MODIFY `participantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `meetingreferences`
--
ALTER TABLE `meetingreferences`
  MODIFY `mrefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meeting_confirmations`
--
ALTER TABLE `meeting_confirmations`
  MODIFY `confID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `memberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `monthlyincome`
--
ALTER TABLE `monthlyincome`
  MODIFY `incomeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `monthlyspecialcontributions`
--
ALTER TABLE `monthlyspecialcontributions`
  MODIFY `contribID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `otherincomes`
--
ALTER TABLE `otherincomes`
  MODIFY `incomeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payabletransactions`
--
ALTER TABLE `payabletransactions`
  MODIFY `transID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `receivabletransactions`
--
ALTER TABLE `receivabletransactions`
  MODIFY `transID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `referencedocuments`
--
ALTER TABLE `referencedocuments`
  MODIFY `docID` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `referenceprefixes`
--
ALTER TABLE `referenceprefixes`
  MODIFY `prefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `repository`
--
ALTER TABLE `repository`
  MODIFY `docID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `takeover`
--
ALTER TABLE `takeover`
  MODIFY `tvID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_audit_entry`
--
ALTER TABLE `tbl_audit_entry`
  MODIFY `audit_entry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210380;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6386;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `annualbudget`
--
ALTER TABLE `annualbudget`
  ADD CONSTRAINT `annualbudget_ibfk_1` FOREIGN KEY (`yearID`) REFERENCES `budgetyear` (`yearID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `annualbudget_ibfk_2` FOREIGN KEY (`authority`) REFERENCES `member` (`memberID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branchotherincomes`
--
ALTER TABLE `branchotherincomes`
  ADD CONSTRAINT `botherincomes_ibfk_1` FOREIGN KEY (`budget`) REFERENCES `branch_annual_budget` (`bbID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `branch_annual_budget`
--
ALTER TABLE `branch_annual_budget`
  ADD CONSTRAINT `branch_annual_budget_ibfk_1` FOREIGN KEY (`budgetID`) REFERENCES `annualbudget` (`budgetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branch_annual_budget_ibfk_2` FOREIGN KEY (`branch`) REFERENCES `branch` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branch_annual_budget_ibfk_3` FOREIGN KEY (`authority`) REFERENCES `member` (`memberID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `branch_monthly_revenue`
--
ALTER TABLE `branch_monthly_revenue`
  ADD CONSTRAINT `branch_monthly_revenue_ibfk_1` FOREIGN KEY (`incomeID`) REFERENCES `monthlyincome` (`incomeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branch_monthly_revenue_ibfk_2` FOREIGN KEY (`branchbudget`) REFERENCES `branch_annual_budget` (`bbID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `budgetprojections`
--
ALTER TABLE `budgetprojections`
  ADD CONSTRAINT `budgetprojections_ibfk_1` FOREIGN KEY (`branchbudget`) REFERENCES `branch_annual_budget` (`bbID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customreferences`
--
ALTER TABLE `customreferences`
  ADD CONSTRAINT `customreferences_ibfk_1` FOREIGN KEY (`refID`) REFERENCES `referenceprefixes` (`prefID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `itemizedprojections`
--
ALTER TABLE `itemizedprojections`
  ADD CONSTRAINT `itemizedprojections_ibfk_1` FOREIGN KEY (`projID`) REFERENCES `budgetprojections` (`projID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`type`) REFERENCES `meetingnames` (`typeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_2` FOREIGN KEY (`announcedFrom`) REFERENCES `branch` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_3` FOREIGN KEY (`announcedBy`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meetingattendance`
--
ALTER TABLE `meetingattendance`
  ADD CONSTRAINT `meetingattendance_ibfk_1` FOREIGN KEY (`meetingID`) REFERENCES `meeting` (`meetingID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingattendance_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `member` (`memberID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meetingcancel`
--
ALTER TABLE `meetingcancel`
  ADD CONSTRAINT `meetingcancel_ibfk_1` FOREIGN KEY (`meetingID`) REFERENCES `meeting` (`meetingID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingcancel_ibfk_3` FOREIGN KEY (`fileID`) REFERENCES `files` (`fileID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingcancel_ibfk_4` FOREIGN KEY (`memberID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meetingdocuments`
--
ALTER TABLE `meetingdocuments`
  ADD CONSTRAINT `meetingdocuments_ibfk_1` FOREIGN KEY (`meetingID`) REFERENCES `meeting` (`meetingID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingdocuments_ibfk_2` FOREIGN KEY (`fileID`) REFERENCES `files` (`fileID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meetinginvitees`
--
ALTER TABLE `meetinginvitees`
  ADD CONSTRAINT `meetinginvitees_ibfk_1` FOREIGN KEY (`meetingID`) REFERENCES `meeting` (`meetingID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetinginvitees_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meetingparticipants`
--
ALTER TABLE `meetingparticipants`
  ADD CONSTRAINT `meetingparticipants_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `meetingnames` (`typeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingparticipants_ibfk_2` FOREIGN KEY (`participant`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meetingreferences`
--
ALTER TABLE `meetingreferences`
  ADD CONSTRAINT `meetingreferences_ibfk_1` FOREIGN KEY (`referenceName`) REFERENCES `meetingnames` (`typeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingreferences_ibfk_2` FOREIGN KEY (`refID`) REFERENCES `referenceprefixes` (`prefID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meeting_confirmations`
--
ALTER TABLE `meeting_confirmations`
  ADD CONSTRAINT `meeting_confirmations_ibfk_1` FOREIGN KEY (`meetingID`) REFERENCES `meeting` (`meetingID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_confirmations_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `brkey1` FOREIGN KEY (`branch`) REFERENCES `branch` (`branchID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `userkey1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monthlyincome`
--
ALTER TABLE `monthlyincome`
  ADD CONSTRAINT `monthlyincome_ibfk_1` FOREIGN KEY (`budgetID`) REFERENCES `annualbudget` (`budgetID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monthlyspecialcontributions`
--
ALTER TABLE `monthlyspecialcontributions`
  ADD CONSTRAINT `specialContribkey` FOREIGN KEY (`income`) REFERENCES `monthlyincome` (`incomeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `otherincomes`
--
ALTER TABLE `otherincomes`
  ADD CONSTRAINT `otherincomes_ibfk_1` FOREIGN KEY (`budget`) REFERENCES `annualbudget` (`budgetID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payabletransactions`
--
ALTER TABLE `payabletransactions`
  ADD CONSTRAINT `payabletransactions_ibfk_1` FOREIGN KEY (`item`) REFERENCES `itemizedprojections` (`ipID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payabletransactions_ibfk_2` FOREIGN KEY (`authority`) REFERENCES `member` (`memberID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receivabletransactions`
--
ALTER TABLE `receivabletransactions`
  ADD CONSTRAINT `receivabletransactions_ibfk_1` FOREIGN KEY (`authority`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `receivabletransactions_ibfk_2` FOREIGN KEY (`projID`) REFERENCES `budgetprojections` (`projID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `referencedocuments`
--
ALTER TABLE `referencedocuments`
  ADD CONSTRAINT `referencedocuments_ibfk_1` FOREIGN KEY (`referencePrefix`) REFERENCES `referenceprefixes` (`prefID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `referencedocuments_ibfk_2` FOREIGN KEY (`fileID`) REFERENCES `files` (`fileID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `referencedocuments_ibfk_3` FOREIGN KEY (`meetingID`) REFERENCES `meeting` (`meetingID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `referencedocuments_ibfk_4` FOREIGN KEY (`offeredTo`) REFERENCES `member` (`memberID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `referenceprefixes`
--
ALTER TABLE `referenceprefixes`
  ADD CONSTRAINT `referenceprefixes_ibfk_1` FOREIGN KEY (`branch`) REFERENCES `branch` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `repository`
--
ALTER TABLE `repository`
  ADD CONSTRAINT `repository_ibfk_1` FOREIGN KEY (`file`) REFERENCES `files` (`fileID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `repository_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `takeover`
--
ALTER TABLE `takeover`
  ADD CONSTRAINT `takeover_ibfk_1` FOREIGN KEY (`budget`) REFERENCES `branch_annual_budget` (`bbID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
