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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

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
  `name` varchar(255) NOT NULL,
  `value` text,
  `autoload` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `institution_id_2` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) NOT NULL,
  `content_id` bigint(20) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `position` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2062 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1805 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_file`
--

CREATE TABLE IF NOT EXISTS `content_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_template`
--

CREATE TABLE IF NOT EXISTS `content_template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `restriction_panel`
--

CREATE TABLE IF NOT EXISTS `restriction_panel` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `note` tinyint(3) DEFAULT NULL,
  `panel_id` bigint(20) NOT NULL,
  `note_restriction` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `content_id` (`content_id`),
  KEY `panel_id` (`panel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment` varchar(255) NOT NULL,
  `cellphone` varchar(13) NOT NULL,
  `landline` varchar(13) NOT NULL,
  `know` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `uf` varchar(4) NOT NULL,
  `city` varchar(255) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `neighborhood` varchar(255) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `cpf` varchar(14) NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=332 ;

-- --------------------------------------------------------

--
-- Table structure for table `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT 'default',
  `controller` varchar(255) NOT NULL DEFAULT 'index',
  `action` varchar(255) NOT NULL DEFAULT 'index',
  `order` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classroom`
--
ALTER TABLE `classroom`
  ADD CONSTRAINT `classroom_ibfk_3` FOREIGN KEY (`responsible`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `classroom_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `classroom_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Constraints for table `classroom_user`
--
ALTER TABLE `classroom_user`
  ADD CONSTRAINT `classroom_user_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `content_access`
--
ALTER TABLE `content_access`
  ADD CONSTRAINT `content_access_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`);

--
-- Constraints for table `content_file`
--
ALTER TABLE `content_file`
  ADD CONSTRAINT `content_file_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_3` FOREIGN KEY (`responsible`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `log_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `panel`
--
ALTER TABLE `panel`
  ADD CONSTRAINT `panel_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);

--
-- Constraints for table `restriction_panel`
--
ALTER TABLE `restriction_panel`
  ADD CONSTRAINT `restriction_panel_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `restriction_panel_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `restriction_panel_ibfk_3` FOREIGN KEY (`panel_id`) REFERENCES `panel` (`id`);

--
-- Constraints for table `restriction_time`
--
ALTER TABLE `restriction_time`
  ADD CONSTRAINT `restriction_time_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `restriction_time_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`);


--
-- Constraints for table `timeline`
--
ALTER TABLE `timeline`
  ADD CONSTRAINT `timeline_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `timeline_ibfk_2` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`);