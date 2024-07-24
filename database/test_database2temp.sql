-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 04:19 PM
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
-- Database: `test_database2temp`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `CREATED_AT` datetime DEFAULT NULL,
  `TOKEN` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_tokens`
--

INSERT INTO `api_tokens` (`ID`, `USER_ID`, `CREATED_AT`, `TOKEN`) VALUES
(1, 1, NULL, '8ced661ebc491ba0d672d909ef38ac12'),
(2, 2, NULL, 'e7c7d9a872b834242391ec5f6f272cf0'),
(3, 1, NULL, 'c9accf6bbb5b312f81d33fa620e42d3b'),
(4, 1, NULL, '5843127323cfb510a1395f1e14b88ae5'),
(5, 1, NULL, 'cf58683b1df7465f434a36291f53cd61'),
(6, 1, NULL, '163f57bf66f792ad792076a4950ac486'),
(7, 1, NULL, '36ac77233691adeb411b6d2610d6f9cd'),
(8, 1, NULL, 'b8dc5fe461fc50094f7ec7c245f86685'),
(9, 1, NULL, 'c712da9d1545f8bbbd30c6efa1d3e0dd'),
(10, 1, NULL, '9be0869004b8e292b924bf01d2b62f07'),
(11, 1, NULL, '86c0e93321983d911096a3bfb6ec461f'),
(12, 1, NULL, '3a215e7b044e4a6f2923153bccaf52e4'),
(13, 1, NULL, '8d02e1247188fc86823c6f555affea6d'),
(14, 1, NULL, '4b276b34ad2b2a648768ac6dcb776ae4'),
(15, 1, NULL, 'da25cd9dcf694cf94ddeb0aa9ec6f3fd'),
(16, 1, NULL, '301676a94462771a88657c5780b686a6'),
(17, 1, NULL, '6902cb046703f892a77536eee6a39d93'),
(18, 1, NULL, '3e3fcd67b06ad2b01708e853aa2226de'),
(19, 1, NULL, 'ebb6cb7c0d89a41a9a8d8a00df3a1a00'),
(20, 1, NULL, '37428d3159a42de3c82bc0a654598495'),
(21, 1, NULL, '46f8f9c8bc26acccd258a3cb99f7c028'),
(22, 1, NULL, 'b21dd2b78280c47c04f2e773cc6f9842'),
(23, 1, NULL, 'e44c40bbac85ad3829be0ea783257255'),
(24, 1, NULL, '929b06dc36f9a6f37967fb9f1ba8660a'),
(25, 1, NULL, '2a2465014dd19561ba886c5b64c826b6'),
(26, 1, NULL, '224ed4069c84afbdfa40446cde69996e'),
(27, 1, NULL, '9b3bb6d72515bbdbceaa13b6f0aeea5a'),
(28, 1, NULL, 'fccce345d2d33a93ef0a2ccb47c94e19'),
(29, 1, NULL, '96bdd95970acbb730b1edfeae3e92a8e'),
(30, 1, NULL, 'd7e39e55e0df7c635f2681c9541f1a02'),
(31, 1, NULL, '53e974f31c6696ad359dfb23b9ef8cfc'),
(32, 1, NULL, 'a8fa17fc67dc4684eff7bfa11645dbc4'),
(33, 1, NULL, 'f12c7e566835a6e60c37fbe39cc1c4cf'),
(34, 1, NULL, '4911b40a4998c3b24ce9f442b711b4b0'),
(35, 1, NULL, 'e5e7886d76b349fe71448e4ea3202b74'),
(36, 1, NULL, '21d4148e0fb97c72cf161b385a6fa416'),
(37, 1, NULL, '0bd3a0290919b216e97a32b6890564d0');

--
-- Triggers `api_tokens`
--
DELIMITER $$
CREATE TRIGGER `BEFORE_API_TOKEN_INSERT` BEFORE INSERT ON `api_tokens` FOR EACH ROW BEGIN UPDATE USERS SET LAST_LOGIN = NOW() WHERE ID = NEW.USER_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `CREATED_AT` datetime DEFAULT current_timestamp(),
  `UPDATED_AT` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ID`, `NAME`, `DESCRIPTION`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 'Appetizers', 'Delicious starters to begin your meal with.', '2024-07-23 00:15:04', NULL),
