-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 01, 2012 at 02:51 AM
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `event_address`
--

INSERT INTO `event_address` (`address_id`, `event_id`, `address_text`, `x_coord`, `y_coord`) VALUES
(1, 5, '3024 Harrison Ave, Cincinnati, OH 45211, USA', 39.148857, -84.598358),
(2, 6, '8521 Ridge Rd, Cincinnati, OH 45236, USA', 39.215958, -84.426826),
(3, 7, '3040 Harrison Ave, Cincinnati, OH 45211, USA', 39.149223, -84.598038);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `user_events`
--

INSERT INTO `user_events` (`event_id`, `user_id`, `event_title`, `event_description`, `end_date`, `start_date`, `date_created`, `public`) VALUES
(7, 1, 'Party Time MOTHAFUCKAS', 'This event will be so super tight that it will be tight. ', '2012-04-06 20:45:00', '2012-04-08 20:45:00', '2012-03-31 20:45:55', 1),
(6, 1, 'Custom Party', 'Testing', '2012-03-31 00:48:00', '2012-04-02 00:48:00', '2012-03-29 00:48:53', 1),
(5, 1, 'Derp', 'derp', '2012-03-30 00:31:00', '2012-03-31 00:31:00', '2012-03-29 00:35:19', 1);

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
(1, 'lol@lol.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'hurr', 'durr', '0000-00-00 00:00:00', '2012-03-31 13:37:19', '127.0.0.1'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `YQL_events`
--

INSERT INTO `YQL_events` (`event_id`, `yql_id`, `event_title`, `event_description`, `end_date`, `start_date`, `date_created`, `public`, `price`, `country_name`, `venue_name`, `url_link`, `venue_state`, `venue_zip`) VALUES
(51, 8744007, 'Derek Webb and Sandra McCracken', 'Singer/songwriter Derek Webb continues to make iconoclastic, irresistible, radio-ready pop records about love and war and social justice. However, unlike most pop artists on the scene today, Webb''s engaged, committed fan-base is constantly expanding - in part because Derek Webb has a tendency to give his music away for free.  In 2006, Webb convinced his label, INO Records / Columbia Records, to give away over 80,000 free downloads of his critically acclaimed album, MOCKINGBIRD. This widely publi', '2012-03-24 05:03:00', '2012-03-24 02:03:00', '2012-02-10 05:02:50', 1, 0.00, 'United States', 'Jammin Java', 'http://jamminjava.3dcartstores.com/product.asp?itemid=1840', '', ''),
(52, 8530315, 'Kathy Griffin', 'Kathy Griffin, stand-up comedian, actress, author and television personality best known for her reality show Kathy Griffin: My Life on the D-List. The double Emmy winning Chicago native is a multi-faceted performer with a rapid-fire wit. In Australia she is probably most recognisable for her four-year stint on the NBC sitcom "Suddenly Susan" as Vickie Groener, Brooke Shields'' acerbic colleague. Kathy also had a recurring guest role on Seinfeld as the character Sally Weaver. Famous for her signat', '2012-03-24 06:03:00', '2012-03-24 03:03:00', '2011-10-26 10:10:49', 1, 0.00, 'United States', 'Wilbur Theatre - MA', 'http://www.ticketmaster.com/event/010046B9851F70B8?camefrom=cfc_boswilbur_fl', '', ''),
(53, 8162210, 'Kathy Griffin', 'Event: Kathy Griffin<br>Venue: Wilbur Theatre - MA<br>Start: 3/23/2012 10:00:00 PM<br>Category: CONCERTS COMEDY', '2012-03-24 06:03:00', '2012-03-24 03:03:00', '2011-06-25 10:06:29', 1, 0.00, 'United States', 'Wilbur Theater', 'http://upcomingevents.net/search/redirect/1639064', '', ''),
(57, 8760463, 'Fiona Apple', 'Event: Fiona Apple<br>Venue: Royale Boston<br>Start: 3/27/2012 3:30:00 AM<br>Category: CONCERTS ALTERNATIVE', '2012-03-27 12:03:00', '2012-03-27 09:03:00', '2012-02-24 12:02:04', 1, 0.00, 'United States', 'Royale Boston', 'http://upcomingevents.net/search/redirect/1835659', 'Massachusetts', '02116'),
(56, 8635945, 'Needtobreathe', 'Event: Needtobreathe<br>Venue: Royal Oak Music Theatre<br>Start: 3/27/2012 3:30:00 AM<br>Category: CONCERTS POP / ROCK', '2012-03-27 12:03:00', '2012-03-27 09:03:00', '2012-01-07 13:01:03', 1, 0.00, 'United States', 'Royal Oak Music Theatre', 'http://upcomingevents.net/search/redirect/1775890', 'Michigan', '48067'),
(58, 8791734, '"The Boy, the Uncle, the Jews and the Monk: Norwich 1144 and Its Afterlives" - Miri Rubin, Queen Mar', 'Center for Medieval Studies Colloquium Series 1210 Heller Hall  Miri Rubin is a Professor of History at Queen Mary, University of London where her interests cover a wide range of social relations within the predominantly religious cultures of Europe between 1100-1600. She has found anthropological approaches and an understanding of textuality and visual imagery to be invaluable tools for the appreciation of the complex meanings of ritual, gender, power and community life. Some of her most recent', '2012-03-28 02:03:00', '2012-03-27 23:03:00', '2012-03-20 19:03:42', 1, 0.00, 'United States', 'Heller Hall, University of Minnesota', '', 'Minnesota', '55455'),
(59, 8715545, '7 in Heaven''s Singles FREE MINGLE Psychic Readings Karaoke', 'Starting at 5:30pm Held every last Tuesday of the month, this FREE group gathering has 50+ singles in attendance for this event. The BRICKYARD is a fun place located inside the Holiday Inn Hotel and easy to get to, a central location in Suffolk County. Happy hour drink specials and KARAOKE entertainment starts at 7pm. 7 in Heaven Singles Events  hosts Speed Dating Parties here the first SAT of the month in this hotel too! BONUS OPTIONAL ENTERTAINMENT -  Psychic Readings! available ($20 per readi', '2012-03-28 02:03:00', '2012-03-27 23:03:00', '2012-01-29 12:01:37', 1, 0.00, 'United States', 'Holiday Inn Ronkonkoma', '', 'New York', '11779'),
(60, 8768476, 'Download Zone Workshop', 'We will offer a Download Zone (DZ) audiobook and eBook class in the computer lab.  Learn how to use DZ to search for and place holds on audiobooks and eBooks.  The class will include a demonstration on how to download an eBook and audiobook from DZ.  You will also learn how to create a wish list in DZ.  Registration is required.  To register, stop by or call the Information Desk at 778-6451.', '2012-03-28 02:03:00', '2012-03-27 23:03:00', '2012-03-02 06:03:45', 1, 0.00, 'United States', 'Broome County Public Library', '', 'New York', '13901'),
(61, 8632326, 'Cooking Class - Asian Flavors of Thailand', 'Fun and informative cooking classes in Grand Haven. Click the link below for class description, information and registration.', '2012-03-28 03:03:00', '2012-03-28 00:03:00', '2012-01-04 12:01:47', 1, 0.00, 'United States', 'real cool cooking school at Bekins', '', 'Michigan', '49417'),
(62, 8611601, 'American Heart Association BLS Healthcare Provider Cpr Certification Class - Kansas City MO Metro Ar', 'BLS for Healthcare Providers Course Description: The Basic Life Support (BLS) for Healthcare Providers Course is designed to provide a wide variety of healthcare professionals the ability to recognize several life-threatening emergencies, provide CPR, use an AED, and relieve choking in a safe, timely and effective manner. Covers adult, child and infant modules.   Intended Audience: This is the class that is required for nurses, physicians, EMS professionals, students in a healthcare program at a', '2012-03-28 03:03:00', '2012-03-28 00:03:00', '2011-12-21 12:12:16', 1, 0.00, 'United States', 'Drury Inn & Suites Meeting Room', '', 'Kansas', '66202'),
(63, 8228978, 'Montreal Canadiens vs. Florida Panthers', 'Event: Montreal Canadiens vs. Florida Panthers<br>Venue: Centre Bell<br>Start: 3/27/2012 7:30:00 PM<br>Category: SPORTS HOCKEY', '2012-03-28 04:03:00', '2012-03-28 01:03:00', '2011-07-18 15:07:00', 1, 0.00, 'Canada', 'Bell Centre', 'http://upcomingevents.net/search/redirect/1672014', 'Quebec', 'H3B 5E8'),
(64, 8188701, 'Moscow Festival Ballet - Cinderella', 'Event: Moscow Festival Ballet - Cinderella<br>Venue: University At Buffalo Center For The Arts<br>Start: 3/27/2012 7:30:00 PM<br>Category: THEATRE BALLET', '2012-03-28 04:03:00', '2012-03-28 01:03:00', '2011-07-02 10:07:58', 1, 0.00, 'United States', 'University at Buffalo Center for the Arts', 'http://upcomingevents.net/search/redirect/1673661', 'New York', '14260'),
(65, 8504633, 'Jersey Boys', 'Event: Jersey Boys<br>Venue: Miller Auditorium - Western Michigan University<br>Start: 3/27/2012 7:30:00 PM<br>Category: THEATRE MUSICAL / PLAY', '2012-03-28 04:03:00', '2012-03-28 01:03:00', '2011-10-06 15:10:14', 1, 0.00, 'United States', 'Western Michigan University - James W Miller Auditorium', 'http://upcomingevents.net/search/redirect/1682623', 'Michigan', '49008'),
(66, 8202394, 'The Parchman Hour', 'Mike Wiley Productions presents a powerful account of the struggle of the â€œFreedom Riders.â€', '2012-03-28 04:03:00', '2012-03-28 01:03:00', '2011-07-07 14:07:38', 1, 0.00, 'United States', 'J.E. Broyhill Civic Center', 'http://www.broyhillcenter.com', 'North Carolina', '28645'),
(67, 8495164, '''Moulin Rouge - The Ballet'' presented by the Royal Winnipeg Ballet', 'Premiered in 2009, Moulin RougeÂ® - The Ballet is an original burlesque-inspired storybook ballet with irrepressible â€œGay Pareeâ€ style, razzle-dazzle dancing, eye-popping costumes, twinkling sets and a score made up of well-known French music. Along with a rousing soundtrack featuring Offenbach, Ravel, Strauss, Debussy and a dash of Piazzolla, the ballet features high-kicking choreography and a passionate story of love, ambition and heartbreak. â€œThe Royal Winnipeg Ballet is the first compa', '2012-03-28 04:03:00', '2012-03-28 01:03:00', '2011-09-28 12:09:09', 1, 0.00, 'United States', 'Center College - Norton Center for the Arts', 'http://www.nortoncenter.com/upcoming-schedule/1-the-newlin-hall-series/207-moulin-rouge-the-ballet', 'Kentucky', '40422'),
(68, 8719095, 'Mac Miller', 'Event: Mac Miller<br>Venue: Scope<br>Start: 3/27/2012 7:30:00 PM<br>Category: CONCERTS ALTERNATIVE', '2012-03-28 04:03:00', '2012-03-28 01:03:00', '2012-01-31 10:01:18', 1, 0.00, 'United States', 'Scope Arena', 'http://upcomingevents.net/search/redirect/1814696', 'Virginia', '23510'),
(69, 8162625, 'Million Dollar Quartet', 'Event: Million Dollar Quartet<br>Venue: State Theatre - MN<br>Start: 3/27/2012 7:30:00 PM<br>Category: THEATRE MUSICAL / PLAY', '2012-03-28 05:03:00', '2012-03-28 02:03:00', '2011-06-25 12:06:16', 1, 0.00, 'United States', 'Brave New Workshop Comedy Theatre', 'http://upcomingevents.net/search/redirect/1584498', 'Minnesota', '55408'),
(70, 8758218, 'PopGun Presents... Big Scary, NewVillager, The Echo Friendly, Elvis Depressedly', '21+', '2012-03-28 05:03:00', '2012-03-28 02:03:00', '2012-02-22 09:02:47', 1, 0.00, 'United States', 'Glassland Gallery', '', 'New York', '11122'),
(71, 8765522, 'Jim Lauletta''s Comedy Showcase @ Dick''s Beantown Comedy Vault', 'A favorite act wherever he goes, Jim mixes his cynical, biting, self-deprecating material with some of the most off the wall off the wall and original impressions you''ll EVER see. Quoted by the Boston Globe as "...off-beat and original.". Not only is Jim one of the most popular headliners in New England, he''s a regular performer in Las Vegas and Atlantic City! Jim also has an impressive resume of national television credits which include: HBO''s U.S. Comedy Arts Festival''s "Best of the Fest", NBC', '2012-03-28 05:03:00', '2012-03-28 02:03:00', '2012-02-29 16:02:54', 1, 0.00, 'United States', 'Dick''s Beantown Comedy Vault at Remington''s Restaurant', 'http://www.beantowncomedy.com', 'Massachusetts', '02116'),
(72, 8479992, 'Bring It On', 'Event: Bring It On<br>Venue: Fabulous Fox Theatre - MO<br>Start: 3/27/2012 8:00:00 PM<br>Category: THEATRE MUSICAL / PLAY', '2012-03-28 06:03:00', '2012-03-28 03:03:00', '2011-09-16 13:09:34', 1, 0.00, 'United States', 'The Fabulous Fox Theatre', 'http://upcomingevents.net/search/redirect/1626529', 'Missouri', '63103');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `YQL_event_address`
--

INSERT INTO `YQL_event_address` (`address_id`, `event_id`, `address_text`, `x_coord`, `y_coord`) VALUES
(42, 51, '227 Maple Ave E', 38.903900, -77.262199),
(43, 52, 'Boston, MA', 38.817501, -77.268204),
(44, 53, '246 Tremont St', 42.350300, -71.065002),
(49, 58, '271 19th Avenue S', 44.972099, -93.245697),
(47, 56, '318 W. 4th St.', 42.487301, -83.146896),
(48, 57, '279 Tremont St', 42.349800, -71.065102),
(50, 59, '3845 Veterans Memorial Highway', 40.784698, -73.109596),
(51, 60, '185 Court St', 42.100101, -75.906097),
(52, 61, '735 Washington Ave', 43.061298, -86.222298),
(53, 62, '9009 Shawnee Mission Parkway', 39.014198, -94.689697),
(54, 63, '1260 De La Gauchetiere Ouest', 45.506699, -73.569702),
(55, 64, '103 Center For The Arts', 43.000000, -78.783302),
(56, 65, '1903 W Michigan Ave', 42.284000, -85.612900),
(57, 66, '1913 Hickory Blvd', 35.880600, -81.513000),
(58, 67, '600 W Walnut St', 37.645000, -84.777397),
(59, 68, '201 E Brambleton Ave', 36.854000, -76.287300),
(60, 69, '2605 Hennepin Avenue South', 44.965302, -93.296898),
(61, 70, '289 Kent Ave', 40.724800, -73.966698),
(62, 71, '124 Boylston St', 42.352402, -71.065804),
(63, 72, '527 N. Grand Blvd.', 38.638802, -90.231697);

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
