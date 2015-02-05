-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 05-Fev-2015 às 14:33
-- Versão do servidor: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `noscope`
--
DROP DATABASE IF EXISTS `noscope`;
CREATE DATABASE `noscope`;
USE `noscope`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `nick` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `group` int(1) NOT NULL DEFAULT '1',
  `datareg` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` datetime DEFAULT NULL,
  `banned` int(1) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `validado` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `accounts_has_games`
--

CREATE TABLE IF NOT EXISTS `accounts_has_games` (
  `accounts_id` int(11) NOT NULL,
  `games_id` int(11) NOT NULL,
  PRIMARY KEY (`accounts_id`,`games_id`),
  KEY `fk_accounts_has_games_games1_idx` (`games_id`),
  KEY `fk_accounts_has_games_accounts1_idx` (`accounts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `accounts_has_messages`
--

CREATE TABLE IF NOT EXISTS `accounts_has_messages` (
  `to_account` int(11) NOT NULL DEFAULT '0',
  `messages_id` int(11) NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`to_account`,`messages_id`),
  KEY `fk_accounts_has_messages_messages1_idx` (`messages_id`),
  KEY `fk_accounts_has_messages_accounts1_idx` (`to_account`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `accounts_options`
--

CREATE TABLE IF NOT EXISTS `accounts_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_id` int(11) NOT NULL,
  `bio` text,
  `show_email` tinyint(1) DEFAULT '1',
  `show_games` tinyint(1) DEFAULT '1',
  `show_friends` tinyint(1) DEFAULT '1',
  `facebook` varchar(45) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_accounts_options_accounts1_idx` (`accounts_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='	' AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adm_options`
--

CREATE TABLE IF NOT EXISTS `adm_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opcao` varchar(45) NOT NULL,
  `valor` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opcao_UNIQUE` (`opcao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `buy_history`
--

CREATE TABLE IF NOT EXISTS `buy_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `games_id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `copes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_buy_history_games1_idx` (`games_id`),
  KEY `fk_buy_history_accounts1_idx` (`accounts_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `seo_name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `news_id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comments_news1_idx` (`news_id`),
  KEY `fk_comments_accounts1_idx` (`accounts_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_acc` int(11) NOT NULL,
  `to_acc` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  `notifications_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_accounts_has_accounts_accounts2_idx` (`to_acc`),
  KEY `fk_accounts_has_accounts_accounts1_idx` (`from_acc`),
  KEY `fk_friends_notifications1_idx` (`notifications_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `desenvolvedor` int(11) DEFAULT NULL,
  `desc` text,
  `value` int(11) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aprovado` int(1) DEFAULT '-1',
  `publicado` int(1) DEFAULT '0',
  `ultima_versao` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_games_accounts1_idx` (`desenvolvedor`),
  KEY `fk_games_versoes1_idx` (`ultima_versao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `games_stats`
--

CREATE TABLE IF NOT EXISTS `games_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `views` int(11) DEFAULT '0',
  `copes` int(11) DEFAULT '0',
  `downloads` int(11) DEFAULT '0',
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_games_stats_games1_idx` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL,
  `value` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `visibility` int(1) NOT NULL,
  `onlyGuest` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `message` text,
  `sender_account` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_messages_accounts1_idx` (`sender_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `author_ip` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `category` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `seo_name` varchar(50) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `data` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `game_id` int(11) NOT NULL,
  `publicado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  KEY `category` (`category`),
  KEY `fk_news_games1_idx` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_acc` int(11) DEFAULT NULL,
  `to_acc` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference` varchar(255) DEFAULT '0',
  `type` enum('friend_request','win_points','buy_points','custom') DEFAULT NULL,
  `seen` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_accounts_has_accounts_accounts4_idx` (`to_acc`),
  KEY `fk_accounts_has_accounts_accounts3_idx` (`from_acc`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7920 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `points_transfer`
--

CREATE TABLE IF NOT EXISTS `points_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_id` int(11) NOT NULL,
  `points` int(11) DEFAULT NULL,
  `value` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `payment_type` enum('pagseguro','paypal') NOT NULL,
  `received` tinyint(1) DEFAULT '0',
  `aprovado` int(11) DEFAULT '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_points_transfer_accounts1_idx` (`accounts_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(255) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `users_ip` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `recover`
--

CREATE TABLE IF NOT EXISTS `recover` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `code` varchar(45) NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `data` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `validations`
--

CREATE TABLE IF NOT EXISTS `validations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `date_validated` datetime DEFAULT NULL,
  `accounts_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_validations_accounts1_idx` (`accounts_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `versions`
--

CREATE TABLE IF NOT EXISTS `versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `versao` varchar(45) NOT NULL,
  `dir_id` int(11) DEFAULT '1',
  `date` datetime NOT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `ext` varchar(45) NOT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_versoes_games1_idx` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `accounts_has_games`
--
ALTER TABLE `accounts_has_games`
  ADD CONSTRAINT `accounts_has_games_ibfk_1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_has_games_ibfk_2` FOREIGN KEY (`games_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `accounts_has_messages`
--
ALTER TABLE `accounts_has_messages`
  ADD CONSTRAINT `accounts_has_messages_ibfk_5` FOREIGN KEY (`to_account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `accounts_has_messages_ibfk_6` FOREIGN KEY (`messages_id`) REFERENCES `messages` (`id`);

--
-- Limitadores para a tabela `accounts_options`
--
ALTER TABLE `accounts_options`
  ADD CONSTRAINT `accounts_options_ibfk_1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `buy_history`
--
ALTER TABLE `buy_history`
  ADD CONSTRAINT `buy_history_ibfk_1` FOREIGN KEY (`games_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buy_history_accounts1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_accounts1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comments_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_3` FOREIGN KEY (`notifications_id`) REFERENCES `notifications` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `friends_ibfk_6` FOREIGN KEY (`from_acc`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friends_ibfk_7` FOREIGN KEY (`to_acc`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_games_accounts1` FOREIGN KEY (`desenvolvedor`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_games_versoes1` FOREIGN KEY (`ultima_versao`) REFERENCES `versions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `games_stats`
--
ALTER TABLE `games_stats`
  ADD CONSTRAINT `games_stats_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_accounts1` FOREIGN KEY (`sender_account`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_games1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_ibfk_2` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_accounts_has_accounts_accounts3` FOREIGN KEY (`from_acc`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`to_acc`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `points_transfer`
--
ALTER TABLE `points_transfer`
  ADD CONSTRAINT `fk_points_transfer_accounts1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `recover`
--
ALTER TABLE `recover`
  ADD CONSTRAINT `chave_estrangeira_recover_uid` FOREIGN KEY (`uid`) REFERENCES `accounts` (`id`);

--
-- Limitadores para a tabela `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `validations`
--
ALTER TABLE `validations`
  ADD CONSTRAINT `validations_ibfk_1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `versions`
--
ALTER TABLE `versions`
  ADD CONSTRAINT `versions_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
