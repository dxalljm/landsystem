-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 07 月 06 日 08:44
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `landsystem`
--
CREATE DATABASE IF NOT EXISTS `landsystem` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `landsystem`;

-- --------------------------------------------------------

--
-- 表的结构 `land_auth_assignment`
--

CREATE TABLE IF NOT EXISTS `land_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `land_auth_assignment`
--

INSERT INTO `land_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('业务管理员', '2', 1431172584),
('作物管理', '2', 1432003197),
('区域管理', '2', 1431996360),
('地产科', '4', 1432003386),
('系统维护员', '1', 1431873248),
('财务科', '5', 1434293633);

-- --------------------------------------------------------

--
-- 表的结构 `land_auth_item`
--

CREATE TABLE IF NOT EXISTS `land_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `land_auth_item`
--

INSERT INTO `land_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('assignmentindex', 2, NULL, NULL, NULL, 1431873290, 1431873290),
('bankaccountcreate', 2, NULL, NULL, NULL, 1434367059, 1434367059),
('bankaccountdelete', 2, NULL, NULL, NULL, 1434367077, 1434367077),
('bankaccountindex', 2, NULL, NULL, NULL, 1434367052, 1434367052),
('bankaccountupdate', 2, NULL, NULL, NULL, 1434367066, 1434367066),
('bankaccountview', 2, NULL, NULL, NULL, 1434367071, 1434367071),
('businessindex', 2, NULL, NULL, NULL, 1432189258, 1432189258),
('collectioncreate', 2, NULL, NULL, NULL, 1434292879, 1434292879),
('collectiondelete', 2, NULL, NULL, NULL, 1434292900, 1434292900),
('collectionfarmerlist', 2, NULL, NULL, NULL, 1434609018, 1434609018),
('collectionindex', 2, NULL, NULL, NULL, 1434292872, 1434292872),
('collectionupdate', 2, NULL, NULL, NULL, 1434292886, 1434292886),
('collectionview', 2, NULL, NULL, NULL, 1434292891, 1434292891),
('collectionyears', 2, NULL, NULL, NULL, 1434609008, 1434609008),
('farmercontract', 2, NULL, NULL, NULL, 1434940113, 1434940113),
('farmercreate', 2, NULL, NULL, NULL, 1434851813, 1434851813),
('farmsbusiness', 2, NULL, NULL, NULL, 1434086405, 1434086405),
('farmscreate', 2, NULL, NULL, NULL, 1431822981, 1431822981),
('farmsdelete', 2, NULL, NULL, NULL, 1431822999, 1431822999),
('farmsindex', 2, NULL, NULL, NULL, 1431823019, 1431823019),
('farmsmenu', 2, NULL, NULL, NULL, 1434085549, 1434085549),
('farmsupdate', 2, NULL, NULL, NULL, 1431822988, 1431822988),
('farmsview', 2, NULL, NULL, NULL, 1431822994, 1431822994),
('goodseedcreate', 2, NULL, NULL, NULL, 1435154375, 1435154375),
('goodseeddelete', 2, NULL, NULL, NULL, 1435154392, 1435154392),
('goodseedindex', 2, NULL, NULL, NULL, 1435154370, 1435154370),
('goodseedupdate', 2, NULL, NULL, NULL, 1435154380, 1435154380),
('goodseedview', 2, NULL, NULL, NULL, 1435154387, 1435154387),
('Inputproductcreate', 2, NULL, NULL, NULL, 1435154341, 1435154341),
('Inputproductdelete', 2, NULL, NULL, NULL, 1435154356, 1435154356),
('Inputproductindex', 2, NULL, NULL, NULL, 1435154336, 1435154336),
('Inputproductupdate', 2, NULL, NULL, NULL, 1435154348, 1435154348),
('Inputproductview', 2, NULL, NULL, NULL, 1435154353, 1435154353),
('leasecreate', 2, NULL, NULL, NULL, 1433775524, 1433775524),
('leasedelete', 2, NULL, NULL, NULL, 1433775803, 1433775803),
('leaseindex', 2, NULL, NULL, NULL, 1433775518, 1433775518),
('leaseupdate', 2, NULL, NULL, NULL, 1433775532, 1433775532),
('leaseview', 2, NULL, NULL, NULL, 1433775542, 1433775542),
('mainmenucreate', 2, NULL, NULL, NULL, 1431861792, 1431861792),
('mainmenudelete', 2, NULL, NULL, NULL, 1431861836, 1431861836),
('mainmenuindex', 2, NULL, NULL, NULL, 1431861825, 1431861825),
('mainmenuupdate', 2, NULL, NULL, NULL, 1431861800, 1431861811),
('mainmenuview', 2, NULL, NULL, NULL, 1431861820, 1431861820),
('managementareacreate', 2, NULL, NULL, NULL, 1431996317, 1431996317),
('managementareadelete', 2, NULL, NULL, NULL, 1431996331, 1431996331),
('managementareaindex', 2, NULL, NULL, NULL, 1431996309, 1431996309),
('managementareaupdate', 2, NULL, NULL, NULL, 1431996326, 1431996326),
('managementareaview', 2, NULL, NULL, NULL, 1431996337, 1431996337),
('menutousercreate', 2, NULL, NULL, NULL, 1431861861, 1431861861),
('menutouserdelete', 2, NULL, NULL, NULL, 1431861888, 1431861888),
('menutouserindex', 2, NULL, NULL, NULL, 1431861901, 1431861901),
('menutouserupdate', 2, NULL, NULL, NULL, 1431861871, 1431861871),
('menutouserview', 2, NULL, NULL, NULL, 1431861878, 1431861878),
('nationcreate', 2, NULL, NULL, NULL, 1433567636, 1433567636),
('nationdelete', 2, NULL, NULL, NULL, 1433567662, 1433567662),
('nationindex', 2, NULL, NULL, NULL, 1433567626, 1433567626),
('nationupdate', 2, NULL, NULL, NULL, 1433567645, 1433567645),
('nationview', 2, NULL, NULL, NULL, 1433567653, 1433567653),
('permissioncreate', 2, NULL, NULL, NULL, 1431181461, 1431181461),
('permissiondelete', 2, NULL, NULL, NULL, 1431181480, 1431181480),
('permissionindex', 2, NULL, NULL, NULL, 1431181346, 1431181346),
('permissionupdate', 2, NULL, NULL, NULL, 1431181471, 1431181471),
('permissionview', 2, NULL, NULL, NULL, 1431181541, 1431181541),
('pesticidescreate', 2, NULL, NULL, NULL, 1435154303, 1435154303),
('pesticidesdelete', 2, NULL, NULL, NULL, 1435154319, 1435154319),
('pesticidesindex', 2, NULL, NULL, NULL, 1435154296, 1435154296),
('pesticidesupdate', 2, NULL, NULL, NULL, 1435154308, 1435154308),
('pesticidesview', 2, NULL, NULL, NULL, 1435154314, 1435154314),
('plantcreate', 2, NULL, NULL, NULL, 1432003114, 1432003114),
('plantdelete', 2, NULL, NULL, NULL, 1432003140, 1432003140),
('plantindex', 2, NULL, NULL, NULL, 1432003145, 1432003145),
('plantpricecreate', 2, NULL, NULL, NULL, 1434293456, 1434293456),
('plantpricedelete', 2, NULL, NULL, NULL, 1434293474, 1434293474),
('plantpriceindex', 2, NULL, NULL, NULL, 1434293447, 1434293447),
('plantpriceupdate', 2, NULL, NULL, NULL, 1434293461, 1434293461),
('plantpriceview', 2, NULL, NULL, NULL, 1434293465, 1434293465),
('plantupdate', 2, NULL, NULL, NULL, 1432003125, 1432003125),
('plantview', 2, NULL, NULL, NULL, 1432003133, 1432003133),
('roleaddchild', 2, NULL, NULL, NULL, 1431873323, 1431873323),
('rolecreate', 2, NULL, NULL, NULL, 1431181307, 1431181307),
('roledelete', 2, NULL, NULL, NULL, 1431181319, 1431181319),
('roleindex', 2, NULL, NULL, NULL, 1431181298, 1431181298),
('roleupdate', 2, NULL, NULL, NULL, 1431181313, 1431181313),
('roleview', 2, NULL, NULL, NULL, 1431181533, 1431181533),
('tablefieldscreate', 2, NULL, NULL, NULL, 1431181221, 1431181221),
('tablefieldsdelete', 2, NULL, NULL, NULL, 1431181290, 1431181290),
('tablefieldsindex', 2, NULL, NULL, NULL, 1431181212, 1431181212),
('tablefieldsupdate', 2, NULL, NULL, NULL, 1431181232, 1431181232),
('tablefieldsview', 2, NULL, NULL, NULL, 1431181527, 1431181527),
('tablescreate', 2, NULL, NULL, NULL, 1431181159, 1431181159),
('tablesdelete', 2, NULL, NULL, NULL, 1431181205, 1431181205),
('tablesindex', 2, NULL, NULL, NULL, 1431181151, 1431181151),
('tablesupdate', 2, NULL, NULL, NULL, 1431181186, 1431181186),
('tablesview', 2, NULL, NULL, NULL, 1431181516, 1431181516),
('theyearupdate', 2, NULL, NULL, NULL, 1435560729, 1435560729),
('usercreate', 2, NULL, NULL, NULL, 1431173328, 1431175292),
('userdelete', 2, NULL, NULL, NULL, 1431181137, 1431181137),
('userindex', 2, NULL, NULL, NULL, 1431173315, 1431175302),
('userupdate', 2, NULL, NULL, NULL, 1431177310, 1431177310),
('userview', 2, NULL, NULL, NULL, 1431181508, 1431181508),
('业务管理员', 1, NULL, NULL, NULL, 1431158539, 1431158539),
('作物管理', 1, NULL, NULL, NULL, 1432003168, 1432003168),
('农场管理', 1, NULL, NULL, NULL, 1431822947, 1431822947),
('区域管理', 1, NULL, NULL, NULL, 1431996349, 1431996349),
('地产科', 1, NULL, NULL, NULL, 1432003339, 1432003339),
('导航菜单管理', 1, NULL, NULL, NULL, 1431870907, 1431870907),
('数据库管理', 1, NULL, NULL, NULL, 1431861694, 1431861694),
('用户管理', 1, NULL, NULL, NULL, 1431181654, 1431181654),
('租赁管理', 1, NULL, NULL, NULL, 1433775849, 1433775849),
('系统维护员', 1, NULL, NULL, NULL, 1431868208, 1431868208),
('角色权限管理', 1, NULL, NULL, NULL, 1431870934, 1431870934),
('财务科', 1, NULL, NULL, NULL, 1434292910, 1434292910);

