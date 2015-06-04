-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2015 at 11:02 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `joh19`
--

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS POLLS;
DROP TABLE IF EXISTS ANSWERS;
DROP TABLE IF EXISTS VOTES;

CREATE TABLE IF NOT EXISTS `POLLS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `question` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `ANSWERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pollId` int(11) NOT NULL,
  `optionNo` int(11) NOT NULL,
  `answer` varchar(512) NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `VOTES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pollId` int(11) NOT NULL,
  `answerId` int(11) NOT NULL,
  `ip` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Generate the first poll about php with four answers and three votes for 'There are people who aren't'
INSERT INTO POLLS (id, title, question) VALUES (1, "(in)sane", "Are you insane?");

INSERT INTO ANSWERS(id, pollId, optionNo, answer) VALUES (1,1,1,"No! (I'm just mildly mad)");
INSERT INTO ANSWERS(pollId, optionNo, answer) VALUES (1,2,"Without a doubt.");
INSERT INTO ANSWERS(pollId, optionNo, answer) VALUES (1,3,"Clearly");
INSERT INTO ANSWERS(id, pollId, optionNo, answer) VALUES (4,1,4,"There are people who aren't?");

-- Vote on the first poll (1 votes for No! (I'm just mildly mad) and 2 for there are people who aren't)
INSERT INTO VOTES(pollId, answerId, ip) VALUES (1, 1, "127.0.0.1");
INSERT INTO VOTES(pollId, answerId, ip) VALUES (1, 4, "127.0.0.1");
INSERT INTO VOTES(pollId, answerId, ip) VALUES (1, 4, "127.0.0.1");

-- Generate the second poll. Do you remember what you're doing here?
INSERT INTO POLLS (id, title, question) VALUES (2, "Of Course..", "What's this course?");

INSERT INTO ANSWERS(id, pollId, optionNo, answer) VALUES (5,2,1,"..uum...");
INSERT INTO ANSWERS(pollId, optionNo, answer) VALUES (2,2,"SENG ..eer something?");
INSERT INTO ANSWERS(pollId, optionNo, answer) VALUES (2,3,"One with programming and stuff?");

-- Vote on the second poll (3 votes for ...uum...)
INSERT INTO VOTES(pollId, answerId, ip) VALUES (2, 5, "127.0.0.1");
INSERT INTO VOTES(pollId, answerId, ip) VALUES (2, 5, "127.0.0.1");
INSERT INTO VOTES(pollId, answerId, ip) VALUES (2, 5, "127.0.0.1");

-- Generate the third poll. You must know the question
INSERT INTO POLLS (id, title, question) VALUES (3, "The question", "What... is the air-speed velocity of an unladen swallow?");

INSERT INTO ANSWERS(id, pollId, optionNo, answer) VALUES (8,3,1,"What do you mean? An African or European swallow?");
INSERT INTO ANSWERS(id, pollId, optionNo, answer) VALUES (9,3,2,"Huh? I... I don't know that.");
INSERT INTO ANSWERS(id, pollId, optionNo, answer) VALUES (10,3,3,"Blue");

-- Vote on the third poll (1 votes for What do you mean?, 1 for Huh? and 1 for Blue)
INSERT INTO VOTES(pollId, answerId, ip) VALUES (2, 8, "127.0.0.1");
INSERT INTO VOTES(pollId, answerId, ip) VALUES (2, 9, "127.0.0.1");
INSERT INTO VOTES(pollId, answerId, ip) VALUES (2, 10, "127.0.0.1");