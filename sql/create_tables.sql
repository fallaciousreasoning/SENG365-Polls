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

CREATE TABLE IF NOT EXISTS `polls` (
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
  `questionId` int(11) NOT NULL,
  `optionNo` int(11) NOT NULL,
  `answer` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `VOTES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `answerId` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Generate the first poll about php with four answers and three votes for 'There are people who aren't'
INSERT INTO POLLS (id, title, question) VALUES (1, "(in)sane", "Are you insane?");

INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,1,"Yes");
INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,2,"Without a doubt.");
INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,3,"Clearly");
INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,4,"There are people who aren't?");

INSERT INTO VOTES(answerId, ip) VALUES (4, "127.0.0.1");
INSERT INTO VOTES(answerId, ip) VALUES (4, "127.0.0.1");
INSERT INTO VOTES(answerId, ip) VALUES (4, "127.0.0.1");

-- Generate the second poll. You can only love php
INSERT INTO POLLS (id, title, question) VALUES (2, "PHP", "How much do you love PHP?");

INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,1,"..uum... Do I have to answer?");
INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,2,"...");
INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,3,"It's alright, I suppose.");
INSERT INTO ANSWERS(questionId, optionNo, answer) VALUES (1,4,"NO.");

INSERT INTO VOTES(answerId, ip) VALUES (5, "127.0.0.1");
INSERT INTO VOTES(answerId, ip) VALUES (6, "127.0.0.1");
INSERT INTO VOTES(answerId, ip) VALUES (7, "127.0.0.1");