-- --------------------------------------------------------

--
-- 表的结构 `land_auth_item_child`
--

CREATE TABLE IF NOT EXISTS `land_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `land_auth_item_child`
--

INSERT INTO `land_auth_item_child` (`parent`, `child`) VALUES
('系统维护员', 'assignmentindex'),
('财务科', 'bankaccountcreate'),
('财务科', 'bankaccountdelete'),
('财务科', 'bankaccountindex'),
('财务科', 'bankaccountupdate'),
('财务科', 'bankaccountview'),
('地产科', 'businessindex'),
('系统维护员', 'businessindex'),
('财务科', 'collectioncreate'),
('财务科', 'collectiondelete'),
('财务科', 'collectionfarmerlist'),
('财务科', 'collectionindex'),
('财务科', 'collectionupdate'),
('财务科', 'collectionview'),
('财务科', 'collectionyears'),
('地产科', 'farmercontract'),
('财务科', 'farmercontract'),
('地产科', 'farmercreate'),
('地产科', 'farmsbusiness'),
('农场管理', 'farmscreate'),
('地产科', 'farmscreate'),
('农场管理', 'farmsdelete'),
('地产科', 'farmsdelete'),
('农场管理', 'farmsindex'),
('地产科', 'farmsindex'),
('地产科', 'farmsmenu'),
('农场管理', 'farmsupdate'),
('地产科', 'farmsupdate'),
('农场管理', 'farmsview'),
('地产科', 'farmsview'),
('地产科', 'goodseedcreate'),
('地产科', 'goodseeddelete'),
('地产科', 'goodseedindex'),
('地产科', 'goodseedupdate'),
('地产科', 'goodseedview'),
('地产科', 'Inputproductcreate'),
('地产科', 'Inputproductdelete'),
('地产科', 'Inputproductindex'),
('地产科', 'Inputproductupdate'),
('地产科', 'Inputproductview'),
('租赁管理', 'leasecreate'),
('租赁管理', 'leasedelete'),
('租赁管理', 'leaseindex'),
('租赁管理', 'leaseupdate'),
('租赁管理', 'leaseview'),
('导航菜单管理', 'mainmenucreate'),
('导航菜单管理', 'mainmenudelete'),
('导航菜单管理', 'mainmenuindex'),
('导航菜单管理', 'mainmenuupdate'),
('导航菜单管理', 'mainmenuview'),
('区域管理', 'managementareacreate'),
('地产科', 'managementareacreate'),
('区域管理', 'managementareadelete'),
('地产科', 'managementareadelete'),
('区域管理', 'managementareaindex'),
('地产科', 'managementareaindex'),
('区域管理', 'managementareaupdate'),
('地产科', 'managementareaupdate'),
('区域管理', 'managementareaview'),
('地产科', 'managementareaview'),
('导航菜单管理', 'menutousercreate'),
('导航菜单管理', 'menutouserdelete'),
('导航菜单管理', 'menutouserindex'),
('导航菜单管理', 'menutouserupdate'),
('导航菜单管理', 'menutouserview'),
('地产科', 'nationcreate'),
('地产科', 'nationdelete'),
('地产科', 'nationindex'),
('地产科', 'nationupdate'),
('地产科', 'nationview'),
('角色权限管理', 'permissioncreate'),
('角色权限管理', 'permissiondelete'),
('角色权限管理', 'permissionindex'),
('角色权限管理', 'permissionupdate'),
('角色权限管理', 'permissionview'),
('地产科', 'pesticidescreate'),
('地产科', 'pesticidesdelete'),
('地产科', 'pesticidesindex'),
('地产科', 'pesticidesupdate'),
('地产科', 'pesticidesview'),
('作物管理', 'plantcreate'),
('地产科', 'plantcreate'),
('作物管理', 'plantdelete'),
('地产科', 'plantdelete'),
('作物管理', 'plantindex'),
('地产科', 'plantindex'),
('财务科', 'plantpricecreate'),
('财务科', 'plantpricedelete'),
('财务科', 'plantpriceindex'),
('财务科', 'plantpriceupdate'),
('财务科', 'plantpriceview'),
('作物管理', 'plantupdate'),
('地产科', 'plantupdate'),
('作物管理', 'plantview'),
('地产科', 'plantview'),
('角色权限管理', 'roleaddchild'),
('角色权限管理', 'rolecreate'),
('角色权限管理', 'roledelete'),
('角色权限管理', 'roleindex'),
('角色权限管理', 'roleupdate'),
('角色权限管理', 'roleview'),
('数据库管理', 'tablefieldscreate'),
('数据库管理', 'tablefieldsdelete'),
('数据库管理', 'tablefieldsindex'),
('数据库管理', 'tablefieldsupdate'),
('数据库管理', 'tablefieldsview'),
('数据库管理', 'tablescreate'),
('数据库管理', 'tablesdelete'),
('数据库管理', 'tablesindex'),
('数据库管理', 'tablesupdate'),
('数据库管理', 'tablesview'),
('地产科', 'theyearupdate'),
('用户管理', 'usercreate'),
('用户管理', 'userdelete'),
('用户管理', 'userindex'),
('用户管理', 'userupdate'),
('用户管理', 'userview'),
('业务管理员', '农场管理'),
('系统维护员', '导航菜单管理'),
('系统维护员', '数据库管理'),
('系统维护员', '用户管理'),
('地产科', '租赁管理'),
('系统维护员', '角色权限管理');

-- --------------------------------------------------------

--
-- 表的结构 `land_auth_rule`
--

CREATE TABLE IF NOT EXISTS `land_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `land_bank_account`
--

