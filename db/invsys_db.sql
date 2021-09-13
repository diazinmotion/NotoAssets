/*
 Navicat Premium Data Transfer

 Source Server         : Localhost - Invsys
 Source Server Type    : MySQL
 Source Server Version : 50562
 Source Host           : localhost:3306
 Source Schema         : invsys_db

 Target Server Type    : MySQL
 Target Server Version : 50562
 File Encoding         : 65001

 Date: 11/09/2021 18:02:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for laptop
-- ----------------------------
DROP TABLE IF EXISTS `laptop`;
CREATE TABLE `laptop`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `entity_id` int(11) NULL DEFAULT NULL,
  `location_id` int(11) NULL DEFAULT NULL,
  `serial_number` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `os_type_id` int(11) NULL DEFAULT NULL,
  `os_product_key` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pki_email` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pki_password` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `encryption_password` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `encryption_recovery_file` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `storage_type_id` int(11) NULL DEFAULT NULL,
  `storage_type_brand` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `storage_size` double NULL DEFAULT NULL,
  `memory_type_id` int(11) NULL DEFAULT NULL,
  `memory_brand` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `memory_size` double NULL DEFAULT NULL,
  `account_type_id` int(11) NULL DEFAULT NULL,
  `account_email` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_status` int(1) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `purchased_at` date NULL DEFAULT NULL,
  `warranty_expired` date NULL DEFAULT NULL,
  `model_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `code`(`code`) USING BTREE,
  INDEX `entity_id`(`entity_id`, `location_id`, `os_type_id`, `storage_type_id`, `memory_type_id`, `account_type_id`, `flag_status`) USING BTREE,
  INDEX `location_id`(`location_id`) USING BTREE,
  INDEX `os_type_id`(`os_type_id`) USING BTREE,
  INDEX `storage_type_id`(`storage_type_id`) USING BTREE,
  INDEX `memory_type_id`(`memory_type_id`) USING BTREE,
  INDEX `account_type_id`(`account_type_id`) USING BTREE,
  INDEX `model_id`(`model_id`) USING BTREE,
  CONSTRAINT `laptop_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `master_entity` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `laptop_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `master_location` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `laptop_ibfk_3` FOREIGN KEY (`os_type_id`) REFERENCES `master_os` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `laptop_ibfk_4` FOREIGN KEY (`storage_type_id`) REFERENCES `master_storage` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `laptop_ibfk_5` FOREIGN KEY (`memory_type_id`) REFERENCES `master_memory` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `laptop_ibfk_6` FOREIGN KEY (`account_type_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `laptop_ibfk_7` FOREIGN KEY (`model_id`) REFERENCES `master_model` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of laptop
-- ----------------------------
INSERT INTO `laptop` VALUES (1, '12344', 'ideapad gaming 3', 1, 7, '121212', 1, NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, '2021-09-04 08:24:17', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `laptop` VALUES (2, '570526', '', 6, 12, 'AKAJSKAJS-121212', 5, 'KAJN-AKAJ-SKAJ', 'lkaslkalsk@gmail.com', '1234567', '1234567', NULL, 10, 'Adata', 1000, 11, 'Kingston', 16, 9, 'asasas@gmail.com', 1, '2021-09-07 10:20:15', NULL, 1, NULL, '2021-09-07', '2021-09-30', 9);
INSERT INTO `laptop` VALUES (3, '570526', 'asdas', 5, 10, 'AKAJSKAJS-121212', 4, 'KAJN-AKAJ-SKAJ', '12asasas12@asasas.com', '1234567', '12345678', NULL, 10, 'Adata', 1212, 11, 'Kingston', 1212, 7, '1212@asasas.com', 0, '2021-09-07 10:47:01', NULL, 1, NULL, '2021-09-06', '2021-10-01', 9);

-- ----------------------------
-- Table structure for license
-- ----------------------------
DROP TABLE IF EXISTS `license`;
CREATE TABLE `license`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `universal_product_key` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `purchased_place` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `purchased_at` date NULL DEFAULT NULL,
  `quota` int(255) NULL DEFAULT 1,
  `software_id` int(11) NULL DEFAULT NULL,
  `universal_expired_at` date NULL DEFAULT NULL,
  `is_bulk_license` int(1) NULL DEFAULT 0 COMMENT '1/0; If this is got checked mean that all laptop installed with this license will get the same license key',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `flag_permanent` int(1) NULL DEFAULT 1 COMMENT '1/0; If 1 means this licensed is on time purchase',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `software_id`(`software_id`) USING BTREE,
  CONSTRAINT `license_ibfk_1` FOREIGN KEY (`software_id`) REFERENCES `master_software` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of license
-- ----------------------------
INSERT INTO `license` VALUES (1, 'Home & Student', NULL, 'Tokopedia - Toko Kelontong', '2021-01-20', 10, 7, NULL, 0, '2021-09-11 02:44:49', NULL, NULL, NULL, 1);
INSERT INTO `license` VALUES (6, 'Business Edition 21', 'PKA-JAKA-91820-11111', 'Tokopedia - Office Suite Software', '2021-09-01', 5, 10, '2021-08-04', 1, '2021-09-11 11:48:30', '2021-09-11 13:21:33', 1, 1, 0);
INSERT INTO `license` VALUES (8, 'Testing', NULL, 'asdasdasd', '2021-09-07', 2, 7, NULL, 0, '2021-09-11 11:53:27', NULL, 1, NULL, 1);

-- ----------------------------
-- Table structure for license_seat
-- ----------------------------
DROP TABLE IF EXISTS `license_seat`;
CREATE TABLE `license_seat`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `license_id` int(11) NULL DEFAULT NULL,
  `laptop_id` int(11) NULL DEFAULT NULL,
  `expiration_at` date NULL DEFAULT NULL,
  `installed_at` datetime NULL DEFAULT NULL,
  `product_key` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `license_id`(`license_id`) USING BTREE,
  INDEX `laptop_id`(`laptop_id`) USING BTREE,
  CONSTRAINT `license_seat_ibfk_1` FOREIGN KEY (`license_id`) REFERENCES `license` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `license_seat_ibfk_2` FOREIGN KEY (`laptop_id`) REFERENCES `laptop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of license_seat
-- ----------------------------
INSERT INTO `license_seat` VALUES (1, 6, 2, NULL, '2021-09-10 15:19:20', 'XH17-JAS7-111K', '2021-09-11 08:19:38', NULL, NULL, NULL);
INSERT INTO `license_seat` VALUES (2, 6, 2, NULL, '2021-09-10 15:19:20', 'XH17-JAS7-111K', '2021-09-11 08:19:38', NULL, NULL, NULL);
INSERT INTO `license_seat` VALUES (3, 6, 2, NULL, '2021-09-10 15:19:20', 'XH17-JAS7-111K', '2021-09-11 08:19:38', NULL, NULL, NULL);
INSERT INTO `license_seat` VALUES (4, 6, 2, NULL, '2021-09-10 15:19:20', 'XH17-JAS7-111K', '2021-09-11 08:19:38', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for master_account
-- ----------------------------
DROP TABLE IF EXISTS `master_account`;
CREATE TABLE `master_account`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_account
-- ----------------------------
INSERT INTO `master_account` VALUES (7, 'Apple ID', '2021-08-31 10:14:44', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_account` VALUES (8, 'Microsoft ID 2', '2021-08-31 10:14:51', '2021-08-31 10:14:58', '2021-08-31 10:15:02', 1, 1, 1);
INSERT INTO `master_account` VALUES (9, 'Microsoft ID', '2021-09-04 17:30:52', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_account` VALUES (10, 'Linux ID', '2021-09-04 17:31:00', NULL, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for master_brand
-- ----------------------------
DROP TABLE IF EXISTS `master_brand`;
CREATE TABLE `master_brand`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_brand
-- ----------------------------
INSERT INTO `master_brand` VALUES (1, 'Lenovo', '2021-08-31 06:51:20', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (2, 'Toshiba', '2021-08-31 06:51:28', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (3, 'Apple', '2021-08-31 06:51:34', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (4, 'Acer', '2021-08-31 06:51:43', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (5, 'Dell', '2021-08-31 06:51:52', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (6, 'Asus', '2021-08-31 06:52:00', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (7, 'Axioo', '2021-09-04 17:35:35', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_brand` VALUES (8, 'CHingChongKhuan', '2021-09-04 17:35:46', NULL, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for master_entity
-- ----------------------------
DROP TABLE IF EXISTS `master_entity`;
CREATE TABLE `master_entity`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_active` int(1) NULL DEFAULT 1 COMMENT '0 = Inactive; 1 = Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`, `code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_entity
-- ----------------------------
INSERT INTO `master_entity` VALUES (1, 'GIA', 'Garuda Indonesia 2', 1, '2021-08-29 11:01:44', '2021-09-04 17:27:41', NULL, NULL, 1, NULL);
INSERT INTO `master_entity` VALUES (5, 'DCI', 'PT. Dwi Cermat Indonesia', 1, '2021-08-30 22:58:22', '2021-09-04 17:29:03', NULL, 1, 1, 1);
INSERT INTO `master_entity` VALUES (6, 'HAT', 'PT. Pizza Hut Timor Leste', 1, '2021-09-04 17:29:36', NULL, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for master_location
-- ----------------------------
DROP TABLE IF EXISTS `master_location`;
CREATE TABLE `master_location`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  `entity_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `entity_id`(`entity_id`) USING BTREE,
  CONSTRAINT `master_location_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `master_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_location
-- ----------------------------
INSERT INTO `master_location` VALUES (7, 'Tomang', '2021-08-31 06:55:17', NULL, NULL, 1, NULL, NULL, 1);
INSERT INTO `master_location` VALUES (8, 'Lebak Bulus', '2021-08-31 09:32:11', '2021-08-31 10:08:13', '2021-08-31 10:08:20', 1, 1, 1, 5);
INSERT INTO `master_location` VALUES (9, 'REQUEST', '2021-08-31 09:32:48', NULL, NULL, 1, NULL, NULL, 1);
INSERT INTO `master_location` VALUES (10, 'Direksi 2', '2021-08-31 09:54:00', '2021-08-31 10:08:02', NULL, 1, 1, NULL, 5);
INSERT INTO `master_location` VALUES (11, 'Lantai 2', '2021-09-04 17:30:16', NULL, NULL, 1, NULL, NULL, 6);
INSERT INTO `master_location` VALUES (12, 'Lantai 3', '2021-09-04 17:30:36', NULL, NULL, 1, NULL, NULL, 6);

-- ----------------------------
-- Table structure for master_memory
-- ----------------------------
DROP TABLE IF EXISTS `master_memory`;
CREATE TABLE `master_memory`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  `code` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_memory
-- ----------------------------
INSERT INTO `master_memory` VALUES (7, 'Double Data Rate 1', '2021-08-31 03:22:06', NULL, NULL, NULL, NULL, NULL, 'DDR1');
INSERT INTO `master_memory` VALUES (8, 'Double Data Rate 3', '2021-08-31 10:23:48', '2021-08-31 10:24:25', '2021-08-31 10:24:29', 1, 1, 1, 'DDR3');
INSERT INTO `master_memory` VALUES (9, 'Double Data Rate 2', '2021-09-04 17:36:56', NULL, NULL, 1, NULL, NULL, 'DDR2');
INSERT INTO `master_memory` VALUES (10, 'Double Data Rate 3', '2021-09-04 17:37:04', NULL, NULL, 1, NULL, NULL, 'DDR3');
INSERT INTO `master_memory` VALUES (11, 'Double Data Rate 4', '2021-09-04 17:37:13', NULL, NULL, 1, NULL, NULL, 'DDR4');
INSERT INTO `master_memory` VALUES (12, 'Double Data Rate 3 Low Voltage', '2021-09-04 17:37:27', NULL, NULL, 1, NULL, NULL, 'DDR3L');
INSERT INTO `master_memory` VALUES (13, 'Double Data Rate 4 Low Voltage', '2021-09-04 17:37:40', NULL, NULL, 1, NULL, NULL, 'DDR4L');

-- ----------------------------
-- Table structure for master_model
-- ----------------------------
DROP TABLE IF EXISTS `master_model`;
CREATE TABLE `master_model`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  `brand_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `brand_id`(`brand_id`) USING BTREE,
  CONSTRAINT `master_model_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `master_brand` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_model
-- ----------------------------
INSERT INTO `master_model` VALUES (9, 'Ideapad Gaming 3', '2021-09-05 03:18:16', NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO `master_model` VALUES (10, 'Nitro 5 AMD', '2021-09-05 10:21:12', NULL, NULL, 1, NULL, NULL, 4);

-- ----------------------------
-- Table structure for master_os
-- ----------------------------
DROP TABLE IF EXISTS `master_os`;
CREATE TABLE `master_os`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main_in`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_os
-- ----------------------------
INSERT INTO `master_os` VALUES (1, 'Windows 7 Vista', '2021-08-30 23:43:56', '2021-08-31 06:45:54', '2021-08-31 06:45:58', NULL, 1, 1);
INSERT INTO `master_os` VALUES (2, 'Windows 8 (x86)', '2021-08-31 06:44:37', '2021-08-31 06:45:39', NULL, 1, 1, NULL);
INSERT INTO `master_os` VALUES (3, 'Windows 8.1 (x64)', '2021-08-31 06:45:24', '2021-08-31 06:45:32', NULL, 1, 1, NULL);
INSERT INTO `master_os` VALUES (4, 'MacOs Sierra', '2021-08-31 06:46:27', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_os` VALUES (5, 'Windows 10 Home', '2021-09-04 17:31:32', NULL, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for master_software
-- ----------------------------
DROP TABLE IF EXISTS `master_software`;
CREATE TABLE `master_software`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_software
-- ----------------------------
INSERT INTO `master_software` VALUES (7, 'Adobe Photoshop CC 2020', '2021-08-31 03:33:08', '2021-08-31 10:35:10', NULL, NULL, 1, 1);
INSERT INTO `master_software` VALUES (8, 'Foxit PDF Reader 2021', '2021-08-31 10:35:03', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_software` VALUES (9, 'Slack', '2021-09-04 17:32:00', NULL, NULL, 1, NULL, NULL);
INSERT INTO `master_software` VALUES (10, 'Libre Office 2020', '2021-09-04 17:32:12', NULL, NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for master_software_package
-- ----------------------------
DROP TABLE IF EXISTS `master_software_package`;
CREATE TABLE `master_software_package`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_software_package
-- ----------------------------
INSERT INTO `master_software_package` VALUES (13, 'SLS', 'Sales Team Software', '2021-08-31 12:13:55', '2021-09-04 17:34:05', 1, 1);
INSERT INTO `master_software_package` VALUES (14, 'ITA', 'Software IT', '2021-09-04 17:33:51', '2021-09-09 20:32:49', 1, 1);
INSERT INTO `master_software_package` VALUES (21, 'ITB', 'Software IT', '2021-09-04 17:33:51', NULL, NULL, NULL);
INSERT INTO `master_software_package` VALUES (22, 'ITC', 'Software IT', '2021-09-04 17:33:51', NULL, NULL, NULL);
INSERT INTO `master_software_package` VALUES (23, 'ITD', 'Software IT', '2021-09-04 17:33:51', NULL, NULL, NULL);
INSERT INTO `master_software_package` VALUES (24, 'ITE', 'Software IT', '2021-09-04 17:33:51', NULL, NULL, NULL);
INSERT INTO `master_software_package` VALUES (25, 'ITF', 'Software IT', '2021-09-04 17:33:51', NULL, NULL, NULL);
INSERT INTO `master_software_package` VALUES (26, 'ITG', 'Interner Exp', '2021-09-09 20:51:42', NULL, 1, NULL);
INSERT INTO `master_software_package` VALUES (27, 'ITH', 'Internet H', '2021-09-09 20:52:16', NULL, 1, NULL);
INSERT INTO `master_software_package` VALUES (28, 'ITI', 'Internet I', '2021-09-09 20:52:56', NULL, 1, NULL);
INSERT INTO `master_software_package` VALUES (29, 'ITJ', 'Intenert J', '2021-09-09 20:53:22', NULL, 1, NULL);
INSERT INTO `master_software_package` VALUES (30, 'ITK', 'INterner K', '2021-09-09 20:53:52', NULL, 1, NULL);

-- ----------------------------
-- Table structure for master_software_package_item
-- ----------------------------
DROP TABLE IF EXISTS `master_software_package_item`;
CREATE TABLE `master_software_package_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `software_id` int(11) NULL DEFAULT NULL COMMENT 'REF: master_software',
  `software_package_id` int(11) NULL DEFAULT NULL COMMENT 'REF: master_software_package',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `software_id`(`software_id`) USING BTREE,
  INDEX `software_package_id`(`software_package_id`) USING BTREE,
  CONSTRAINT `master_software_package_item_ibfk_2` FOREIGN KEY (`software_package_id`) REFERENCES `master_software_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `master_software_package_item_ibfk_1` FOREIGN KEY (`software_id`) REFERENCES `master_software` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_software_package_item
-- ----------------------------
INSERT INTO `master_software_package_item` VALUES (20, 9, 13);
INSERT INTO `master_software_package_item` VALUES (21, 10, 13);
INSERT INTO `master_software_package_item` VALUES (26, 9, 14);
INSERT INTO `master_software_package_item` VALUES (27, 8, 14);
INSERT INTO `master_software_package_item` VALUES (28, 7, 14);
INSERT INTO `master_software_package_item` VALUES (29, 10, 14);
INSERT INTO `master_software_package_item` VALUES (39, 7, 26);
INSERT INTO `master_software_package_item` VALUES (40, 10, 26);
INSERT INTO `master_software_package_item` VALUES (41, 8, 26);
INSERT INTO `master_software_package_item` VALUES (42, 7, 27);
INSERT INTO `master_software_package_item` VALUES (43, 10, 27);
INSERT INTO `master_software_package_item` VALUES (44, 8, 27);
INSERT INTO `master_software_package_item` VALUES (45, 7, 28);
INSERT INTO `master_software_package_item` VALUES (46, 10, 28);
INSERT INTO `master_software_package_item` VALUES (47, 7, 29);
INSERT INTO `master_software_package_item` VALUES (48, 10, 29);
INSERT INTO `master_software_package_item` VALUES (49, 8, 29);
INSERT INTO `master_software_package_item` VALUES (50, 9, 29);
INSERT INTO `master_software_package_item` VALUES (51, 7, 30);
INSERT INTO `master_software_package_item` VALUES (52, 10, 30);
INSERT INTO `master_software_package_item` VALUES (53, 8, 30);

-- ----------------------------
-- Table structure for master_storage
-- ----------------------------
DROP TABLE IF EXISTS `master_storage`;
CREATE TABLE `master_storage`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `deleted_by` int(11) NULL DEFAULT NULL,
  `code` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of master_storage
-- ----------------------------
INSERT INTO `master_storage` VALUES (9, 'Solid State Drive 1', '2021-08-31 03:29:18', '2021-08-31 10:31:12', '2021-08-31 10:31:21', NULL, 1, 1, 'SSD 1');
INSERT INTO `master_storage` VALUES (10, 'Hard Disk Drive', '2021-08-31 10:31:07', NULL, NULL, 1, NULL, NULL, 'HDD');
INSERT INTO `master_storage` VALUES (11, 'Solid State Drive', '2021-09-04 17:38:10', NULL, NULL, 1, NULL, NULL, 'SSD');
INSERT INTO `master_storage` VALUES (12, 'Intel Optane', '2021-09-04 17:38:23', NULL, NULL, 1, NULL, NULL, 'OPT');
INSERT INTO `master_storage` VALUES (13, 'Hard Disk Drive & Solid State Drive', '2021-09-04 17:38:43', NULL, NULL, 1, NULL, NULL, 'HDD + SSD');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `full_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(400) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_super_admin` int(1) NULL DEFAULT 0 COMMENT '1: Super Admin; 0: User',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `flag_allowed` int(1) NULL DEFAULT 1 COMMENT '0: Allowed; 1: Disallowed',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`, `email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin@inv.com', 'Admin User', '$2y$10$qk6VGqp6mZ5/qvOvLWLtp.h7Hq9ImhoBCX4cUfnLejF4Ki/Nj5An.', 1, '2021-08-27 01:52:42', '2021-09-05 22:20:44', NULL, '2021-09-11 10:06:25', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36', 1);

SET FOREIGN_KEY_CHECKS = 1;
