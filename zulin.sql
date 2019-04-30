/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : zulin

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2019-04-30 17:38:44
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
  `unit` varchar(19) DEFAULT NULL,
  `loss_price` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for flow
-- ----------------------------
DROP TABLE IF EXISTS `flow`;
CREATE TABLE `flow` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `begin_date` varchar(13) DEFAULT NULL,
  `create_date` varchar(13) DEFAULT NULL,
  `number` int(6) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `discount` decimal(8,2) DEFAULT NULL,
  `money` decimal(8,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `settle` tinyint(4) DEFAULT NULL,
  `settle_last` int(6) DEFAULT NULL,
  `site_id` int(5) DEFAULT NULL,
  `device_id` int(5) DEFAULT NULL,
  `detail` varchar(500) DEFAULT NULL,
  `ispaid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `device_id` (`device_id`),
  CONSTRAINT `flow_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `flow_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for settle
-- ----------------------------
DROP TABLE IF EXISTS `settle`;
CREATE TABLE `settle` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `site_id` int(5) DEFAULT NULL,
  `create_date` varchar(13) DEFAULT NULL,
  `detail` varchar(500) DEFAULT NULL,
  `money` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

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
  `isfinished` tinyint(1) DEFAULT '0',
  `paid_money` decimal(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
