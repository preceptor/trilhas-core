-- phpMyAdmin SQL Dump
-- version 3.3.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2011 at 10:36 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `trilhas`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `begin` date NOT NULL,
  `end` date DEFAULT NULL,
  `status` enum('active','inactive','final') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_text`
--

CREATE TABLE IF NOT EXISTS `activity_text` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `sender` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','final','close') DEFAULT 'open',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `activity_id` (`activity_id`),
  KEY `sender` (`sender`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) DEFAULT NULL,
  `description` text NOT NULL,
  `begin` date NOT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `certificate`
--

CREATE TABLE IF NOT EXISTS `certificate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sender` bigint(20) NOT NULL,
  `receiver` bigint(20) NOT NULL,
  `description` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE IF NOT EXISTS `chat_room` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `max_student` int(10) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','close') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_room_message`
--

CREATE TABLE IF NOT EXISTS `chat_room_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chat_room_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `description` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('logged','message') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_room_id` (`chat_room_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

CREATE TABLE IF NOT EXISTS `classroom` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) NOT NULL,
  `responsible` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date DEFAULT NULL,
  `max_student` int(10) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `visibility` enum('public','protected','private') NOT NULL DEFAULT 'public',
  `register_type` enum('open','payment','process') NOT NULL DEFAULT 'open',
  `status` enum('active','open','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `responsible` (`responsible`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `classroom_user`
--

CREATE TABLE IF NOT EXISTS `classroom_user` (
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('registered','approved','disapproved','justified','not-justified','waiting') NOT NULL DEFAULT 'registered',
  PRIMARY KEY (`user_id`,`classroom_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `institution_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text,
  `autoload` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `institution_id` (`institution_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) NOT NULL,
  `content_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `position` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1858 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_access`
--

CREATE TABLE IF NOT EXISTS `content_access` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=982 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_file`
--

CREATE TABLE IF NOT EXISTS `content_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_template`
--

CREATE TABLE IF NOT EXISTS `content_template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `responsible` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `information` text,
  `hours` tinyint(4) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Uncategorized',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `responsible` (`responsible`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=206 ;

-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

CREATE TABLE IF NOT EXISTS `exercise` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `time` int(10) DEFAULT NULL,
  `begin` date NOT NULL,
  `end` date DEFAULT NULL,
  `attempts` bigint(20) NOT NULL DEFAULT '2',
  `status` enum('active','inactive','final') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_answer`
--

CREATE TABLE IF NOT EXISTS `exercise_answer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exercise_option_id` bigint(20) NOT NULL,
  `exercise_note_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exercise_value_id` (`exercise_option_id`),
  KEY `exercise_note_id` (`exercise_note_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_note`
--

CREATE TABLE IF NOT EXISTS `exercise_note` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `exercise_id` bigint(20) NOT NULL,
  `note` tinyint(3) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_option`
--