CREATE TABLE IF NOT EXISTS `land_bank_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountnumber` varchar(500) DEFAULT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `bank` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `land_bank_account`
--

INSERT INTO `land_bank_account` (`id`, `accountnumber`, `farmer_id`, `bank`) VALUES
(1, '1234567890', 2, '工商银行');

-- --------------------------------------------------------

--
-- 表的结构 `land_collection`
--

CREATE TABLE IF NOT EXISTS `land_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payyear` int(11) DEFAULT NULL,
  `farms_id` int(11) DEFAULT NULL,
  `billingtime` varchar(500) DEFAULT NULL,
  `amounts_receivable` int(11) DEFAULT NULL,
  `real_income_amount` int(11) DEFAULT NULL,
  `ypayyear` int(11) DEFAULT NULL,
  `ypayarea` float DEFAULT NULL,
  `ypaymoney` float DEFAULT NULL,
  `owe` float DEFAULT NULL,
  `isupdate` int(11) DEFAULT NULL,
  `cardid` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `land_collection`
--

INSERT INTO `land_collection` (`id`, `payyear`, `farms_id`, `billingtime`, `amounts_receivable`, `real_income_amount`, `ypayyear`, `ypayarea`, `ypaymoney`, `owe`, `isupdate`, `cardid`) VALUES
(34, NULL, 1, '0', 96000, 50000, 2014, 383.33, 46000, 46000, 0, '232700000000000000'),
(35, NULL, 1, '0', 100800, 70000, 2015, 244.44, 30800, 76800, 0, '232700000000000000');

