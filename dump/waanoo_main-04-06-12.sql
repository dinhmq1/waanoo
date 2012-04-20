-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 07, 2012 at 03:57 AM
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

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
  `privlege_level` varchar(20) NOT NULL,
  `sex` varchar(2) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `YQL_events`
--

CREATE TABLE IF NOT EXISTS `YQL_events` (
  `event_id` int(20) NOT NULL AUTO_INCREMENT,
  `yql_id` int(20) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` varchar(500) NOT NULL,
  `end_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `public` int(1) NOT NULL,
  `price` float(5,2) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `venue_name` varchar(100) NOT NULL,
  `url_link` varchar(150) NOT NULL,
  `venue_state` varchar(30) NOT NULL,
  `venue_zip` varchar(10) NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `yql_id` (`yql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=130 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=121 ;

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