CREATE TABLE IF NOT EXISTS `exercise_option` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exercise_question_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `justify` text,
  `status` enum('right','wrong') NOT NULL DEFAULT 'wrong',
  PRIMARY KEY (`id`),
  KEY `exercise_question_id` (`exercise_question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_question`
--

CREATE TABLE IF NOT EXISTS `exercise_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exercise_id` bigint(20) DEFAULT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `description` text NOT NULL,
  `note` tinyint(3) DEFAULT NULL,
  `position` int(10) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum`
--

CREATE TABLE IF NOT EXISTS `forum` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `begin` date NOT NULL,
  `end` date DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_access`
--

CREATE TABLE IF NOT EXISTS `forum_access` (
  `user_id` bigint(20) NOT NULL,
  `forum_id` bigint(20) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`forum_id`),
  KEY `forum_id` (`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_reply`
--

CREATE TABLE IF NOT EXISTS `forum_reply` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `forum_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_subscribe`
--

CREATE TABLE IF NOT EXISTS `forum_subscribe` (
  `user_id` bigint(20) NOT NULL,
  `forum_id` bigint(20) NOT NULL,
  PRIMARY KEY (`user_id`,`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `glossary`
--

CREATE TABLE IF NOT EXISTS `glossary` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `word` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `institution`
--

CREATE TABLE IF NOT EXISTS `institution` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) DEFAULT NULL,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) DEFAULT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','delete') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_receiver`
--

CREATE TABLE IF NOT EXISTS `message_receiver` (
  `message_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notepad`
--

CREATE TABLE IF NOT EXISTS `notepad` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `institution_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `position` tinyint(4) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `institution_id` (`institution_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `panel`
--

CREATE TABLE IF NOT EXISTS `panel` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `type` enum('exercise','forum','activity') NOT NULL,
  `item_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `panel_note`
--

CREATE TABLE IF NOT EXISTS `panel_note` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `panel_id` bigint(20) DEFAULT NULL,
  `note` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `panel_id` (`panel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `restriction_panel`
--

CREATE TABLE IF NOT EXISTS `restriction_panel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `note` tinyint(3) DEFAULT NULL,
  `panel_id` int(20) NOT NULL,
  `note_restriction` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `restriction_time`
--

CREATE TABLE IF NOT EXISTS `restriction_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `begin` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `selection_process`
--

CREATE TABLE IF NOT EXISTS `selection_process` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `justify` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('waiting','accepts','rejected') NOT NULL DEFAULT 'waiting',
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `timeline`
--

CREATE TABLE IF NOT EXISTS `timeline` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `institution_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sex` enum('M','F') DEFAULT 'M',
  `born` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` enum('student','teacher','coordinator','institution') NOT NULL DEFAULT 'student',
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `institution_id` (`institution_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `institution_id` bigint(20) NOT NULL,
  `position` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT 'default',
  `controller` varchar(255) NOT NULL DEFAULT 'index',
  `action` varchar(255) NOT NULL DEFAULT 'index',
  `order` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `institution_id` (`institution_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `activity_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `activity_text`
--
ALTER TABLE `activity_text`
  ADD CONSTRAINT `activity_text_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `activity_text_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`),
  ADD CONSTRAINT `activity_text_ibfk_3` FOREIGN KEY (`sender`) REFERENCES `user` (`id`);

--
-- Constraints for table `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `calendar_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `calendar_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `calendar_ibfk_3` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `certificate`
--
ALTER TABLE `certificate`
  ADD CONSTRAINT `certificate_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `certificate_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `user` (`id`);

--
-- Constraints for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD CONSTRAINT `chat_room_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `chat_room_message`
--
ALTER TABLE `chat_room_message`
  ADD CONSTRAINT `chat_room_message_ibfk_1` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`),
  ADD CONSTRAINT `chat_room_message_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `classroom`
--
ALTER TABLE `classroom`
  ADD CONSTRAINT `classroom_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `classroom_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `classroom_ibfk_3` FOREIGN KEY (`responsible`) REFERENCES `user` (`id`);

--
-- Constraints for table `classroom_user`
--
ALTER TABLE `classroom_user`
  ADD CONSTRAINT `classroom_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `classroom_user_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `configuration`
--
ALTER TABLE `configuration`
  ADD CONSTRAINT `configuration_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`id`);

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `content_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Constraints for table `content_access`
--
ALTER TABLE `content_access`
  ADD CONSTRAINT `content_access_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `content_access_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`responsible`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `exercise`
--
ALTER TABLE `exercise`
  ADD CONSTRAINT `exercise_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `exercise_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `exercise_answer`
--
ALTER TABLE `exercise_answer`
  ADD CONSTRAINT `exercise_answer_ibfk_2` FOREIGN KEY (`exercise_option_id`) REFERENCES `exercise_option` (`id`),
  ADD CONSTRAINT `exercise_answer_ibfk_3` FOREIGN KEY (`exercise_note_id`) REFERENCES `exercise_note` (`id`);

--
-- Constraints for table `exercise_option`
--
ALTER TABLE `exercise_option`
  ADD CONSTRAINT `exercise_option_ibfk_1` FOREIGN KEY (`exercise_question_id`) REFERENCES `exercise_question` (`id`);

--
-- Constraints for table `exercise_question`
--
ALTER TABLE `exercise_question`
  ADD CONSTRAINT `exercise_question_ibfk_1` FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`);

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `file_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `forum_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `forum_access`
--
ALTER TABLE `forum_access`
  ADD CONSTRAINT `forum_access_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `forum_access_ibfk_2` FOREIGN KEY (`forum_id`) REFERENCES `forum` (`id`);

--
-- Constraints for table `forum_reply`
--
ALTER TABLE `forum_reply`
  ADD CONSTRAINT `forum_reply_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forum` (`id`),
  ADD CONSTRAINT `forum_reply_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `glossary`
--
ALTER TABLE `glossary`
  ADD CONSTRAINT `glossary_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `glossary_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `glossary_ibfk_3` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `log_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `message_receiver`
--
ALTER TABLE `message_receiver`
  ADD CONSTRAINT `message_receiver_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`),
  ADD CONSTRAINT `message_receiver_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `notepad`
--
ALTER TABLE `notepad`
  ADD CONSTRAINT `notepad_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `notepad_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `page_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`id`);

--
-- Constraints for table `panel`
--
ALTER TABLE `panel`
  ADD CONSTRAINT `panel_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `panel_note`
--
ALTER TABLE `panel_note`
  ADD CONSTRAINT `panel_note_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `panel_note_ibfk_2` FOREIGN KEY (`panel_id`) REFERENCES `panel` (`id`);

--
-- Constraints for table `selection_process`
--
ALTER TABLE `selection_process`
  ADD CONSTRAINT `selection_process_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `selection_process_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `timeline`
--
ALTER TABLE `timeline`
  ADD CONSTRAINT `timeline_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `timeline_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`id`);

--
-- Constraints for table `widget`
--
ALTER TABLE `widget`
  ADD CONSTRAINT `widget_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`id`);