-- --------------------------------------------------------

--
-- 表的结构 `land_farmer`
--

CREATE TABLE IF NOT EXISTS `land_farmer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `farmername` varchar(500) DEFAULT NULL,
  `cardid` varchar(500) DEFAULT NULL,
  `farms_id` int(11) DEFAULT NULL,
  `isupdate` int(11) DEFAULT NULL,
  `farmerbeforename` varchar(500) DEFAULT NULL,
  `nickname` varchar(500) DEFAULT NULL,
  `gender` varchar(500) DEFAULT NULL,
  `nation` varchar(500) DEFAULT NULL,
  `political_outlook` varchar(500) DEFAULT NULL,
  `cultural_degree` varchar(500) DEFAULT NULL,
  `domicile` varchar(500) DEFAULT NULL,
  `nowlive` varchar(500) DEFAULT NULL,
  `telephone` varchar(500) DEFAULT NULL,
  `living_room` varchar(500) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `cardpic` varchar(500) DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `land_farmer`
--

INSERT INTO `land_farmer` (`id`, `farmername`, `cardid`, `farms_id`, `isupdate`, `farmerbeforename`, `nickname`, `gender`, `nation`, `political_outlook`, `cultural_degree`, `domicile`, `nowlive`, `telephone`, `living_room`, `photo`, `cardpic`, `years`) VALUES
(2, '于峰龙', '230231197507182000', 1, 0, '', '', '男', '1', '群众', '初中', '大兴安岭地区加格达奇区', '大兴安岭地区加格达奇区', '13845777100', NULL, 'uploads/1433766705949.jpg', 'uploads/1433766705162.jpg', 2015),
(14, '张三', '232700000000000000', 1, 0, '', '', '男', '1', '党员', '研究生', '大兴安岭地区加格达奇区', '大兴安岭地区加格达奇区', '13800000000', NULL, 'uploads/1434784695408.jpg', 'uploads/1434784695840.jpg', 2014),
(15, '刘长文', '23022319611023252X', 7, 0, '', NULL, '男', '1', '群众', '初中', '大兴安岭地区加格达奇区', '大兴安岭地区加格达奇区', '15245758533', NULL, 'uploads/1435396611190.jpg', 'uploads/1435396611330.jpg', 2015),
(16, '刘长富', '230223196107142000', 8, 0, '', NULL, '男', '1', '群众', '初中', '', '', '', NULL, '', '', 2015);

-- --------------------------------------------------------

--
-- 表的结构 `land_farms`
--

CREATE TABLE IF NOT EXISTS `land_farms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `farmname` varchar(500) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `management_area` varchar(500) DEFAULT NULL,
  `spyear` varchar(500) DEFAULT NULL,
  `iscontract` int(11) DEFAULT NULL,
  `contractlife` varchar(500) DEFAULT NULL,
  `measure` int(11) DEFAULT NULL,
  `zongdi` varchar(500) DEFAULT NULL,
  `isdelete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `land_farms`
--

INSERT INTO `land_farms` (`id`, `farmname`, `address`, `management_area`, `spyear`, `iscontract`, `contractlife`, `measure`, `zongdi`, `isdelete`) VALUES
(1, '龙盾农场', '加卧79南5公里', '1', '1999', NULL, '30', 388, 'ab-34', NULL),
(7, '长富四农场', '加卧79南0.5公里', '1', '1990', NULL, '30', 141, '', NULL),
(8, '长富一农场', '加卧79南0.5公里', '1', '1990', NULL, '30', 256, '', NULL),
(9, '复兴农场', '加卧南5公里', '1', '1997', NULL, '30', 323, '', NULL),
(10, '丰庆一场', '加卧77南1公里', '1', '1990', NULL, '30', 403, '', NULL),
(11, '测试1', '测试1测试1', '4', '1994', NULL, '30', 367, '', NULL),
(12, '测试2', '测试1测试1', '4', '1994', NULL, '30', 367, '', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `land_goodseed`
--

CREATE TABLE IF NOT EXISTS `land_goodseed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plant_id` int(11) DEFAULT NULL,
  `plant_model` varchar(500) DEFAULT NULL,
  `planting_area` float DEFAULT NULL,
  `plant_measurearea` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `land_groups`
--

CREATE TABLE IF NOT EXISTS `land_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(500) DEFAULT NULL,
  `grouprole` varchar(500) DEFAULT NULL,
  `groupmark` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `land_groups`
