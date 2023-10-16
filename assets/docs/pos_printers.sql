/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : hospitalzm

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-12-09 10:42:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pos_printers`
-- ----------------------------
DROP TABLE IF EXISTS `pos_printers`;
CREATE TABLE `pos_printers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `encode` varchar(15) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pos_printers
-- ----------------------------
INSERT INTO `pos_printers` VALUES ('1', 'Thermal Receipt Printer (58mm)', '32', 'utf8');
INSERT INTO `pos_printers` VALUES ('2', 'Xprinter XP-58IIH (58mm)', '32', 'chinese');