(2, 'Main Courses', 'Hearty and fulfilling main course recipes.', '2024-07-23 00:15:19', NULL),
(3, 'Desserts', 'Sweet treats to end your meal on a high note.', '2024-07-23 00:15:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int(11) NOT NULL,
  `POST_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `CONTENT` text NOT NULL,
  `CREATED_AT` datetime DEFAULT current_timestamp(),
  `UPDATED_AT` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `PARENT_COMMENT_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `comments`
--
DELIMITER $$
CREATE TRIGGER `AFTER_COMMENT_DELETE` AFTER DELETE ON `comments` FOR EACH ROW BEGIN UPDATE POSTS SET COMMENTS_COUNT = COMMENTS_COUNT - 1 WHERE ID = OLD.POST_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `AFTER_COMMENT_INSERT` AFTER INSERT ON `comments` FOR EACH ROW BEGIN UPDATE POSTS SET COMMENTS_COUNT = COMMENTS_COUNT + 1 WHERE ID = NEW.POST_ID;
 -- Notification for post owner
INSERT INTO NOTIFICATIONS (
    USER_ID,
    TYPE,
    CONTENT
)
    SELECT
        P.USER_ID,
        'post_commented',
        CONCAT(U.USERNAME,
        ' commented on your post')
    FROM
        POSTS P
        JOIN USERS U
        ON U.ID = NEW.USER_ID
    WHERE
        P.ID = NEW.POST_ID
        AND P.USER_ID != NEW.USER_ID;
 -- Notification for parent comment owner (if it's a reply)
IF NEW.PARENT_COMMENT_ID IS NOT NULL THEN
    INSERT INTO NOTIFICATIONS (
        USER_ID,
        TYPE,
        CONTENT
    )
        SELECT
            C.USER_ID,
            'comment_reply',
            CONCAT(U.USERNAME,
            ' replied to your comment')
        FROM
            COMMENTS C
            JOIN USERS U
            ON U.ID = NEW.USER_ID
        WHERE
            C.ID = NEW.PARENT_COMMENT_ID
            AND C.USER_ID != NEW.USER_ID;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `TYPE` varchar(50) NOT NULL,
  `CONTENT` text NOT NULL,
  `IS_READ` tinyint(1) DEFAULT 0,
  `CREATED_AT` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  `TITLE` varchar(255) DEFAULT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `CONTENT` text DEFAULT NULL,
  `DATE` datetime DEFAULT current_timestamp(),
  `UPDATED_AT` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `LIKES_COUNT` int(11) DEFAULT 0,
  `COMMENTS_COUNT` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `USER_ID`, `CATEGORY_ID`, `TITLE`, `DESCRIPTION`, `CONTENT`, `DATE`, `UPDATED_AT`, `LIKES_COUNT`, `COMMENTS_COUNT`) VALUES
(1, 1, 2, 'Sample Post Title', 'This is a sample post description.', '1_1721568784.png', '2024-07-23 00:21:55', '2024-07-23 18:15:22', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `post_reactions`
--

CREATE TABLE `post_reactions` (
  `ID` int(11) NOT NULL,
  `POST_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `REACTION_TYPE` enum('like','love','haha','wow','sad','angry') NOT NULL,
  `CREATED_AT` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `post_reactions`
--
DELIMITER $$
CREATE TRIGGER `AFTER_POST_REACTION_DELETE` AFTER DELETE ON `post_reactions` FOR EACH ROW BEGIN UPDATE POSTS SET LIKES_COUNT = LIKES_COUNT - 1 WHERE ID = OLD.POST_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `AFTER_POST_REACTION_INSERT` AFTER INSERT ON `post_reactions` FOR EACH ROW BEGIN UPDATE POSTS SET LIKES_COUNT = LIKES_COUNT + 1 WHERE ID = NEW.POST_ID;
INSERT INTO NOTIFICATIONS (
    USER_ID,
    TYPE,
    CONTENT
)
    SELECT
        P.USER_ID,
        'post_liked',
        CONCAT(U.USERNAME,
        ' liked your post')
    FROM
        POSTS P
        JOIN USERS U
        ON U.ID = NEW.USER_ID
    WHERE
        P.ID = NEW.POST_ID
        AND P.USER_ID != NEW.USER_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `FULLNAME` varchar(255) NOT NULL,
  `USERNAME` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `NOOFFOLLOWERS` int(11) DEFAULT 0,
  `NOOFFOLLOWING` int(11) DEFAULT 0,
  `TYPE` varchar(20) DEFAULT 'Usual',
  `IMAGE` varchar(255) DEFAULT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `BIO` text DEFAULT NULL,
  `PHONE` varchar(15) DEFAULT NULL,
  `LOCATION` varchar(255) DEFAULT NULL,
  `WEBSITE` varchar(255) DEFAULT NULL,
  `REGISTRATION_DATE` datetime DEFAULT current_timestamp(),
  `LAST_LOGIN` datetime DEFAULT NULL,
  `IS_ACTIVE` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `FULLNAME`, `USERNAME`, `EMAIL`, `NOOFFOLLOWERS`, `NOOFFOLLOWING`, `TYPE`, `IMAGE`, `PASSWORD`, `BIO`, `PHONE`, `LOCATION`, `WEBSITE`, `REGISTRATION_DATE`, `LAST_LOGIN`, `IS_ACTIVE`) VALUES
(1, 'aaa', 'aaa', 'aaa@gmail.com', 0, 0, 'Usual', NULL, '$2y$10$WxN5HEUyZbysik.wqQ.1w.yRI3rJGXO78wA72tO2u4H.ne5onb6b.', NULL, '123', NULL, NULL, '2024-07-23 00:05:07', '2024-07-24 18:20:26', 1),
(2, 'aaa', 'aaa2', 'aaa2@gmail.com', 0, 0, 'Usual', NULL, '$2y$10$YgHTT/xXTHRKiaffwzeKpOLk2NtqkISenCqpxi8fyBJykZDoDkHmy', NULL, '123', NULL, NULL, '2024-07-23 22:55:24', '2024-07-23 22:55:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_relationships`
--

CREATE TABLE `user_relationships` (
  `ID` int(11) NOT NULL,
  `FOLLOWER_ID` int(11) NOT NULL,
  `FOLLOWED_ID` int(11) NOT NULL,
  `CREATED_AT` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `user_relationships`
--
DELIMITER $$
CREATE TRIGGER `AFTER_USER_RELATIONSHIP_DELETE` AFTER DELETE ON `user_relationships` FOR EACH ROW BEGIN UPDATE USERS SET NOOFFOLLOWERS = NOOFFOLLOWERS - 1 WHERE ID = OLD.FOLLOWED_ID;
UPDATE USERS
SET
    NOOFFOLLOWING = NOOFFOLLOWING - 1
WHERE
    ID = OLD.FOLLOWER_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `AFTER_USER_RELATIONSHIP_INSERT` AFTER INSERT ON `user_relationships` FOR EACH ROW BEGIN
    UPDATE USERS
    SET
        NOOFFOLLOWERS = NOOFFOLLOWERS + 1
    WHERE
        ID = NEW.FOLLOWED_ID;
    UPDATE USERS
    SET
        NOOFFOLLOWING = NOOFFOLLOWING + 1
    WHERE
        ID = NEW.FOLLOWER_ID;
    INSERT INTO NOTIFICATIONS (
        USER_ID,
        TYPE,
        CONTENT
    )
        SELECT
            NEW.FOLLOWED_ID,
            'new_follower',
            CONCAT(U.USERNAME,
            ' started following you')
        FROM
            USERS U
        WHERE
            U.ID = NEW.FOLLOWER_ID;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PARENT_COMMENT_ID` (`PARENT_COMMENT_ID`),
  ADD KEY `IDX_COMMENTS_POST_ID` (`POST_ID`),
  ADD KEY `IDX_COMMENTS_USER_ID` (`USER_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDX_NOTIFICATIONS_USER_ID` (`USER_ID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `CATEGORY_ID` (`CATEGORY_ID`),
  ADD KEY `IDX_POSTS_USER_ID` (`USER_ID`),
  ADD KEY `IDX_POSTS_CATEGORY_ID` (`CATEGORY_ID`);

--
-- Indexes for table `post_reactions`
--
ALTER TABLE `post_reactions`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_USER_POST_REACTION` (`USER_ID`,`POST_ID`),
  ADD KEY `IDX_POST_REACTIONS_POST_ID` (`POST_ID`),
  ADD KEY `IDX_POST_REACTIONS_USER_ID` (`USER_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `user_relationships`
--
ALTER TABLE `user_relationships`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_RELATIONSHIP` (`FOLLOWER_ID`,`FOLLOWED_ID`),
  ADD KEY `FOLLOWED_ID` (`FOLLOWED_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_tokens`
--
ALTER TABLE `api_tokens`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_reactions`
--
ALTER TABLE `post_reactions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_relationships`
--
ALTER TABLE `user_relationships`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD CONSTRAINT `API_TOKENS_IBFK_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`PARENT_COMMENT_ID`) REFERENCES `comments` (`ID`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`ID`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `POSTS_IBFK_1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `POSTS_IBFK_2` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `category` (`ID`);

--
-- Constraints for table `post_reactions`
--
ALTER TABLE `post_reactions`
  ADD CONSTRAINT `post_reactions_ibfk_1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`ID`),
  ADD CONSTRAINT `post_reactions_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `user_relationships`
--
ALTER TABLE `user_relationships`
  ADD CONSTRAINT `user_relationships_ibfk_1` FOREIGN KEY (`FOLLOWER_ID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `user_relationships_ibfk_2` FOREIGN KEY (`FOLLOWED_ID`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
