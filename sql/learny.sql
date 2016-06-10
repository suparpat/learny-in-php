-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2016 at 09:55 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `learny`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(10000) NOT NULL,
  `post_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `post_id`, `uid`, `created_at`, `updated_at`) VALUES
(1, 'Test comment', 1, 1, '2016-06-04 16:40:06', '2016-06-04 16:40:06'),
(2, '123', 2, 0, '2016-06-06 15:31:12', '2016-06-06 15:31:12'),
(3, '123', 2, 0, '2016-06-06 15:32:48', '2016-06-06 15:32:48'),
(4, 'asd', 2, 0, '2016-06-06 15:32:50', '2016-06-06 15:32:50'),
(5, 'sdf', 2, 0, '2016-06-06 15:33:18', '2016-06-06 15:33:18'),
(6, 'sdf', 2, 0, '2016-06-06 15:33:19', '2016-06-06 15:33:19'),
(7, 'dfdsf', 2, 1, '2016-06-06 15:41:47', '2016-06-06 15:41:47'),
(8, '123', 23, 1, '2016-06-10 16:45:57', '2016-06-10 16:45:57'),
(9, 'yo', 23, 4, '2016-06-10 16:46:59', '2016-06-10 16:46:59'),
(10, 'cool!', 25, 1, '2016-06-10 19:55:00', '2016-06-10 19:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(300) NOT NULL,
  `content` mediumtext NOT NULL,
  `type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `uid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `subject`, `content`, `type`, `uid`, `created_at`, `updated_at`) VALUES
(25, 'Learny', '<p>- Allow up/downvoting of posts</p>\r\n\r\n<p>- Allow post categorization by tag (limit one post to 5 tags?)</p>\r\n\r\n<p>- Make sure tags are retrieve and shown on the edit post page</p>\r\n\r\n<p>- Make sure tags of a post can be modified</p>\r\n\r\n<p>- Make sure when a post is deleted, relevant rows in posts_tags table are also removed</p>\r\n\r\n<p>- Show number of posts of each tag? in tags section... this might be expensive on resource. perhaps need to cache.</p>\r\n\r\n<p>- Should users be able to delete post? Allowing deletion is a bit complicated as many tables will be affected</p>\r\n', 'idea', 1, '2016-06-10 16:51:05', '2016-06-10 16:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `posts_tags`
--

CREATE TABLE IF NOT EXISTS `posts_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `posts_tags`
--

INSERT INTO `posts_tags` (`id`, `post_id`, `tag_id`) VALUES
(1, 30, 1),
(2, 30, 2),
(3, 25, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, '7878'),
(2, '567567'),
(3, 'hi'),
(4, 'planning');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `email`) VALUES
(1, 'suparpat', '$2y$10$4LXfCFUg2mYKp1FoqSH6W.XzGGBQJVG44StvyU91BdJBcyEcmAPvi', 'pawat.s@live.com'),
(2, 'patpat', '$2y$10$GXI6Z1pZVgZm6Zu4g720h.Yl8FSc5bb9iTiue8u3h4hjyJbinGF6O', 'abc@gmail.com'),
(3, 'test', '$2y$10$aNgcxtiJ62nhlMBl.oC0o..diM5QrhsO77hcM99p4fUpRu2.JYKQC', 'dfsdfsd@gmail.com'),
(4, '123456', '$2y$10$DDQRZBRzojB77vIwjTlxoO13FGkmU/zDA4P6KHDtS7NWdNa.Vmgoa', 'zzzzz@gmail.com'),
(5, '555555', '$2y$10$8IiUU//852xH/3lZJatH9uKmCCJSXg0e6/DPR5WoaQ48fv5yjBYlW', 'asdasd@gmail.com'),
(6, 'admin', '$2y$10$Qy33cGqXECp0MlzkAxjheudIC7GvYgvYAkPk3BbAJHfXV6gJoAlhS', 'blalbalbla@gmail.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
