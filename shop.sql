-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2020 at 11:49 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(6, 'Computers', 'Computers Item', 0, 2, 0, 0, 0),
(7, 'Cell Phones', 'Cell Phone Items', 0, 3, 0, 0, 0),
(8, 'Clothing', 'Clothing And Fashion', 0, 4, 0, 0, 0),
(9, 'Tools', 'Home Tools', 0, 5, 0, 0, 0),
(10, 'Nokia', 'Nokia Phones', 7, 1, 1, 1, 0),
(11, 'Hand Made', 'Hand Made Items', 0, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(6, 'Very Nice', 1, '2020-11-17', 9, 6),
(7, 'Nice Item Thanks So Much', 1, '2020-11-09', 9, 6),
(8, 'This Very Good Phone\r\nThank You\r\n', 1, '2020-11-05', 10, 6),
(9, 'Good Phone Thank You', 1, '2020-11-05', 10, 6),
(10, 'thank you ', 1, '2020-11-05', 10, 6),
(17, 'Thank You', 1, '2020-11-05', 14, 1),
(18, 'Thank You', 1, '2020-11-05', 14, 1),
(19, 'This IS Very Good\r\n', 1, '2020-11-05', 10, 1),
(20, 'Thank You Very Match', 1, '2020-11-05', 9, 1),
(21, 'Thank You Very Match', 1, '2020-11-05', 9, 1),
(22, 'Thank You\r\n', 1, '2020-11-05', 10, 12),
(23, 'Thank You\r\n', 1, '2020-11-05', 10, 12),
(24, 'Very Bad', 1, '2020-11-05', 9, 12),
(25, 'Very Bad', 1, '2020-11-05', 9, 12);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(8, 'Speaker', 'Very Good Speaker', '10', '2020-11-01', 'Chine', '', '1', 0, 1, 6, 2, ''),
(9, 'Microphone', 'Very Good Microphone', '108', '2020-11-01', 'USA', '', '1', 0, 1, 6, 6, ''),
(10, 'iPhone 6 Plus', 'Apple iPhone 6 Plus', '800', '2020-11-01', 'USA', '', '1', 0, 1, 7, 6, ''),
(11, ' Magic Mouse', 'Apple  Magic Mouse', '50', '2020-11-01', 'USA', '', '1', 0, 1, 6, 9, ''),
(12, 'Network Cable', 'Cat 9 Network Cable', '100', '2020-11-01', 'USA', '', '1', 0, 1, 6, 6, ''),
(13, 'T shirt', 'The Good T shirt', '10', '2020-11-04', 'USA', '', '1', 0, 1, 8, 1, ''),
(14, 'Game', 'Very Good Game', '120', '2020-11-04', 'USA', '', '2', 0, 1, 6, 1, ''),
(15, 'Shirt', 'Very shairt', '20', '2020-11-05', 'USA', '', '1', 0, 1, 8, 12, ''),
(16, 'Test', 'Very Good Item', '1', '2020-11-05', 'USA', '', '2', 0, 1, 9, 12, ''),
(17, 'Android A20', 'This The Very Good Phone', '120', '2020-11-05', 'Chine', '', '1', 0, 1, 7, 1, 'Tags Test, Discount'),
(21, 'Wooden Game', 'A Good Wooden Game', '100', '2020-11-06', 'Jordan', '', '1', 0, 1, 11, 1, 'Elzero, Hand, Discount, Gurantee'),
(22, 'Diablo lll', 'Good Playstation 4 Games', '70', '2020-11-06', 'USA', '', '1', 0, 1, 6, 1, 'RPG, Online, Game'),
(23, 'Ys Oath in Felghana', 'Very Good Ps Game', '100', '2020-11-06', 'Japan', '', '1', 0, 1, 6, 1, 'Online, RPG, Game');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'User Approval',
  `Date` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `avatar`) VALUES
(1, 'Loai', '601f1889667efaebb33b8c12572835da3f027f78', 'Loai.mustafa76@gmail.com', 'Loai Mustafa', 1, 0, 1, '2020-10-24', ''),
(2, 'Maen', '601f1889667efaebb33b8c12572835da3f027f78', 'M@gmail.com', 'Maen Halah', 0, 0, 1, '2020-10-27', ''),
(6, 'Mustafa', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'M@gmail.com', 'Mustafa Ahmad', 0, 0, 1, '2020-10-27', ''),
(9, 'Mohammad', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'M@gmail.com', 'Mohammad Mustafa Ahmad', 0, 0, 1, '2020-10-27', ''),
(11, 'Salma', 'ccbe91b1f19bd31a1365363870c0eec2296a61c1', 'S@gmail.com', 'Salma Mustafa', 0, 0, 1, '2020-10-27', ''),
(12, 'Ahmad', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'A@gmail.com', 'Ahmad Mustafa', 0, 0, 1, '2020-10-27', ''),
(13, 'Hmaza', '601f1889667efaebb33b8c12572835da3f027f78', 'H@gmail.com', 'Hamza Helah', 0, 0, 1, '2020-10-27', ''),
(14, 'Sara', '4b6b90bfdc1519eeaae9f00243a2cfc820e30ca1', 'S@gmail.com', 'Sara Mustafa', 0, 0, 1, '2020-10-27', ''),
(15, 'Asel', '601f1889667efaebb33b8c12572835da3f027f78', 'A@gmail.com', '', 0, 0, 1, '2020-11-03', ''),
(16, 'Khalid', '601f1889667efaebb33b8c12572835da3f027f78', 'L@gmail.com', 'Loai Mustafa', 0, 0, 1, '2020-11-06', '38227_IMG_20191218_134933_380.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `Cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `Cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
