-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-31 14:36:30
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `landsystem`
--

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
('collectioncq', 2, NULL, NULL, NULL, 1442228893, 1442228893),
('collectioncreate', 2, NULL, NULL, NULL, 1434292879, 1434292879),
('collectiondelete', 2, NULL, NULL, NULL, 1434292900, 1434292900),
('collectionfarmerlist', 2, NULL, NULL, NULL, 1434609018, 1434609018),
('collectionindex', 2, NULL, NULL, NULL, 1434292872, 1434292872),
('collectionupdate', 2, NULL, NULL, NULL, 1434292886, 1434292886),
('collectionview', 2, NULL, NULL, NULL, 1434292891, 1434292891),
('collectionyears', 2, NULL, NULL, NULL, 1434609008, 1434609008),
('cooperativecreate', 2, NULL, NULL, NULL, 1438946661, 1438946661),
('cooperativedelete', 2, NULL, NULL, NULL, 1438946684, 1438946684),
('cooperativeindex', 2, NULL, NULL, NULL, 1438946703, 1438946703),
('cooperativetypecreate', 2, NULL, NULL, NULL, 1438950368, 1438950368),
('cooperativetypedelete', 2, NULL, NULL, NULL, 1438950357, 1438950357),
('cooperativetypeindex', 2, NULL, NULL, NULL, 1438950339, 1438950339),
('cooperativetypeupdate', 2, NULL, NULL, NULL, 1438950344, 1438950344),
('cooperativetypeview', 2, NULL, NULL, NULL, 1438950351, 1438950351),
('cooperativeupdate', 2, NULL, NULL, NULL, 1438946674, 1438946674),
('cooperativeview', 2, NULL, NULL, NULL, 1438946678, 1438946678),
('farmercontract', 2, NULL, NULL, NULL, 1434940113, 1434940113),
('farmercreate', 2, NULL, NULL, NULL, 1434851813, 1434851813),
('farmerxls', 2, NULL, NULL, NULL, 1440295192, 1440295192),
('farmsbusiness', 2, NULL, NULL, NULL, 1434086405, 1434086405),
('farmscreate', 2, NULL, NULL, NULL, 1431822981, 1431822981),
('farmsdelete', 2, NULL, NULL, NULL, 1431822999, 1431822999),
('farmsindex', 2, NULL, NULL, NULL, 1431823019, 1431823019),
('farmsmenu', 2, NULL, NULL, NULL, 1434085549, 1434085549),
('farmstransfer', 2, NULL, NULL, NULL, 1446106031, 1446106031),
('farmsttpo', 2, NULL, NULL, NULL, 1446039712, 1446039712),
('farmsttpomenu', 2, NULL, NULL, NULL, 1446105573, 1446105573),
('farmsupdate', 2, NULL, NULL, NULL, 1431822988, 1431822988),
('farmsview', 2, NULL, NULL, NULL, 1431822994, 1431822994),
('farmsxls', 2, NULL, NULL, NULL, 1439988878, 1439988878),
('farmszdxls', 2, NULL, NULL, NULL, 1442645262, 1442645262),
('getamounts', 2, NULL, NULL, NULL, 1441977993, 1441977993),
('getar', 2, NULL, NULL, NULL, 1442574425, 1442574425),
('getfarmarea', 2, NULL, NULL, NULL, 1441977666, 1441977666),
('getfarmid', 2, NULL, NULL, NULL, 1444999095, 1444999185),
('getfarmrows', 2, NULL, NULL, NULL, 1441977654, 1441977654),
('getplantprice', 2, NULL, NULL, NULL, 1442574447, 1442574447),
('goodseedcreate', 2, NULL, NULL, NULL, 1435154375, 1435154375),
('goodseeddelete', 2, NULL, NULL, NULL, 1435154392, 1435154392),
('goodseedgetson', 2, NULL, NULL, NULL, 1441107990, 1441107990),
('goodseedindex', 2, NULL, NULL, NULL, 1435154370, 1435154370),
('goodseedupdate', 2, NULL, NULL, NULL, 1435154380, 1435154380),
('goodseedview', 2, NULL, NULL, NULL, 1435154387, 1435154387),
('Inputproductcreate', 2, NULL, NULL, NULL, 1435154341, 1435154341),
('Inputproductdelete', 2, NULL, NULL, NULL, 1435154356, 1435154356),
('Inputproductindex', 2, NULL, NULL, NULL, 1435154336, 1435154336),
('Inputproductupdate', 2, NULL, NULL, NULL, 1435154348, 1435154348),
('Inputproductview', 2, NULL, NULL, NULL, 1435154353, 1435154353),
('leaseallview', 2, NULL, NULL, NULL, 1441104310, 1441104310),
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
('modfiyuserinfo', 2, NULL, NULL, NULL, 1445775686, 1445775686),
('nationcreate', 2, NULL, NULL, NULL, 1433567636, 1433567636),
('nationdelete', 2, NULL, NULL, NULL, 1433567662, 1433567662),
('nationindex', 2, NULL, NULL, NULL, 1433567626, 1433567626),
('nationupdate', 2, NULL, NULL, NULL, 1433567645, 1433567645),
('nationview', 2, NULL, NULL, NULL, 1433567653, 1433567653),
('parcelcreate', 2, NULL, NULL, NULL, 1442489229, 1442489229),
('parceldelete', 2, NULL, NULL, NULL, 1442489248, 1442489248),
('parcelindex', 2, NULL, NULL, NULL, 1442489216, 1442489216),
('parcellist', 2, NULL, NULL, NULL, 1442490462, 1442490462),
('parcellistajax', 2, NULL, NULL, NULL, 1442490478, 1442490478),
('parcelupdate', 2, NULL, NULL, NULL, 1442489240, 1442489240),
('parcelview', 2, NULL, NULL, NULL, 1442489256, 1442489256),
('parcelxls', 2, NULL, NULL, NULL, 1442489293, 1442489293),
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
('plantgetson', 2, NULL, NULL, NULL, 1441185957, 1441185957),
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
('业务办理', 1, NULL, NULL, NULL, 1442488209, 1442488209),
('业务管理员', 1, NULL, NULL, NULL, 1431158539, 1431158539),
('作物管理', 1, NULL, NULL, NULL, 1432003168, 1432003168),
('农场管理', 1, NULL, NULL, NULL, 1431822947, 1431822947),
('区域管理', 1, NULL, NULL, NULL, 1431996349, 1431996349),
('合作社类型', 1, NULL, NULL, NULL, 1438950292, 1438950292),
('地产科', 1, NULL, NULL, NULL, 1432003339, 1432003339),
('宗地管理', 1, NULL, NULL, NULL, 1442488886, 1442488886),
('导航菜单管理', 1, NULL, NULL, NULL, 1431870907, 1431870907),
('数据库管理', 1, NULL, NULL, NULL, 1431861694, 1431861694),
('用户管理', 1, NULL, NULL, NULL, 1431181654, 1431181654),
('租赁管理', 1, NULL, NULL, NULL, 1433775849, 1433775849),
('系统维护员', 1, NULL, NULL, NULL, 1431868208, 1431868208),
('角色权限管理', 1, NULL, NULL, NULL, 1431870934, 1431870934),
('财务科', 1, NULL, NULL, NULL, 1434292910, 1434292910);

--
-- 限制导出的表
--

--
-- 限制表 `land_auth_item`
--
ALTER TABLE `land_auth_item`
  ADD CONSTRAINT `land_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `land_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
