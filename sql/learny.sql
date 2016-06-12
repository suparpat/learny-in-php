-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2016 at 09:18 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `post_id`, `uid`, `created_at`, `updated_at`) VALUES
(2, '<p>test</p>\r\n', 1, 1, '2016-06-12 05:18:24', '2016-06-12 05:18:24'),
(3, '<p>test</p>\r\n', 1, 1, '2016-06-12 05:19:15', '2016-06-12 05:19:15'),
(4, '<p>test</p>\r\n', 1, 1, '2016-06-12 05:19:18', '2016-06-12 05:19:18'),
(5, '<p>good</p>\r\n\r\n<p>However, I think blahๆๆๆ</p>\r\n', 1, 1, '2016-06-12 05:22:25', '2016-06-12 05:22:25'),
(6, '<h1><strong>Nice!</strong></h1>\r\n', 1, 1, '2016-06-12 05:30:44', '2016-06-12 05:30:44'),
(7, '<table border="1" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>123456</td>\r\n			<td>123456</td>\r\n		</tr>\r\n		<tr>\r\n			<td>123456</td>\r\n			<td>123456</td>\r\n		</tr>\r\n		<tr>\r\n			<td>123456</td>\r\n			<td>123456</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>\r\n', 1, 1, '2016-06-12 05:31:07', '2016-06-12 05:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(300) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `uid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `subject`, `content`, `type`, `uid`, `created_at`, `updated_at`) VALUES
(1, 'Learny!', '<h2>To-dos</h2>\r\n\r\n<ul>\r\n	<li><s>Allow post categorization by tag (limit one post to 5 tags?)</s></li>\r\n	<li><s>Make sure tags are retrieve and shown on the edit post page</s></li>\r\n	<li><s>Make sure tags of a post can be modified</s></li>\r\n	<li><s>Make sure page title of each page is correct</s></li>\r\n	<li><s>[View Post Page] Hide comment box if user is not logged in</s></li>\r\n	<li><s>[View Post Page] Make comment html instead of plaintext&nbsp;</s></li>\r\n	<li><strong>[Data Structure] make type of a post as table (right now any string is accepted)</strong></li>\r\n	<li><strong>[Post] Allow up/downvoting of posts</strong></li>\r\n	<li>[Post] Allow a post to be public or private/draft?</li>\r\n	<li>[Post Deletion] Make sure when a post is deleted, relevant rows in comments and posts_tags table are also removed</li>\r\n	<li>[Tags] Show number of posts of each tag? in tags section... this might be expensive on resource. perhaps need to cache.</li>\r\n	<li>[Scoring] Add scoring feature: user gets points for\r\n	<ul>\r\n		<li>getting upvote on post</li>\r\n		<li>getting upvote on comments</li>\r\n	</ul>\r\n	</li>\r\n	<li>[Usability] make dates relative i.e. 3 hours ago</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Thoughts</h2>\r\n\r\n<ul>\r\n	<li>[Post] Should users be able to delete post? Allowing deletion is a bit complicated as many tables will be affected</li>\r\n	<li>Make a Thai version of the website?</li>\r\n</ul>\r\n', 'thought', 1, '2016-06-11 09:21:49', '2016-06-12 06:06:34');

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
(3, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `posts_type`
--

CREATE TABLE IF NOT EXISTS `posts_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(3, 'plan for learny');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(5, 'etc'),
(4, 'General'),
(2, 'Mathematics'),
(1, 'Science'),
(3, 'Social Sciences');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'suparpat', '$2y$10$4LXfCFUg2mYKp1FoqSH6W.XzGGBQJVG44StvyU91BdJBcyEcmAPvi', 'pawat.s@live.com', '2016-06-11 09:02:25'),
(2, 'patpat', '$2y$10$GXI6Z1pZVgZm6Zu4g720h.Yl8FSc5bb9iTiue8u3h4hjyJbinGF6O', 'abc@gmail.com', '2016-06-11 09:02:25'),
(3, 'test', '$2y$10$aNgcxtiJ62nhlMBl.oC0o..diM5QrhsO77hcM99p4fUpRu2.JYKQC', 'dfsdfsd@gmail.com', '2016-06-11 09:02:25'),
(4, '123456', '$2y$10$DDQRZBRzojB77vIwjTlxoO13FGkmU/zDA4P6KHDtS7NWdNa.Vmgoa', 'zzzzz@gmail.com', '2016-06-11 09:02:25'),
(5, '555555', '$2y$10$8IiUU//852xH/3lZJatH9uKmCCJSXg0e6/DPR5WoaQ48fv5yjBYlW', 'asdasd@gmail.com', '2016-06-11 09:02:25'),
(6, 'admin', '$2y$10$Qy33cGqXECp0MlzkAxjheudIC7GvYgvYAkPk3BbAJHfXV6gJoAlhS', 'blalbalbla@gmail.com', '2016-06-11 09:02:25');

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