--

INSERT INTO `land_groups` (`id`, `groupname`, `grouprole`, `groupmark`) VALUES
(1, '超级管理员', '99', 'administrator'),
(6, '操作员', '1,2', 'operator');

-- --------------------------------------------------------

--
-- 表的结构 `land_inputproduct`
--

CREATE TABLE IF NOT EXISTS `land_inputproduct` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `father_id` int(11) DEFAULT NULL,
  `fertilizer` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `land_inputproduct`
--

INSERT INTO `land_inputproduct` (`id`, `father_id`, `fertilizer`) VALUES
(1, 1, '大类'),
(2, 1, '化肥'),
(3, 1, '有机肥料'),
(4, 2, '氮肥'),
(5, 2, '磷肥');

-- --------------------------------------------------------

--
-- 表的结构 `land_lease`
--

CREATE TABLE IF NOT EXISTS `land_lease` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lease_area` varchar(500) DEFAULT NULL,
  `lessee` varchar(500) DEFAULT NULL,
  `plant_id` varchar(500) DEFAULT NULL,
  `farms_id` int(11) DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  `lessee_cardid` text,
  `lessee_telephone` int(11) DEFAULT NULL,
  `begindate` varchar(500) DEFAULT NULL,
  `enddate` text,
  `photo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `land_logs`
