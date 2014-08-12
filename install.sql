-- phpMyAdmin SQL Dump
-- By typcn
-- http://www.phpmyadmin.net
--
-- 主机: datacenter.lan.ty
-- 生成日期: 2014-08-12 13:51:05
-- 服务器版本: 5.5.16
-- PHP 版本: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- 表的结构 `tl_cron`
--

CREATE TABLE IF NOT EXISTS `tl_cron` (
  `id` int(11) NOT NULL,
  `cur` int(11) NOT NULL,
  `sum` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tl_cron`
--

INSERT INTO `tl_cron` (`id`, `cur`, `sum`) VALUES
(1, 2, 3);

-- --------------------------------------------------------

--
-- 表的结构 `tl_sid`
--

CREATE TABLE IF NOT EXISTS `tl_sid` (
  `uid` int(11) NOT NULL,
  `qq` int(13) NOT NULL,
  `sid` varchar(50) NOT NULL,
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tl_user`
--

CREATE TABLE IF NOT EXISTS `tl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL,
  `upass` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `lastip` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
