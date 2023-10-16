/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : hospitalzm

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-12-09 10:42:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `code` varchar(50) DEFAULT NULL,
  `group` varchar(50) DEFAULT NULL,
  `value` varchar(255) DEFAULT '',
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('1', 'pos_printer_status', null, 'POS', '1', 'pos printer on or off');
INSERT INTO `settings` VALUES ('2', 'name', null, 'LBP', 'Қўқон шахар Hospital ZM клиникаси', 'klinika nomi');
INSERT INTO `settings` VALUES ('3', 'address', null, 'LBP', '', 'manzil');
INSERT INTO `settings` VALUES ('4', 'orientation', null, 'LBP', 'Қўқон шахар ИИБ идораси орқасида', 'muljal');
INSERT INTO `settings` VALUES ('5', 'phone', null, 'LBP', '+99890 3660003,      +998975660003', 'telefon');
INSERT INTO `settings` VALUES ('6', 'web_address', null, 'LBP', 'www.andrology.uz', 'web site');
INSERT INTO `settings` VALUES ('7', 'telegram', null, 'LBP', '@andrology_uz', 'telegram');
INSERT INTO `settings` VALUES ('8', 'logo', null, 'LBP', '', 'klinika logosi');
INSERT INTO `settings` VALUES ('9', 'email', null, 'LBP', 'amirzakulov@gmail.com', 'email');
INSERT INTO `settings` VALUES ('10', 'lab_title_alignment', null, 'LBP', 'left', null);
INSERT INTO `settings` VALUES ('11', 'lab_title_font_size', null, 'LBP', '1.2', 'rem da');
INSERT INTO `settings` VALUES ('12', 'lab_text_font_size', null, 'LBP', '1.2', 'rem da');
INSERT INTO `settings` VALUES ('13', 'laborant_id', null, 'LBP', '4', null);
INSERT INTO `settings` VALUES ('14', 'footer_text', null, 'LBP', '', null);
INSERT INTO `settings` VALUES ('15', 'selected_pos_printer_id', null, 'POS', '2', 'POS printerning idsi');
INSERT INTO `settings` VALUES ('16', 'pos_printer_qrcode', null, 'POS', '1', 'QR Codeni chop etish');
INSERT INTO `settings` VALUES ('17', 'pos_printer_logo_path', null, 'POS', null, 'Klinika Logosi');
INSERT INTO `settings` VALUES ('18', 'pos_printer_logo_print', null, 'POS', '1', 'Klinika Logosini chop etish');
INSERT INTO `settings` VALUES ('19', 'pos_printer_name', null, 'POS', 'PosPrinter', 'Printerning nomi');
