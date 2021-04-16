-- MariaDB dump 10.18  Distrib 10.5.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: musicstream
-- ------------------------------------------------------
-- Server version	10.5.8-MariaDB-1:10.5.8+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(11) NOT NULL,
  `album_name` varchar(60) NOT NULL,
  PRIMARY KEY (`album_id`),
  UNIQUE KEY `album_id_UNIQUE` (`artist_id`),
  UNIQUE KEY `album_name_UNIQUE` (`album_name`),
  CONSTRAINT `artist_id` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `albums`
--

LOCK TABLES `albums` WRITE;
/*!40000 ALTER TABLE `albums` DISABLE KEYS */;
INSERT INTO `albums` VALUES (2,1,'Chap In The Recess'),(3,2,'The Misplaced Cassette'),(4,3,'Non Orthodox Sound System'),(5,4,'Pinniped'),(6,5,'Daybreak Triumph'),(7,6,'Thomas'),(8,7,'The Boulder Perennials'),(9,8,'What People Claim Aboout Me Is Incorrect'),(10,9,'Entity'),(11,10,'Get Abraded');
/*!40000 ALTER TABLE `albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artists`
--

DROP TABLE IF EXISTS `artists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artists` (
  `artist_id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_name` varchar(60) NOT NULL,
  PRIMARY KEY (`artist_id`),
  UNIQUE KEY `artist_name_UNIQUE` (`artist_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artists`
--

LOCK TABLES `artists` WRITE;
/*!40000 ALTER TABLE `artists` DISABLE KEYS */;
INSERT INTO `artists` VALUES (2,'50 Pence Piece'),(10,'Deceased Rod3nt'),(1,'Disoriented Scoundrel'),(3,'Frank Saturn'),(9,'Old Chaos'),(4,'Pinniped'),(8,'Polar Primates'),(7,'The Boulder Perennials'),(6,'The What'),(5,'Watering Hole');
/*!40000 ALTER TABLE `artists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`offer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offers`
--

LOCK TABLES `offers` WRITE;
/*!40000 ALTER TABLE `offers` DISABLE KEYS */;
INSERT INTO `offers` VALUES (1,'GOLD Level','Subscribe to our gold level subscription and get unlimited access to our entire catalogue',4.99,'images/offers/GoldOffer.png'),(2,'SILVER Level','Silver level subscribers receieve unlimited usage with ad support',2.99,'images/offers/SilverOffer.png'),(3,'PLATINUM Level','Stream unlimited music on unlimited devices (non concurrently) registered with the same user account',6.99,'images/offers/PlatinumOffer.png'),(4,'FAMILY Pack','Up to 4 separate GOLD user accounts in one pack, all accounts can run concurrently',9.99,'images/offers/FamilyOffer.png');
/*!40000 ALTER TABLE `offers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playlist_entries`
--

DROP TABLE IF EXISTS `playlist_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlist_entries` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `playlist_idx` (`playlist_id`),
  KEY `track_instance_idx` (`track_id`),
  CONSTRAINT `parent_playlist` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`playlist_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `track_instance` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`track_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=730 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playlist_entries`
--

LOCK TABLES `playlist_entries` WRITE;
/*!40000 ALTER TABLE `playlist_entries` DISABLE KEYS */;
INSERT INTO `playlist_entries` VALUES (708,64,65),(709,64,59),(710,64,8),(711,64,32),(712,64,80),(713,64,23),(714,64,101),(715,62,6),(716,64,6);
/*!40000 ALTER TABLE `playlist_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `playlists`
--

DROP TABLE IF EXISTS `playlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlists` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `playlist_name` varchar(255) DEFAULT NULL,
  `public` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`playlist_id`),
  KEY `playlist_owner_idx` (`owner_id`),
  CONSTRAINT `playlist_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `playlists`
--

LOCK TABLES `playlists` WRITE;
/*!40000 ALTER TABLE `playlists` DISABLE KEYS */;
INSERT INTO `playlists` VALUES (62,896120299,'My Favourite Tracks',0),(64,896120299,'Random Tracks',1);
/*!40000 ALTER TABLE `playlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recommendations`
--

DROP TABLE IF EXISTS `recommendations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recommendations` (
  `recomm_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  PRIMARY KEY (`recomm_id`),
  UNIQUE KEY `recomm_id_UNIQUE` (`recomm_id`),
  KEY `user_idx` (`user_id`),
  KEY `track_idx` (`track_id`),
  CONSTRAINT `recomm_track` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`track_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recommendations`
--

LOCK TABLES `recommendations` WRITE;
/*!40000 ALTER TABLE `recommendations` DISABLE KEYS */;
/*!40000 ALTER TABLE `recommendations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `track_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(255) NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `track_idx` (`track_id`),
  KEY `author_idx` (`author_id`),
  CONSTRAINT `author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `track` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`track_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (18,1,851269135,8,'Love this Song!! Wicked Bassline'),(19,61,851269135,10,'Love Watering Hole, this is by far the most epic track on the album, what a closer!'),(34,1,896120299,1,'Hate this track, it\'s absolute garbage, how can anyone listen to this guff?');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracks`
--

DROP TABLE IF EXISTS `tracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracks` (
  `track_id` int(11) NOT NULL,
  `artist_id` int(11) DEFAULT NULL,
  `album_id` int(11) DEFAULT NULL,
  `track_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `sample` varchar(255) DEFAULT NULL,
  `genre` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`track_id`),
  KEY `artist_idx` (`artist_id`),
  KEY `album_idx` (`album_id`),
  CONSTRAINT `album` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `artist` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracks`
--

LOCK TABLES `tracks` WRITE;
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;
INSERT INTO `tracks` VALUES (1,1,2,'Taking A Seat','1 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(2,1,2,'Cease, Fourthwith','2 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(3,1,2,'I Love You','3 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(4,1,2,'Fresh Morrow','4 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(5,1,2,'Excessive Distance','5 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(6,1,2,'Repair it and Look G','6 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(7,1,2,'Remove them','7 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(8,1,2,'Bite your Lip','8 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(9,1,2,'Repeating Process','9 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(10,1,2,'Simply a Scoundrel','10 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(11,1,2,'Upon What Are You Pl','11 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(12,1,2,'Tart','12 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(13,1,2,'Appears So','13 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(14,1,2,'Survive O','14 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(15,1,2,'Proceed','15 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/chapintherecess.jpg','images/thumbs/chapintherecessTM.jpg','samples/chapintherecess.mp3','Rap'),(16,2,3,'Occupy Your Time','16 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/chapintherecess.mp3','Rap'),(17,2,3,'Multiply by 2','17 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(18,2,3,'Slay One','18 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(19,2,3,'Fracas','19 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(20,2,3,'Orange Juice','20 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(21,2,3,'I Will Tell The Trut','21 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(22,2,3,'Complex','22 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(23,2,3,'Impressive Slayer','23 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(24,2,3,'Dont Panic!','24 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(25,2,3,'I Require Assistance','25 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(26,2,3,'When I Open The Boot','26 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(27,2,3,'Cosmic Body 50','27 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(28,2,3,'Loot Straight','28 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(29,2,3,'Lie Flat (Smoked)','29 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(30,2,3,'All His Affection','30 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/themisplacedcassette.jpg','images/thumbs/themisplacedcassetteTM.jpg','samples/themisplacedcassette.mp3','Rap'),(31,3,4,'Adolescent Females','31 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(32,3,4,'Celestial Banishment','32 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(33,3,4,' Hominoidea','33 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(34,3,4,'Valuables','34 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(35,3,4,'Homebrew','35 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(36,3,4,'When I Were Yours','36 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(37,3,4,'Portman','37 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(38,3,4,'Demonstrate','38 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(39,3,4,'Cash Pleases Her','39 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(40,3,4,'Had I Been Aware','40 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/non-orthodoxsoundsystem.jpg','images/thumbs/non-orthodoxsoundsystemTM.jpg','samples/non-orthodoxsoundsystem.mp3','R and B'),(41,4,5,'The Start','41 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(42,4,5,'Deep Dihydrogen Oxide','42 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(43,4,5,'Lunatic','43 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(44,4,5,'Assassin','44 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(45,4,5,'Vortex','45 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(46,4,5,'FLP','46 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(47,4,5,'Feral','47 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(48,4,5,'Demonstrate','48 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(49,4,5,'Lavender','49 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/pinniped.jpg','images/thumbs/pinnipedTM.jpg','samples/pinniped.mp3','R and B'),(50,5,6,'Greetings','50 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(51,5,6,'Rotate With It','51 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(52,5,6,'Fascinating Barrier','52 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(53,5,6,'Don\'t Reflect in Acrimony','53 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(54,5,6,'Hey, At This Moment','54 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(55,5,6,'Untitled','55 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(56,5,6,'A Number May Affirm ','56 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(57,5,6,'Make No Shade','57 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(58,5,6,'She\'s Charged','58 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(59,5,6,'Daybreak Triumph','59 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(60,5,6,'Untited','60 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(61,5,6,'Prosecco Stellar Explosion','61 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/daybreaktriumph.jpg','images/thumbs/daybreaktriumphTM.jpg','samples/daybreaktriumph.mp3','Rock'),(62,7,8,'I Desire Esteem','62 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(63,7,8,'She Beats The Gong','63 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(64,7,8,'Cascade','64 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(65,7,8,'Continue','65 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(66,7,8,'Farewell Antagonist','66 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(67,7,8,'Lilly My Darling','67 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(68,7,8,'Dextrose Rotated Sibling','68 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(69,7,8,'Of Boulder Construction','69 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(70,7,8,'Blast you Out','70 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(71,7,8,'This Is It','71 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(72,7,8,'I\'m The Reawakening','72 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/theboulderperennials.jpg','images/thumbs/theboulderperennialsTM.jpg','samples/theboulderperennials.mp3','Indie'),(73,6,7,'Prelude','73 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(74,6,7,'It\'s A Girl','74 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(75,6,7,'2119','75 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(76,6,7,'Splendid Outing','76 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(77,6,7,'Embers','77 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(78,6,7,'The Peddler','78 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(79,6,7,'Noel','79 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(80,6,7,'Nephew Keith','80 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(81,6,7,'The Alkaline King','81 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(82,6,7,'Epilogue','82 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/thomas.jpg','images/thumbs/thomasTM.jpg','samples/thomas.mp3','Rock'),(83,8,9,'The Aspect At Noon','83 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(84,8,9,'I\'d Wager Your An Attractive Dancer','84 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(85,8,9,'Bogus Anecdotes in California','85 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(86,8,9,'Boppin Boots','86 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(87,8,9,'Lookin At Me In The Dark','87 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(88,8,9,'Take You Home Regardless','88 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(89,8,9,'Commotion Car','89 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(90,8,9,'Lock Indicator','90 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(91,8,9,'Grumpy Rump','91 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(92,8,9,'Undead Is  Forceful, However..','92 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(93,8,9,'At Sunset','93 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(94,8,9,'From Claridges to Crumbs','94 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(95,8,9,'A Definite Courtship','95 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/whatpeopleclaimaboutmeisincorrect.jpg','images/thumbs/whatpeopleclaimaboutmeisincorrectTM.jpg','samples/whatpeopleclaimaboutmeisincorrect.mp3','Indie'),(96,9,10,'Ritual','96 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(97,9,10,'It\'s All Gone Blue','97 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(98,9,10,'Allurement','98 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(99,9,10,'Green Tuesday','99 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(100,9,10,'Befuddling','100 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(101,9,10,'Bandits Like Ourselves','101 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(102,9,10,'The Flawless Smooch','102 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(103,9,10,'Under Civilization','103 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(104,9,10,'Carapace Fright','104 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(105,9,10,'Condition of the Country','105 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(106,9,10,'Strange Amourous Square','106 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(107,9,10,'Correct Belief','107 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/entity.jpg','images/thumbs/entityTM.jpg','samples/entity.mp3','Dance'),(108,10,11,'8 Binary Digits','108 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(109,10,11,'Disinterested in Canada','109 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(110,10,11,'Disregardful','110 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(111,10,11,'Tatsletni','111 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(112,10,11,'Glaring Television','112 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(113,10,11,'Repeat Attempt','113 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(114,10,11,'Change Your Acquaintances','114 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(115,10,11,'I Do Not Recall','115 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(116,10,11,'Memo From The Void','116 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(117,10,11,'Debt','117 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(118,10,11,'Contentedness','118 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(119,10,11,'On Occasion im Unsucessful','119 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(120,10,11,'Sustain','120 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(121,10,11,'The Awahso Relation','121 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance'),(122,10,11,'The Standard Things','122 Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','images/getabraded.jpg','images/thumbs/getabradedTM.jpg','samples/getabraded.mp3','Dance');
/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `offer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `offer_id_idx` (`offer_id`),
  CONSTRAINT `offer_id` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (354534971,'Bob','$2y$10$U9vzbs0L1Nw5IRxiDsF/M.md2o3YbiGYs91L119tPTbXPrXQTRLw2',4),(851269135,'Brendan','$2y$10$2SLZhPggtRnwJfbFaLpulOFBJkwlkE6eckjXqyBUhlex6n0nE24gC',1),(896120299,'Bobby','$2y$10$x4TfvkSUaCVtPObn1R3UL.uHOmS1d300gHiTiE3/Niz7MJXLzFc4K',1),(1617779988,'DemoUser','$2y$10$X6d/zpruLepLx/Zm3mR76O2IWYkxeKztilGXwPbOAlj/trJSPIHS6',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-16 14:59:13
