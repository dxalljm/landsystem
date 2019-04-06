/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : landsystem

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-05-17 17:34:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for land_insurance
-- ----------------------------
DROP TABLE IF EXISTS `land_insurance`;
CREATE TABLE `land_insurance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `management_area` int(11) DEFAULT NULL,
  `year` varchar(500) DEFAULT NULL,
  `farms_id` int(11) DEFAULT NULL,
  `policyholder` varchar(500) DEFAULT NULL,
  `cardid` varchar(500) DEFAULT NULL,
  `telephone` varchar(500) DEFAULT NULL,
  `wheat` float(10,2) DEFAULT NULL,
  `soybean` float(10,2) DEFAULT NULL,
  `insuredarea` float(10,2) DEFAULT NULL,
  `insuredwheat` float(10,2) DEFAULT NULL,
  `insuredsoybean` float(10,2) DEFAULT NULL,
  `company_id` varchar(500) DEFAULT NULL,
  `create_at` varchar(500) DEFAULT NULL,
  `update_at` varchar(500) DEFAULT NULL,
  `policyholdertime` varchar(500) DEFAULT NULL,
  `managemanttime` varchar(500) DEFAULT NULL,
  `halltime` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of land_insurance
-- ----------------------------

-- ----------------------------
-- Table structure for land_insurancecompany
-- ----------------------------
DROP TABLE IF EXISTS `land_insurancecompany`;
CREATE TABLE `land_insurancecompany` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companynname` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of land_insurancecompany
-- ----------------------------