--

CREATE TABLE IF NOT EXISTS `land_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tablename` varchar(500) DEFAULT NULL,
  `actions` varchar(500) DEFAULT NULL,
  `createtime` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `land_mainmenu`
--

CREATE TABLE IF NOT EXISTS `land_mainmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuname` varchar(500) DEFAULT NULL,
  `menuurl` varchar(500) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `land_mainmenu`
--

INSERT INTO `land_mainmenu` (`id`, `menuname`, `menuurl`, `sort`) VALUES
(1, '农场管理', '/farms/farmsindex', 2),
(2, '承包', '/cb/cbindex', 90),
(3, '区域管理', '/managementarea/managementareaindex', 90),
(4, '作物管理', '/plant/plantindex', 90),
(5, '民族管理', 'nation/nationindex', 90),
(6, '业务办理', 'farms/farmsbusiness', 3),
(7, '缴费基数', '/plantprice/plantpriceindex', 90),
(8, '承包费收缴', '/collection/collectionindex', 90),
(9, '账号管理', '/bankaccount/bankaccountindex', 90),
(10, '投入品管理', 'inputproduct/inputproductindex', 90),
(11, '农药管理', 'pesticides/pesticidesindex', 90),
(12, '良种管理', '/goodseed/goodseedindex', 90),
(13, '年度设置', '/theyear/theyearupdate&id=1', 98);

-- --------------------------------------------------------

--
-- 表的结构 `land_management_area`
--

CREATE TABLE IF NOT EXISTS `land_management_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `areaname` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `land_management_area`
--

INSERT INTO `land_management_area` (`id`, `areaname`) VALUES
(1, '白音河管理区'),
(4, '甘多管理区');

-- --------------------------------------------------------

--
-- 表的结构 `land_menu_to_user`
--

CREATE TABLE IF NOT EXISTS `land_menu_to_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `menulist` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `land_menu_to_user`
--

INSERT INTO `land_menu_to_user` (`id`, `user_id`, `menulist`) VALUES
(3, 2, '1,3,4'),
(4, 4, '1,6,3,4,5,10,11,12,13'),
(5, 5, '7,8,9');

-- --------------------------------------------------------

--
-- 表的结构 `land_migration`
--

CREATE TABLE IF NOT EXISTS `land_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `land_migration`
--

INSERT INTO `land_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1430616560),
('m130524_201442_init', 1430616562),
('m140506_102106_rbac_init', 1430998636);

-- --------------------------------------------------------

--
-- 表的结构 `land_nation`
--

CREATE TABLE IF NOT EXISTS `land_nation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nationname` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `land_nation`
--

INSERT INTO `land_nation` (`id`, `nationname`) VALUES
(1, '汉族'),
(2, '回族'),
(3, '满族');

-- --------------------------------------------------------

--
-- 表的结构 `land_pesticides`
--

CREATE TABLE IF NOT EXISTS `land_pesticides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pesticidename` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `land_plant`
--

CREATE TABLE IF NOT EXISTS `land_plant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cropname` varchar(500) DEFAULT NULL,
  `father_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `land_plant`
--

INSERT INTO `land_plant` (`id`, `cropname`, `father_id`) VALUES
(1, '大类', 1),
(2, '小麦', 5),
(3, '玉米', 5),
(4, '马铃薯', 5),
(5, '粮食作物', 1),
(6, '大豆', 5),
(7, '经济作物', 1),
(8, '北药', 1),
(9, '芸豆', 7),
(10, '黄芪', 8),
(11, '沙参', 8),
(12, '赤勺', 8),
(13, '其它', 1);

-- --------------------------------------------------------

--
-- 表的结构 `land_plant_price`
--

CREATE TABLE IF NOT EXISTS `land_plant_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plant` varchar(500) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `land_plant_price`
--

INSERT INTO `land_plant_price` (`id`, `plant`, `price`, `years`) VALUES
(1, '小麦', 4.2, 2015),
(2, NULL, 4, 2014);

-- --------------------------------------------------------

--
-- 表的结构 `land_role`
--

CREATE TABLE IF NOT EXISTS `land_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(500) DEFAULT NULL,
  `rolemark` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `land_role`
--

INSERT INTO `land_role` (`id`, `rolename`, `rolemark`) VALUES
(1, '农场管理', 'farms'),
(2, '用户管理', 'user'),
(4, '农场主', 'farmer');

-- --------------------------------------------------------

--
-- 表的结构 `land_tablefields`
--

CREATE TABLE IF NOT EXISTS `land_tablefields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tables_id` int(11) NOT NULL,
  `fields` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `cfields` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

