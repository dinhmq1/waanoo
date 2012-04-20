-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 27, 2012 at 02:24 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `waanoo_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE IF NOT EXISTS `attendees` (
  `attend_id` int(20) NOT NULL AUTO_INCREMENT,
  `event_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  PRIMARY KEY (`attend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_address`
--

CREATE TABLE IF NOT EXISTS `event_address` (
  `address_id` int(20) NOT NULL AUTO_INCREMENT,
  `event_id` int(20) NOT NULL,
  `address_text` varchar(255) NOT NULL,
  `x_coord` float(10,6) NOT NULL,
  `y_coord` float(10,6) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_images`
--

CREATE TABLE IF NOT EXISTS `event_images` (
  `image_id` int(20) NOT NULL AUTO_INCREMENT,
  `event_id` int(20) NOT NULL,
  `image_url` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `list_order` int(3) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_events`
--

CREATE TABLE IF NOT EXISTS `user_events` (
  `event_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `event_title` varchar(100) NOT NULL,
  `event_description` varchar(500) NOT NULL,
  `end_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `public` int(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE IF NOT EXISTS `user_images` (
  `image_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `image_url` int(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_list`
--

CREATE TABLE IF NOT EXISTS `user_list` (
  `user_id` int(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(35) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(15) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_list`
--

INSERT INTO `user_list` (`user_id`, `email`, `password`, `first_name`, `last_name`, `date_added`, `last_login`, `last_ip`) VALUES
(1, 'lol@lol.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'hurr', 'durr', '0000-00-00 00:00:00', '2012-03-26 20:17:28', '127.0.0.1'),
(2, 'lol@lol.com2', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'derp', 'derp', '2012-03-26 04:40:18', '2012-03-26 19:39:34', '127.0.0.1'),
(3, 'derp@derp.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'aaron', 'derp', '2012-03-26 04:42:19', '0000-00-00 00:00:00', ''),
(4, 'derp@derp.com3', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'aaron', 'derp', '2012-03-26 04:44:36', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `YQL_events`
--

CREATE TABLE IF NOT EXISTS `YQL_events` (
  `event_id` int(20) NOT NULL AUTO_INCREMENT,
  `yql_id` int(20) NOT NULL,
  `event_title` varchar(100) NOT NULL,
  `event_description` varchar(500) NOT NULL,
  `end_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `public` int(1) NOT NULL,
  `price` float(5,2) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `venue_name` varchar(100) NOT NULL,
  `url_link` varchar(100) NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `yql_id` (`yql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `YQL_events`
--

INSERT INTO `YQL_events` (`event_id`, `yql_id`, `event_title`, `event_description`, `end_date`, `start_date`, `date_created`, `public`, `price`, `country_name`, `venue_name`, `url_link`) VALUES
(51, 8744007, 'Derek Webb and Sandra McCracken', 'Singer/songwriter Derek Webb continues to make iconoclastic, irresistible, radio-ready pop records about love and war and social justice. However, unlike most pop artists on the scene today, Webb''s engaged, committed fan-base is constantly expanding - in part because Derek Webb has a tendency to give his music away for free.  In 2006, Webb convinced his label, INO Records / Columbia Records, to give away over 80,000 free downloads of his critically acclaimed album, MOCKINGBIRD. This widely publi', '2012-03-24 05:03:00', '2012-03-24 02:03:00', '2012-02-10 05:02:50', 1, 0.00, 'United States', 'Jammin Java', 'http://jamminjava.3dcartstores.com/product.asp?itemid=1840'),
(52, 8530315, 'Kathy Griffin', 'Kathy Griffin, stand-up comedian, actress, author and television personality best known for her reality show Kathy Griffin: My Life on the D-List. The double Emmy winning Chicago native is a multi-faceted performer with a rapid-fire wit. In Australia she is probably most recognisable for her four-year stint on the NBC sitcom "Suddenly Susan" as Vickie Groener, Brooke Shields'' acerbic colleague. Kathy also had a recurring guest role on Seinfeld as the character Sally Weaver. Famous for her signat', '2012-03-24 06:03:00', '2012-03-24 03:03:00', '2011-10-26 10:10:49', 1, 0.00, 'United States', 'Wilbur Theatre - MA', 'http://www.ticketmaster.com/event/010046B9851F70B8?camefrom=cfc_boswilbur_fl'),
(53, 8162210, 'Kathy Griffin', 'Event: Kathy Griffin<br>Venue: Wilbur Theatre - MA<br>Start: 3/23/2012 10:00:00 PM<br>Category: CONCERTS COMEDY', '2012-03-24 06:03:00', '2012-03-24 03:03:00', '2011-06-25 10:06:29', 1, 0.00, 'United States', 'Wilbur Theater', 'http://upcomingevents.net/search/redirect/1639064');

-- --------------------------------------------------------

--
-- Table structure for table `YQL_event_address`
--

CREATE TABLE IF NOT EXISTS `YQL_event_address` (
  `address_id` int(20) NOT NULL AUTO_INCREMENT,
  `event_id` int(20) NOT NULL,
  `address_text` varchar(255) NOT NULL,
  `x_coord` float(10,6) NOT NULL,
  `y_coord` float(10,6) NOT NULL,
  PRIMARY KEY (`address_id`),
  UNIQUE KEY `event_id` (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `YQL_event_address`
--

INSERT INTO `YQL_event_address` (`address_id`, `event_id`, `address_text`, `x_coord`, `y_coord`) VALUES
(42, 51, '227 Maple Ave E', 38.903900, -77.262199),
(43, 52, 'Boston, MA', 38.817501, -77.268204),
(44, 53, '246 Tremont St', 42.350300, -71.065002);

-- --------------------------------------------------------

--
-- Table structure for table `YQL_event_images`
--

CREATE TABLE IF NOT EXISTS `YQL_event_images` (
  `image_id` int(20) NOT NULL AUTO_INCREMENT,
  `event_id` int(20) NOT NULL,
  `image_url` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `list_order` int(3) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
