-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2024 at 08:22 AM
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
(18, 0, 11, NULL, 'open');

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
('CHAIRPERSON HQ', '6374', 1710938643),
('GENERAL SECRETARY HQ', '6368', 1655891156),
('GENERAL SECRETARY HQ', '6380', 1711454581),
('SECRETARY', '6380', 1711454581),
('TREASURER BR', '6370', 1656072632),
('TREASURER BR', '6372', 1710254746),
('TREASURER HQ', '6369', 1655893616),
('TREASURER HQ', '6381', 1711121185);

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
(6, 100000, 1, 3, NULL),
(7, 20000, 1, 4, NULL),
(8, 100000, 1, 5, NULL),
(69, 0, 18, 1, NULL),
(70, 0, 18, 3, NULL),
(71, 0, 18, 4, NULL),
(72, 0, 18, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_monthly_revenue`
--

CREATE TABLE `branch_monthly_revenue` (
  `revenueID` int(11) NOT NULL,
  `received_amount` float(14,3) DEFAULT NULL,
  `incomeID` int(11) NOT NULL,
  `month` varchar(10) NOT NULL,
  `branchbudget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branch_monthly_revenue`
--

INSERT INTO `branch_monthly_revenue` (`revenueID`, `received_amount`, `incomeID`, `month`, `branchbudget`) VALUES
(126, 600.000, 91, 'January', 6),
(127, 150.000, 91, 'January', 7),
(128, 5000.000, 91, 'January', 8),
(129, 1650.000, 92, 'February', 69),
(130, 600.000, 92, 'February', 70),
(131, 100.000, 92, 'February', 71),
(132, 75000.000, 92, 'February', 72),
(133, 0.000, 93, 'December', 69),
(134, 0.000, 93, 'December', 70),
(135, 0.000, 93, 'December', 71),
(136, 2000.000, 93, 'December', 72),
(137, 0.000, 94, 'November', 69),
(138, 0.000, 94, 'November', 70),
(139, 0.000, 94, 'November', 71),
(140, 500.000, 94, 'November', 72),
(141, 0.000, 95, 'October', 69),
(142, 0.000, 95, 'October', 70),
(143, 0.000, 95, 'October', 71),
(144, 500.000, 95, 'October', 72);

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
(11, 1, 2023, 2024, '2023 - 2024', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `customreferences`
--

CREATE TABLE `customreferences` (
  `crefID` int(11) NOT NULL,
  `refID` int(11) NOT NULL,
  `referenceName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(120, '65fbf5428736e.pdf');

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

-- --------------------------------------------------------

--
-- Table structure for table `monthlyincome`
--

CREATE TABLE `monthlyincome` (
  `incomeID` int(11) NOT NULL,
  `budgetID` int(11) NOT NULL,
  `month` varchar(10) NOT NULL,
  `receivedAmount` int(11) NOT NULL,
  `datereceived` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `monthlyincome`
--

INSERT INTO `monthlyincome` (`incomeID`, `budgetID`, `month`, `receivedAmount`, `datereceived`) VALUES
(91, 1, 'January', 10000, '2024-03-26 22:08:30'),
(92, 18, 'February', 150000, '2024-03-26 22:22:19'),
(93, 18, 'December', 4000, '2024-03-26 22:30:11'),
(94, 18, 'November', 1000, '2024-03-26 22:31:33'),
(95, 18, 'October', 1000, '2024-03-26 22:33:50');

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
  `month` varchar(10) NOT NULL,
  `budget` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otherincomes`
--

INSERT INTO `otherincomes` (`incomeID`, `incomeType`, `amount`, `month`, `budget`) VALUES
(10, 'processing fee', 30000, 'January', 1),
(11, 'Processing fee', 500000, 'March', 18),
(12, 'donation', 20000, 'February', 18),
(13, 'processing fee', 30000, 'April', 18),
(14, 'processing fee', 30000, 'July', 18),
(15, 'tt', 20000, 'May', 18),
(16, 'processing fee', 1000, 'April', 18),
(17, 'processing fee', 30000, 'March', 18),
(18, 'processing fee', 30000, 'March', 18),
(19, 'processing fee', 9000, 'February', 18),
(20, 'tt', 1000, 'February', 18);

-- --------------------------------------------------------

--
-- Table structure for table `payabletransactions`
--

CREATE TABLE `payabletransactions` (
  `transID` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `Amount` int(11) NOT NULL,
  `dateapplied` datetime DEFAULT current_timestamp(),
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
(210150, '0000-00-00 00:00:00', 'User', 'INSERT', 'id', 'NA', '6368', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210151, '0000-00-00 00:00:00', 'User', 'INSERT', 'username', 'NA', 'thewinner016@gmail.com', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210152, '0000-00-00 00:00:00', 'User', 'INSERT', 'auth_key', 'NA', 'QG9B7uoQeSKpG9xE8Dv-5vwNT7yCRzAN', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210153, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_hash', 'NA', '$2y$13$NtLj8Qcp2bJhjkndkbYFqeh4aKiNIC8FmOUyqEN.BqKVfu8k3uI.y', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210154, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_reset_token', 'NA', NULL, 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210155, '0000-00-00 00:00:00', 'User', 'INSERT', 'status', 'NA', '10', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210156, '0000-00-00 00:00:00', 'User', 'INSERT', 'created_at', 'NA', '1655891155', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210157, '0000-00-00 00:00:00', 'User', 'INSERT', 'updated_at', 'NA', '1655891155', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210158, '0000-00-00 00:00:00', 'User', 'INSERT', 'verification_token', 'NA', 'MCcbP81uGxQJ7aSVoVGoBafq8rxskhe2_1655891155', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210159, '0000-00-00 00:00:00', 'User', 'INSERT', 'last_login', 'NA', NULL, 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210160, '0000-00-00 00:00:00', 'Member', 'INSERT', 'memberID', 'NA', '1', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210161, '0000-00-00 00:00:00', 'Member', 'INSERT', 'userID', 'NA', '6368', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210162, '0000-00-00 00:00:00', 'Member', 'INSERT', 'IndividualNumber', 'NA', 'THTU-2022/THTU-HQ/4780433', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210163, '0000-00-00 00:00:00', 'Member', 'INSERT', 'fname', 'NA', 'khalid', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210164, '0000-00-00 00:00:00', 'Member', 'INSERT', 'mname', 'NA', 'hassan', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210165, '0000-00-00 00:00:00', 'Member', 'INSERT', 'lname', 'NA', 'thewinner', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210166, '0000-00-00 00:00:00', 'Member', 'INSERT', 'email', 'NA', 'thewinner016@gmail.com', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210167, '0000-00-00 00:00:00', 'Member', 'INSERT', 'phone', 'NA', '0755189736', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210168, '0000-00-00 00:00:00', 'Member', 'INSERT', 'branch', 'NA', '2', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210169, '0000-00-00 00:00:00', 'Member', 'INSERT', 'gender', 'NA', 'M', 'NO_USER_ID', '::1', 'N/A', 'N/A'),
(210170, '0000-00-00 00:00:00', 'User', 'UPDATE', 'auth_key', 'QG9B7uoQeSKpG9xE8Dv-5vwNT7yCRzAN', '4FUQTaA1suNAC4yIg74QpDTePBj0i-di', '6368', '::1', '6368', 'common\\models\\User'),
(210171, '0000-00-00 00:00:00', 'User', 'UPDATE', 'password_hash', '$2y$13$NtLj8Qcp2bJhjkndkbYFqeh4aKiNIC8FmOUyqEN.BqKVfu8k3uI.y', '$2y$13$PBh9j8d6/q/.YJ5wqGj9aOyxBXHZduo6WFuNAI39AebaMfzu1GN7S', '6368', '::1', '6368', 'common\\models\\User'),
(210172, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1655891155', '1655891241', '6368', '::1', '6368', 'common\\models\\User'),
(210173, '0000-00-00 00:00:00', 'User', 'UPDATE', 'verification_token', 'MCcbP81uGxQJ7aSVoVGoBafq8rxskhe2_1655891155', '3wMvVt-pB9qSYoTmTndvLjpG0rQcWcqt_1655891241', '6368', '::1', '6368', 'common\\models\\User'),
(210174, '0000-00-00 00:00:00', 'User', 'INSERT', 'id', 'NA', '6369', '6368', '::1', 'N/A', 'N/A'),
(210175, '0000-00-00 00:00:00', 'User', 'INSERT', 'username', 'NA', 'khalid1@gmail.com', '6368', '::1', 'N/A', 'N/A'),
(210176, '0000-00-00 00:00:00', 'User', 'INSERT', 'auth_key', 'NA', 'hLxuYtNXzgu9bn3XecXVqeLJB8p0iuiB', '6368', '::1', 'N/A', 'N/A'),
(210177, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_hash', 'NA', '$2y$13$AkkZEVLd8oWZXfAGQYwDZerBhlcnXuUCcOmdBrD/YmkcgLyfe/GyS', '6368', '::1', 'N/A', 'N/A'),
(210178, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_reset_token', 'NA', NULL, '6368', '::1', 'N/A', 'N/A'),
(210179, '0000-00-00 00:00:00', 'User', 'INSERT', 'status', 'NA', '10', '6368', '::1', 'N/A', 'N/A'),
(210180, '0000-00-00 00:00:00', 'User', 'INSERT', 'created_at', 'NA', '1655893616', '6368', '::1', 'N/A', 'N/A'),
(210181, '0000-00-00 00:00:00', 'User', 'INSERT', 'updated_at', 'NA', '1655893616', '6368', '::1', 'N/A', 'N/A'),
(210182, '0000-00-00 00:00:00', 'User', 'INSERT', 'verification_token', 'NA', '8Y-kgFJ8M5AEvsfWyr9wnAAz0tHCaYRe_1655893616', '6368', '::1', 'N/A', 'N/A'),
(210183, '0000-00-00 00:00:00', 'User', 'INSERT', 'last_login', 'NA', NULL, '6368', '::1', 'N/A', 'N/A'),
(210184, '0000-00-00 00:00:00', 'Member', 'INSERT', 'memberID', 'NA', '2', '6368', '::1', 'N/A', 'N/A'),
(210185, '0000-00-00 00:00:00', 'Member', 'INSERT', 'userID', 'NA', '6369', '6368', '::1', 'N/A', 'N/A'),
(210186, '0000-00-00 00:00:00', 'Member', 'INSERT', 'IndividualNumber', 'NA', 'THTU-2022/THTU-UDOM/2072085', '6368', '::1', 'N/A', 'N/A'),
(210187, '0000-00-00 00:00:00', 'Member', 'INSERT', 'fname', 'NA', 'mussa', '6368', '::1', 'N/A', 'N/A'),
(210188, '0000-00-00 00:00:00', 'Member', 'INSERT', 'mname', 'NA', 'ayubu', '6368', '::1', 'N/A', 'N/A'),
(210189, '0000-00-00 00:00:00', 'Member', 'INSERT', 'lname', 'NA', 'john', '6368', '::1', 'N/A', 'N/A'),
(210190, '0000-00-00 00:00:00', 'Member', 'INSERT', 'email', 'NA', 'khalid1@gmail.com', '6368', '::1', 'N/A', 'N/A'),
(210191, '0000-00-00 00:00:00', 'Member', 'INSERT', 'phone', 'NA', '075518973611', '6368', '::1', 'N/A', 'N/A'),
(210192, '0000-00-00 00:00:00', 'Member', 'INSERT', 'branch', 'NA', '1', '6368', '::1', 'N/A', 'N/A'),
(210193, '0000-00-00 00:00:00', 'Member', 'INSERT', 'gender', 'NA', 'M', '6368', '::1', 'N/A', 'N/A'),
(210194, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docID', 'NA', '1', '6368', '::1', 'N/A', 'N/A'),
(210195, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docTitle', 'NA', 'meeting minutes', '6368', '::1', 'N/A', 'N/A'),
(210196, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docDescription', 'NA', '', '6368', '::1', 'N/A', 'N/A'),
(210197, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'file', 'NA', '1', '6368', '::1', 'N/A', 'N/A'),
(210198, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'userID', 'NA', '6368', '6368', '::1', 'N/A', 'N/A'),
(210199, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'uploadTime', 'NA', '2022-06-22 13:28:00', '6368', '::1', 'N/A', 'N/A'),
(210200, '0000-00-00 00:00:00', 'User', 'INSERT', 'id', 'NA', '6370', '6368', '::1', 'N/A', 'N/A'),
(210201, '0000-00-00 00:00:00', 'User', 'INSERT', 'username', 'NA', 'john@gmail.com', '6368', '::1', 'N/A', 'N/A'),
(210202, '0000-00-00 00:00:00', 'User', 'INSERT', 'auth_key', 'NA', 'LNVlnLPtgM0v2Vt_DuoytsRD2elnrF8N', '6368', '::1', 'N/A', 'N/A'),
(210203, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_hash', 'NA', '$2y$13$TbqBd80BXbEhRzzUM6yKDukVU9lXtVbJBPqjDBZvEyzMQ8o1aEDQq', '6368', '::1', 'N/A', 'N/A'),
(210204, '0000-00-00 00:00:00', 'User', 'INSERT', 'password_reset_token', 'NA', NULL, '6368', '::1', 'N/A', 'N/A'),
(210205, '0000-00-00 00:00:00', 'User', 'INSERT', 'status', 'NA', '10', '6368', '::1', 'N/A', 'N/A'),
(210206, '0000-00-00 00:00:00', 'User', 'INSERT', 'created_at', 'NA', '1656072632', '6368', '::1', 'N/A', 'N/A'),
(210207, '0000-00-00 00:00:00', 'User', 'INSERT', 'updated_at', 'NA', '1656072632', '6368', '::1', 'N/A', 'N/A'),
(210208, '0000-00-00 00:00:00', 'User', 'INSERT', 'verification_token', 'NA', '6Yqs8tobE4H0r2j0ZfKNUEvKABIuOeu0_1656072631', '6368', '::1', 'N/A', 'N/A'),
(210209, '0000-00-00 00:00:00', 'User', 'INSERT', 'last_login', 'NA', NULL, '6368', '::1', 'N/A', 'N/A'),
(210210, '0000-00-00 00:00:00', 'Member', 'INSERT', 'memberID', 'NA', '3', '6368', '::1', 'N/A', 'N/A'),
(210211, '0000-00-00 00:00:00', 'Member', 'INSERT', 'userID', 'NA', '6370', '6368', '::1', 'N/A', 'N/A'),
(210212, '0000-00-00 00:00:00', 'Member', 'INSERT', 'IndividualNumber', 'NA', 'THTU-2022/THTU-UDOM/10280205', '6368', '::1', 'N/A', 'N/A'),
(210213, '0000-00-00 00:00:00', 'Member', 'INSERT', 'fname', 'NA', 'mtumishi', '6368', '::1', 'N/A', 'N/A'),
(210214, '0000-00-00 00:00:00', 'Member', 'INSERT', 'mname', 'NA', '', '6368', '::1', 'N/A', 'N/A'),
(210215, '0000-00-00 00:00:00', 'Member', 'INSERT', 'lname', 'NA', 'john', '6368', '::1', 'N/A', 'N/A'),
(210216, '0000-00-00 00:00:00', 'Member', 'INSERT', 'email', 'NA', 'john@gmail.com', '6368', '::1', 'N/A', 'N/A'),
(210217, '0000-00-00 00:00:00', 'Member', 'INSERT', 'phone', 'NA', '0755189735', '6368', '::1', 'N/A', 'N/A'),
(210218, '0000-00-00 00:00:00', 'Member', 'INSERT', 'branch', 'NA', '1', '6368', '::1', 'N/A', 'N/A'),
(210219, '0000-00-00 00:00:00', 'Member', 'INSERT', 'gender', 'NA', 'M', '6368', '::1', 'N/A', 'N/A'),
(210220, '0000-00-00 00:00:00', 'User', 'UPDATE', 'auth_key', 'LNVlnLPtgM0v2Vt_DuoytsRD2elnrF8N', '0UEWZXrXNDkaPrZSKysMcezfWcv1oPq-', '6370', '::1', '6370', 'common\\models\\User'),
(210221, '0000-00-00 00:00:00', 'User', 'UPDATE', 'password_hash', '$2y$13$TbqBd80BXbEhRzzUM6yKDukVU9lXtVbJBPqjDBZvEyzMQ8o1aEDQq', '$2y$13$34T7sEXixl0p97Wbbz2cp.VIJ5TKthho1NteaYiIoHwpsSOEynWcO', '6370', '::1', '6370', 'common\\models\\User'),
(210222, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1656072632', '1656072732', '6370', '::1', '6370', 'common\\models\\User'),
(210223, '0000-00-00 00:00:00', 'User', 'UPDATE', 'verification_token', '6Yqs8tobE4H0r2j0ZfKNUEvKABIuOeu0_1656072631', 'IG2DG4wGeNC20XKI3Ic2fk4ld56P0OOu_1656072732', '6370', '::1', '6370', 'common\\models\\User'),
(210224, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docID', 'NA', '2', '6368', '::1', 'N/A', 'N/A'),
(210225, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docTitle', 'NA', 'second document 2', '6368', '::1', 'N/A', 'N/A'),
(210226, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docDescription', 'NA', '', '6368', '::1', 'N/A', 'N/A'),
(210227, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'file', 'NA', '2', '6368', '::1', 'N/A', 'N/A'),
(210228, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'userID', 'NA', '6368', '6368', '::1', 'N/A', 'N/A'),
(210229, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'uploadTime', 'NA', '2022-06-25 21:07:23', '6368', '::1', 'N/A', 'N/A'),
(210230, '0000-00-00 00:00:00', 'User', 'UPDATE', 'auth_key', 'hLxuYtNXzgu9bn3XecXVqeLJB8p0iuiB', 'kDKEU4Uk8GWYPpfSPmqedWxQoRtwjdRm', '6369', '::1', '6369', 'common\\models\\User'),
(210231, '0000-00-00 00:00:00', 'User', 'UPDATE', 'password_hash', '$2y$13$AkkZEVLd8oWZXfAGQYwDZerBhlcnXuUCcOmdBrD/YmkcgLyfe/GyS', '$2y$13$DvWLFcOwtOVFaDFhtAn3UOxca8eXynuQ3oE1U/MKMYVJYqL5Lh.D2', '6369', '::1', '6369', 'common\\models\\User'),
(210232, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1655893616', '1656599918', '6369', '::1', '6369', 'common\\models\\User'),
(210233, '0000-00-00 00:00:00', 'User', 'UPDATE', 'verification_token', '8Y-kgFJ8M5AEvsfWyr9wnAAz0tHCaYRe_1655893616', 'zQeKIugT9rD1deuZ5Oy4-BFMD2tHRyGu_1656599918', '6369', '::1', '6369', 'common\\models\\User'),
(210234, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1655891241', '1656839596', '6368', '::1', '6368', 'common\\models\\User'),
(210235, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', NULL, '2022-07-03 12:13:16', '6368', '::1', '6368', 'common\\models\\User'),
(210236, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docID', 'NA', '3', '6368', '::1', 'N/A', 'N/A'),
(210237, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docTitle', 'NA', 'Document for all staff', '6368', '::1', 'N/A', 'N/A'),
(210238, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docDescription', 'NA', 'this document should be used by all staff for all meetings', '6368', '::1', 'N/A', 'N/A'),
(210239, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'file', 'NA', '111', '6368', '::1', 'N/A', 'N/A'),
(210240, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'userID', 'NA', '6368', '6368', '::1', 'N/A', 'N/A'),
(210241, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'uploadTime', 'NA', '2022-07-04 12:06:39', '6368', '::1', 'N/A', 'N/A'),
(210242, '0000-00-00 00:00:00', 'Repository', 'UPDATE', 'docDescription', 'this document should be used by all staff for all meetings', '', '6368', '::1', '3', 'common\\models\\Repository'),
(210243, '0000-00-00 00:00:00', 'Repository', 'DELETE', 'N/A', 'N/A', 'N/A', '6368', '::1', 'N/A', 'N/A'),
(210244, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docID', 'NA', '4', '6368', '::1', 'N/A', 'N/A'),
(210245, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docTitle', 'NA', 'first document', '6368', '::1', 'N/A', 'N/A'),
(210246, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docDescription', 'NA', '', '6368', '::1', 'N/A', 'N/A'),
(210247, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'file', 'NA', '115', '6368', '::1', 'N/A', 'N/A'),
(210248, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'userID', 'NA', '6368', '6368', '::1', 'N/A', 'N/A'),
(210249, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'uploadTime', 'NA', '2022-07-06 13:30:14', '6368', '::1', 'N/A', 'N/A'),
(210250, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1656839596', '1657177095', '6368', '::1', '6368', 'common\\models\\User'),
(210251, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2022-07-03 12:13:16', '2022-07-07 09:58:15', '6368', '::1', '6368', 'common\\models\\User'),
(210252, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1657177095', '1657207993', '6368', '::1', '6368', 'common\\models\\User'),
(210253, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2022-07-07 09:58:15', '2022-07-07 18:33:13', '6368', '::1', '6368', 'common\\models\\User'),
(210254, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1656599918', '1657349841', '6369', '::1', '6369', 'common\\models\\User'),
(210255, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', NULL, '2022-07-09 09:57:21', '6369', '::1', '6369', 'common\\models\\User'),
(210256, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1656072732', '1657639209', '6370', '::1', '6370', 'common\\models\\User'),
(210257, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', NULL, '2022-07-12 18:20:09', '6370', '::1', '6370', 'common\\models\\User'),
(210258, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1657639209', '1657645378', '6370', '::1', '6370', 'common\\models\\User'),
(210259, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2022-07-12 18:20:09', '2022-07-12 20:02:58', '6370', '::1', '6370', 'common\\models\\User'),
(210260, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1657349841', '1657645554', '6369', '::1', '6369', 'common\\models\\User'),
(210261, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2022-07-09 09:57:21', '2022-07-12 20:05:54', '6369', '::1', '6369', 'common\\models\\User'),
(210262, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1657645378', '1657645584', '6370', '::1', '6370', 'common\\models\\User'),
(210263, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2022-07-12 20:02:58', '2022-07-12 20:06:24', '6370', '::1', '6370', 'common\\models\\User'),
(210264, '0000-00-00 00:00:00', 'User', 'UPDATE', 'updated_at', '1657207993', '1657720381', '6368', '::1', '6368', 'common\\models\\User'),
(210265, '0000-00-00 00:00:00', 'User', 'UPDATE', 'last_login', '2022-07-07 18:33:13', '2022-07-13 16:53:01', '6368', '::1', '6368', 'common\\models\\User'),
(210266, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docID', 'NA', '10', '6380', '::1', 'N/A', 'N/A'),
(210267, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docTitle', 'NA', 'my doc', '6380', '::1', 'N/A', 'N/A'),
(210268, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'docDescription', 'NA', 'adscasd', '6380', '::1', 'N/A', 'N/A'),
(210269, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'file', 'NA', '120', '6380', '::1', 'N/A', 'N/A'),
(210270, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'userID', 'NA', '6380', '6380', '::1', 'N/A', 'N/A'),
(210271, '0000-00-00 00:00:00', 'Repository', 'INSERT', 'uploadTime', 'NA', '2024-03-21 11:52:18', '6380', '::1', 'N/A', 'N/A');

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
(6373, 'THTU_ADMIN', 'AFujXxw5biTnwJcSNrjovcTixG-GGsB8', '$2y$13$Be/h1TGF42BuGX/T1Gx20OwdpP/S4sejcSPAcqL05Qoe4Q/8tOsmO', '', 10, 0, 1711481068, 'RK8L3JdasioISkk4Xe8veLgXDqIwFSUs_1710859274', '2024-03-26 22:24:28'),
(6380, 'hq@gmail.com', 'zQlgozCe0kvbebeAv16j4W1b7x55RQ5c', '$2y$13$uxRoW2KSSm9aDJ/hkgYXM.ZutCPudJCn/ecydzUpRcq/4hDql7hOG', NULL, 10, 1711010350, 1711483998, 'u52BtwjKdXLCFQQ1PfM9HISWAPbVNHr0_1711176383', '2024-03-26 23:13:18'),
(6381, 'day@gmail.com', '6kt3bPhPPoL9mg3wxDodZyNziSPtm_pZ', '$2y$13$x8e5pSAJRbTHVK5i6jEruOdA6t4jHK/5rlU5k0VA123kvBn01YDvq', NULL, 10, 1711119432, 1711293641, 'ChMEfn8yTzjRR3aGMMgalJvGS3mB924P_1711293641', '2024-03-22 18:43:58');

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
  MODIFY `budgetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `branchotherincomes`
--
ALTER TABLE `branchotherincomes`
  MODIFY `incomeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branch_annual_budget`
--
ALTER TABLE `branch_annual_budget`
  MODIFY `bbID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `branch_monthly_revenue`
--
ALTER TABLE `branch_monthly_revenue`
  MODIFY `revenueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `budgetprojections`
--
ALTER TABLE `budgetprojections`
  MODIFY `projID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `budgetyear`
--
ALTER TABLE `budgetyear`
  MODIFY `yearID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customreferences`
--
ALTER TABLE `customreferences`
  MODIFY `crefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `itemizedprojections`
--
ALTER TABLE `itemizedprojections`
  MODIFY `ipID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meetingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
  MODIFY `docID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `meetinginvitees`
--
ALTER TABLE `meetinginvitees`
  MODIFY `MI_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `confID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `memberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `monthlyincome`
--
ALTER TABLE `monthlyincome`
  MODIFY `incomeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `monthlyspecialcontributions`
--
ALTER TABLE `monthlyspecialcontributions`
  MODIFY `contribID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `otherincomes`
--
ALTER TABLE `otherincomes`
  MODIFY `incomeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payabletransactions`
--
ALTER TABLE `payabletransactions`
  MODIFY `transID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `receivabletransactions`
--
ALTER TABLE `receivabletransactions`
  MODIFY `transID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `referencedocuments`
--
ALTER TABLE `referencedocuments`
  MODIFY `docID` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `referenceprefixes`
--
ALTER TABLE `referenceprefixes`
  MODIFY `prefID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `repository`
--
ALTER TABLE `repository`
  MODIFY `docID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_audit_entry`
--
ALTER TABLE `tbl_audit_entry`
  MODIFY `audit_entry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210272;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6382;

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
  ADD CONSTRAINT `announcerkey` FOREIGN KEY (`announcedBy`) REFERENCES `member` (`memberID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`type`) REFERENCES `meetingnames` (`typeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_2` FOREIGN KEY (`announcedFrom`) REFERENCES `branch` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `meetingcancel_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `member` (`memberID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meetingcancel_ibfk_3` FOREIGN KEY (`fileID`) REFERENCES `files` (`fileID`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `meetinginvitees_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `member` (`memberID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `meeting_confirmations_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `member` (`memberID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
