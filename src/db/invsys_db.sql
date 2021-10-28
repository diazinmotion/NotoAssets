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

 Date: 05/10/2021 15:43:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for checklist
-- ----------------------------
DROP TABLE IF EXISTS `checklist`;
CREATE TABLE `checklist`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`, `name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for checklist_item
-- ----------------------------
DROP TABLE IF EXISTS `checklist_item`;
CREATE TABLE `checklist_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `checklist_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `checklist_id`(`checklist_id`) USING BTREE,
  CONSTRAINT `checklist_item_ibfk_1` FOREIGN KEY (`checklist_id`) REFERENCES `checklist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for checklist_laptop
-- ----------------------------
DROP TABLE IF EXISTS `checklist_laptop`;
CREATE TABLE `checklist_laptop`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklist_id` int(11) NULL DEFAULT NULL,
  `laptop_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `checklist_id`(`checklist_id`) USING BTREE,
  INDEX `laptop_id`(`laptop_id`) USING BTREE,
  CONSTRAINT `checklist_laptop_ibfk_1` FOREIGN KEY (`checklist_id`) REFERENCES `checklist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `checklist_laptop_ibfk_2` FOREIGN KEY (`laptop_id`) REFERENCES `laptop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for checklist_laptop_status
-- ----------------------------
DROP TABLE IF EXISTS `checklist_laptop_status`;
CREATE TABLE `checklist_laptop_status`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `has_done` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'N' COMMENT 'Y/N',
  `checklist_item_id` int(11) NULL DEFAULT NULL,
  `checklist_laptop_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `laptop_id`(`has_done`) USING BTREE,
  INDEX `checklist_item_id`(`checklist_item_id`) USING BTREE,
  INDEX `checklist_laptop_id`(`checklist_laptop_id`) USING BTREE,
  CONSTRAINT `checklist_laptop_status_ibfk_2` FOREIGN KEY (`checklist_laptop_id`) REFERENCES `checklist_laptop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `checklist_laptop_status_ibfk_1` FOREIGN KEY (`checklist_item_id`) REFERENCES `checklist_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for handover_laptop
-- ----------------------------
DROP TABLE IF EXISTS `handover_laptop`;
CREATE TABLE `handover_laptop`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `location_id` int(11) NULL DEFAULT NULL,
  `cubical_number` int(11) NULL DEFAULT NULL,
  `handovered_at` datetime NULL DEFAULT NULL,
  `laptop_id` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `location_id`(`location_id`) USING BTREE,
  INDEX `laptop_id`(`laptop_id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  CONSTRAINT `handover_laptop_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `master_location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `handover_laptop_ibfk_2` FOREIGN KEY (`laptop_id`) REFERENCES `laptop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
  `reference_id` int(11) NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for laptop_history
-- ----------------------------
DROP TABLE IF EXISTS `laptop_history`;
CREATE TABLE `laptop_history`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `events` varchar(350) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `detail` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_by` int(255) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `laptop_id` int(11) NULL DEFAULT NULL,
  `category` int(2) NULL DEFAULT NULL COMMENT '1: SERVICE, 2: HANDOVER',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `laptop_id`(`laptop_id`) USING BTREE,
  INDEX `created_by`(`created_by`) USING BTREE,
  CONSTRAINT `laptop_history_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `laptop_history_ibfk_1` FOREIGN KEY (`laptop_id`) REFERENCES `laptop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
  `is_freeware` int(1) NULL DEFAULT 0 COMMENT '1/0',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
  CONSTRAINT `master_software_package_item_ibfk_1` FOREIGN KEY (`software_id`) REFERENCES `master_software` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `master_software_package_item_ibfk_2` FOREIGN KEY (`software_package_id`) REFERENCES `master_software_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
-- Table structure for service_laptop
-- ----------------------------
DROP TABLE IF EXISTS `service_laptop`;
CREATE TABLE `service_laptop`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `laptop_id` int(11) NULL DEFAULT NULL,
  `service_start` date NULL DEFAULT NULL,
  `service_end` date NULL DEFAULT NULL,
  `service_location` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `pic_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pic_contact` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ticket_it` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ticket_ga` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `purposes` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `laptop_id`(`laptop_id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  CONSTRAINT `service_laptop_ibfk_1` FOREIGN KEY (`laptop_id`) REFERENCES `laptop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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

SET FOREIGN_KEY_CHECKS = 1;
