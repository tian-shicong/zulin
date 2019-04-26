/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : zulin

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2019-04-26 17:50:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for device
-- ----------------------------
DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `count` int(6) DEFAULT NULL,
  `last` int(6) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of device
-- ----------------------------
INSERT INTO `device` VALUES ('2', 'device1', '0.11', '1000', '1000', '1');
INSERT INTO `device` VALUES ('3', 'device2', '0.09', '700', '500', '1');
INSERT INTO `device` VALUES ('4', 'device3', '1.25', '40', '40', '1');
INSERT INTO `device` VALUES ('5', 'device4', '12.90', '50', '40', '1');

-- ----------------------------
-- Table structure for flow
-- ----------------------------
DROP TABLE IF EXISTS `flow`;
CREATE TABLE `flow` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `begin_date` date DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `number` int(6) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `discount` decimal(8,2) DEFAULT NULL,
  `money` decimal(8,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `settle` tinyint(4) DEFAULT NULL,
  `settle_last` int(6) DEFAULT NULL,
  `site_id` int(5) DEFAULT NULL,
  `device_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `device_id` (`device_id`),
  CONSTRAINT `flow_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `flow_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow
-- ----------------------------

-- ----------------------------
-- Table structure for site
-- ----------------------------
DROP TABLE IF EXISTS `site`;
CREATE TABLE `site` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `person_name` varchar(10) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of site
-- ----------------------------
INSERT INTO `site` VALUES ('1', 'site1', 'person1', '09876543210', '1');
INSERT INTO `site` VALUES ('2', 'site2', 'person2', '12345678901', '1');
INSERT INTO `site` VALUES ('3', 'site3', 'person3', '15613152065', '1');

-- ----------------------------
-- Table structure for summary
-- ----------------------------
DROP TABLE IF EXISTS `summary`;
CREATE TABLE `summary` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `site_id` int(5) DEFAULT NULL,
  `device_id` int(5) DEFAULT NULL,
  `total` int(6) DEFAULT NULL,
  `revert` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `device_id` (`device_id`),
  CONSTRAINT `summary_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `summary_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of summary
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `site_id` int(5) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('2', 'role1', '96e79218965eb72c92a549dd5a330112', '0', '1', '1');
INSERT INTO `user` VALUES ('3', 'admin1', '5f4dcc3b5aa765d61d8327deb882cf99', '1', null, '0');
INSERT INTO `user` VALUES ('4', 'admin1', '5f4dcc3b5aa765d61d8327deb882cf99', '1', null, '1');
INSERT INTO `user` VALUES ('5', 'test', '96e79218965eb72c92a549dd5a330112', '0', '2', '1');