--
-- 转存表中的数据 `land_tablefields`
--

INSERT INTO `land_tablefields` (`id`, `tables_id`, `fields`, `type`, `cfields`) VALUES
(2, 19, 'farmname', 'VARCHAR(500) NULL', '农场名称'),
(3, 19, 'address', 'varchar(500) null', '农场位置'),
(4, 22, 'user_id', 'int(11)', '用户ID'),
(5, 22, 'groupname', 'varchar(500) null', '用户组名称'),
(6, 22, 'grouprole', 'varchar(500) null', '用户组权限'),
(7, 21, 'rolename', 'varchar(500) null', '权限名称'),
(8, 21, 'rolemark', 'varchar(500) null', '权限标识'),
(9, 20, 'farmername', 'varchar(500) null', '承包人姓名'),
(10, 20, 'cardid', 'varchar(500) null', '身份证号'),
(13, 22, 'groupmark', 'varchar(500) null', '用户组标识'),
(14, 23, 'menuname', 'varchar(500) null', '菜单名称'),
(15, 23, 'menuurl', 'varchar(500) null', '菜单地址'),
(23, 24, 'user_id', 'int(11)', '用户ID'),
(24, 24, 'menulist', 'varchar(500) null', '所属导航'),
(25, 19, 'management_area', 'varchar(500) null', '管理区'),
(26, 19, 'spyear', 'varchar(500) null', '审批年度'),
(27, 25, 'areaname', 'varchar(500) null', '区域名称'),
(28, 26, 'cropname', 'varchar(500) null', '作物名称'),
(29, 20, 'farms_id', 'int(11)', '农场ID'),
(30, 19, 'iscontract', 'int(11)', '是否承包'),
(31, 19, 'contractlife', 'varchar(500) null', '承包年限'),
(32, 20, 'isupdate', 'int(11)', '是否可更新'),
(33, 27, 'ip', 'varchar(500) null', '访问IP'),
(34, 27, 'user_id', 'int(11)', '用户ID'),
(35, 27, 'tablename', 'varchar(500) null', '数据表'),
(36, 27, 'actions', 'varchar(500) null', '操作'),
(37, 27, 'createtime', 'varchar(500) null', '操作时间'),
(38, 19, 'measure', 'int(11)', '面积'),
(39, 19, 'zongdi', 'varchar(500) null', '宗地'),
(40, 20, 'farmerbeforename', 'varchar(500) null', '曾用名'),
(41, 20, 'nickname', 'varchar(500) null', '绰号'),
(42, 20, 'gender', 'varchar(500) null', '性别'),
(43, 20, 'nation', 'varchar(500) null', '民族'),
(44, 20, 'political_outlook', 'varchar(500) null', '政治面貌'),
(45, 20, 'cultural_degree', 'varchar(500) null', '文化程度'),
(46, 20, 'domicile', 'varchar(500) null', '户籍所在地'),
(47, 20, 'nowlive', 'varchar(500) null', '现住地'),
(48, 20, 'telephone', 'varchar(500) null', '电话号码'),
(49, 20, 'living_room', 'varchar(500) null', '生产生活用房坐标点'),
(50, 28, 'nationname', 'varchar(500) null', '民族名称'),
(51, 29, 'payyear', 'varchar(500) null', '缴费年度'),
(52, 29, 'farms_id', 'int(11)', '农场ID'),
(53, 29, 'billingtime', 'varchar(500) null', '开票时间'),
(54, 29, 'amounts_receivable', 'int(11)', '应收金额'),
(55, 29, 'real_income_amount', 'int(11)', '实收金额'),
(56, 30, 'lease_area', 'FLOAT', '租赁面积'),
(57, 30, 'lessee', 'varchar(500) null', '承租人'),
(58, 30, 'plant_id', 'varchar(500) null', '种植结构'),
(59, 20, 'photo', 'varchar(500) null', '近期照片'),
(60, 20, 'cardpic', 'varchar(500) null', '身份证扫描件'),
(61, 30, 'farms_id', 'int(11)', '农场ID'),
(62, 20, 'years', 'int(11)', '年度'),
(63, 31, 'years', 'int(11)', '年度'),
(64, 30, 'years', 'int(11)', '年度'),
(65, 29, 'ypayyear', 'int(11)', '应缴费年度'),
(66, 29, 'ypayarea', 'int(11)', '应追缴费面积'),
(67, 29, 'ypaymoney', 'int(11)', '应追缴费金额'),
(68, 29, 'owe', 'int(11)', '剩余欠缴金额'),
(69, 32, 'plant', 'varchar(500) null', '小麦'),
(70, 32, 'price', 'FLOAT(11)', '价格'),
(71, 33, 'accountnumber', 'varchar(500) null', '账号'),
(72, 33, 'farmer_id', 'int(11)', '法人ID'),
(73, 33, 'bank', 'varchar(500) null', '所属银行'),
(74, 29, 'isupdate', 'int(11)', '是否可更改'),
(75, 32, 'years', 'int(11)', '年度'),
(76, 29, 'cardid', 'varchar(500) null', '法人身份证'),
(77, 30, 'lessee_cardid', 'varchar(500) null', '身份证号'),
(78, 30, 'lessee_telephone', 'int(11)', '联系电话'),
(79, 30, 'begindate', 'varchar(500) null', '开始日期'),
(80, 30, 'enddate', 'varchar(500) null', '结束日期'),
(81, 26, 'father_id', 'int(11)', '类别'),
(82, 34, 'father_id', 'int(11)', '类型'),
(83, 34, 'fertilizer', 'varchar(500) null', '肥料'),
(84, 35, 'pesticidename', 'varchar(500) null', '农药名称'),
(85, 36, 'plant_id', 'int(11)', '农作物品种'),
(86, 36, 'plant_model', 'varchar(500) null', '农作物型号'),
(87, 36, 'planting_area', 'FLOAT', '种植区域'),
(88, 36, 'plant_measurearea', 'FLOAT', '种植面积'),
(89, 19, 'isdelete', 'int(11)', '回收站'),
(91, 23, 'sort', 'int(11)', '排序'),
(92, 30, 'photo', 'varchar(500) null', '照片');

