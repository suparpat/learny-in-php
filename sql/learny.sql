-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2016 at 10:39 AM
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
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `post_id`, `uid`, `created_at`, `updated_at`) VALUES
(2, '<p>test</p>\r\n', 1, 1, '2016-06-12 05:18:24', '2016-06-12 05:18:24'),
(3, '<p>test</p>\r\n', 1, 1, '2016-06-12 05:19:15', '2016-06-12 05:19:15'),
(4, '<p>test</p>\r\n', 1, 1, '2016-06-12 05:19:18', '2016-06-12 05:19:18'),
(5, '<p>good</p>\r\n\r\n<p>However, I think blahๆๆๆ</p>\r\n', 1, 1, '2016-06-12 05:22:25', '2016-06-12 05:22:25'),
(6, '<h1><strong>Nice!</strong></h1>\r\n', 1, 1, '2016-06-12 05:30:44', '2016-06-12 05:30:44'),
(7, '<table border="1" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>123456</td>\r\n			<td>123456</td>\r\n		</tr>\r\n		<tr>\r\n			<td>123456</td>\r\n			<td>123456</td>\r\n		</tr>\r\n		<tr>\r\n			<td>123456</td>\r\n			<td>123456</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>\r\n', 1, 1, '2016-06-12 05:31:07', '2016-06-12 05:31:07'),
(8, '<p>I think....</p>\r\n', 1, 1, '2016-06-12 11:15:59', '2016-06-12 11:15:59'),
(9, '<p><img alt="" src="http://f.ptcdn.info/012/006/000/1370761459-9695806677-o.jpg" style="height:344px; width:640px" /></p>\r\n', 5, 1, '2016-06-14 16:02:58', '0000-00-00 00:00:00'),
(10, '<p><img alt="" src="http://f.ptcdn.info/012/006/000/1370761459-9695806677-o.jpg" style="height:344px; width:640px" /></p>\r\n', 5, 1, '2016-06-14 16:03:04', '0000-00-00 00:00:00'),
(11, '<p><img alt="" src="http://f.ptcdn.info/012/006/000/1370761459-9695806677-o.jpg" style="height:344px; width:640px" /></p>\r\n', 5, 1, '2016-06-14 16:04:02', '0000-00-00 00:00:00'),
(12, '<p><img alt="" src="http://vignette4.wikia.nocookie.net/mrmen/images/5/52/Small.gif/revision/latest?cb=20100731114437" style="height:100px; width:100px" /></p>\r\n', 5, 1, '2016-06-14 16:10:11', '0000-00-00 00:00:00'),
(13, '<p>123</p>\r\n', 5, 1, '2016-06-14 16:26:33', '0000-00-00 00:00:00'),
(14, '<iframe width="560" height="315" src="https://www.youtube.com/embed/Um63OQz3bjo" frameborder="0" allowfullscreen></iframe>', 5, 1, '2016-06-14 16:35:29', '0000-00-00 00:00:00'),
(15, '<div class="youtube-embed-wrapper" style="position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden;"><iframe allowfullscreen="" frameborder="0" height="360" src="//www.youtube.com/embed/i3-dxHavRe8" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" width="640"></iframe></div>\r\n\r\n<p>&nbsp;</p>\r\n', 5, 1, '2016-06-14 16:43:01', '0000-00-00 00:00:00'),
(16, '<pre>\r\n&lt;object style=&quot;width:100%;height:100%;width: 820px; height: 461.25px; float: none; clear: both; margin: 2px auto;&quot; data=&quot;http://www.youtube.com/embed/GlIzuTQGgzs&quot;&gt;\r\n&lt;/object&gt;</pre>\r\n', 5, 1, '2016-06-14 16:44:37', '0000-00-00 00:00:00'),
(17, '<object data="http://www.youtube.com/embed/GlIzuTQGgzs"></object>', 5, 1, '2016-06-14 16:44:43', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(300) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `subject`, `content`, `uid`, `votes`, `created_at`, `updated_at`) VALUES
(8, 'Mindful Self-Acceptance? Bad Idea According to Ancient Chinese Philosophers.', '<div style="position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden;"><iframe allowfullscreen="" frameborder="0" height="360" src="//www.youtube.com/embed/i3-dxHavRe8" width="640"></iframe></div>\r\n', 1, 0, '2016-06-14 17:14:32', '2016-06-19 08:36:13'),
(9, 'TIL that the BBC regularly rehearses the Queen''s death in order to make sure that their coverage of it goes without a hitch when she does die.', '<p><a href="http://www.mirror.co.uk/news/uk-news/queen-dead-tweet-blunder-journalist-5818203">http://www.mirror.co.uk/news/uk-news/queen-dead-tweet-blunder-journalist-5818203</a></p>\r\n', 1, 0, '2016-06-17 15:27:02', '2016-06-19 08:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `posts_tags`
--

CREATE TABLE IF NOT EXISTS `posts_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `posts_tags`
--

INSERT INTO `posts_tags` (`id`, `post_id`, `tag_id`) VALUES
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `posts_type`
--

CREATE TABLE IF NOT EXISTS `posts_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `posts_type`
--

INSERT INTO `posts_type` (`id`, `post_id`, `type_id`) VALUES
(1, 4, 4),
(2, 1, 4),
(3, 5, 5),
(4, 6, 3),
(5, 7, 7),
(6, 8, 3),
(7, 9, 8),
(8, 9, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(5, 'collaborative website'),
(4, 'future'),
(3, 'plan for learny'),
(6, 'Queen');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(5, 'Discussion'),
(10, 'Experience'),
(2, 'Fact'),
(4, 'Idea'),
(7, 'Link'),
(1, 'Note'),
(6, 'Question'),
(9, 'Story'),
(8, 'Suggestion'),
(3, 'Thought');

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

-- --------------------------------------------------------

--
-- Table structure for table `users_postvotes`
--

CREATE TABLE IF NOT EXISTS `users_postvotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `vote` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=128 ;

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
