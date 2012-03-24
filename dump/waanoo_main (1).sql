-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 23, 2012 at 09:20 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `waanoo_main`
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

--
-- Dumping data for table `attendees`
--


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

--
-- Dumping data for table `event_address`
--


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

--
-- Dumping data for table `event_images`
--


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

--
-- Dumping data for table `user_events`
--


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

--
-- Dumping data for table `user_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_list`
--

CREATE TABLE IF NOT EXISTS `user_list` (
  `user_id` int(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `sex` varchar(1) NOT NULL,
  `age` int(3) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `YQL_events`
--

CREATE TABLE IF NOT EXISTS `YQL_events` (
  `event_id` int(20) NOT NULL AUTO_INCREMENT,
  `event_title` varchar(100) NOT NULL,
  `event_description` varchar(500) NOT NULL,
  `end_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `public` int(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `YQL_events`
--


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
  PRIMARY KEY (`address_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `YQL_event_address`
--


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

--
-- Dumping data for table `YQL_event_images`
--