-- --------------------------------------------------------

--
-- 表的结构 `land_tables`
--

CREATE TABLE IF NOT EXISTS `land_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tablename` varchar(100) NOT NULL,
  `Ctablename` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `land_tables`
--

INSERT INTO `land_tables` (`id`, `tablename`, `Ctablename`) VALUES
(19, 'farms', '农场管理'),
(20, 'farmer', '承包人'),
(21, 'role', '权限管理'),
(22, 'groups', '用户组管理'),
(23, 'mainmenu', '菜单管理'),
(24, 'menu_to_user', '导航管理'),
(25, 'management_area', '区域管理'),
(26, 'plant', '种植结构'),
(27, 'logs', '日志'),
(28, 'nation', '民族'),
(29, 'Collection', '承包费收缴'),
(30, 'lease', '租赁'),
(31, 'theyear', '年度'),
(32, 'plant_price', '缴费基数'),
(33, 'bank_account', '银行账号'),
(34, 'Inputproduct', '农业投入品'),
(35, 'pesticides', '农药'),
(36, 'goodseed', '良种使用信息');

-- --------------------------------------------------------

--
-- 表的结构 `land_theyear`
--

CREATE TABLE IF NOT EXISTS `land_theyear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `years` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `land_theyear`
--

INSERT INTO `land_theyear` (`id`, `years`) VALUES
(1, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `land_user`
--

CREATE TABLE IF NOT EXISTS `land_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `land_user`
--

INSERT INTO `land_user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'qQoMxGYj5IYP-KExQ23uYHIhwaXKwMGw', '$2y$13$w/WFFg6GuGgM3PnytL700On1ja6h1qVpqpBOHR81vjyz9FB2bN.fW', '', 'admin888@admin.com', 10, 1430436861, 1430436861),
(2, 'ljm', 'imNyZmRTutBYGgnlxG705dfZtvzfJFO-', '$2y$13$Q597exnef.oBPOqUkCcXfekiFsQmv2ozgwVQZNIOmLCf96mkNPrzy', '', 'zhangsan@qq.com', 10, 1430986389, 1430988456),
(4, 'test2', 'X_vVTGy1F17N3ZWqog2aXWglDROjqF08', '$2y$13$TyEd4LOadrGWvS0PdAcg5upL1xQOxXQsuiXBM5nnUR./lLt5ynD3.', NULL, 'test2@test.com', 10, 1431307889, 1431307889),
(5, 'cwk01', '_TBuZFqVgF5pPhmRHAozmX-Sn_7DazBe', '$2y$13$Gfmv3rT/8I1dYOE8XKWhr.ABSmoOf6dZ2OgtTw7zLG4Tz4K4k44ue', NULL, 'cw@cw.163.com', 10, 1434253703, 1434253703);

--
-- 限制导出的表
--

--
-- 限制表 `land_auth_assignment`
--
ALTER TABLE `land_auth_assignment`
  ADD CONSTRAINT `land_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `land_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `land_auth_item`
--
ALTER TABLE `land_auth_item`
  ADD CONSTRAINT `land_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `land_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 限制表 `land_auth_item_child`
--
ALTER TABLE `land_auth_item_child`
  ADD CONSTRAINT `land_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `land_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `land_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `land_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
