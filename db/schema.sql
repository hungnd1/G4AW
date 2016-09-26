-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
-- set global innodb_file_format = BARRACUDA;
-- set global innodb_large_prefix = ON;

-- -----------------------------------------------------
-- Schema vndonor
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `auth_rule`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `auth_rule` ;

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` VARCHAR(64) NOT NULL,
  `data` TEXT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `auth_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `auth_item` ;

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` VARCHAR(64) NOT NULL,
  `type` INT(11) NOT NULL,
  `description` TEXT NULL,
  `rule_name` VARCHAR(64) NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  `acc_type` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`),
  INDEX `rule_name` (`rule_name` ASC),
  INDEX `idx-auth_item-type` (`type` ASC),
  CONSTRAINT `auth_item_ibfk_1`
    FOREIGN KEY (`rule_name`)
    REFERENCES `auth_rule` (`name`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(512) NULL,
  `user_code` VARCHAR(20) NULL COMMENT 'ma nguoi dung (de nhan SMS ung ho...)',
  `phone_number` VARCHAR(128) NULL DEFAULT NULL,
  `avatar` VARCHAR(255) NULL,
  `cover_photo` VARCHAR(255) NULL,
  `email` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NULL,
  `other_profile` TEXT NULL COMMENT 'html, description',
  `individual` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 - ca nhan\n0 - to chuc',
  `auth_key` VARCHAR(32) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `password_reset_token` VARCHAR(255) NULL DEFAULT NULL,
  `role` SMALLINT(6) NOT NULL DEFAULT '10',
  `status` SMALLINT(6) NOT NULL DEFAULT '10',
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL,
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - Admin\n2 - to chuc cau noi\n3 - ben cung\n4 - ben cau',
  `access_login_token` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
COMMENT = 'quan ly cac site (tvod viet nam, tvod nga, tvod sec...)';


-- -----------------------------------------------------
-- Table `auth_assignment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `auth_assignment` ;

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` VARCHAR(64) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  INDEX `fk_auth_assignment_user1_idx` (`user_id` ASC),
  CONSTRAINT `auth_assignment_ibfk_1`
    FOREIGN KEY (`item_name`)
    REFERENCES `auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_assignment_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `auth_item_child`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `auth_item_child` ;

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  INDEX `child` (`child` ASC),
  CONSTRAINT `auth_item_child_ibfk_1`
    FOREIGN KEY (`parent`)
    REFERENCES `auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2`
    FOREIGN KEY (`child`)
    REFERENCES `auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `category` ;

CREATE TABLE IF NOT EXISTS `category` (
  `id` INT(11) AUTO_INCREMENT NOT NULL,
  `display_name` VARCHAR(200) NOT NULL,
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT 'type tuong ung voi cac loai content:\n1 - video\n2 - live\n3 - music\n4 - news\n',
  `description` TEXT NULL DEFAULT NULL,
  `display_name_en` TEXT NULL DEFAULT NULL,
  `status` INT(11) NOT NULL DEFAULT '1' COMMENT '10 - active\n0 - inactive\n3 - for test only',
  `order_number` INT(11) NOT NULL DEFAULT '0' COMMENT 'dung de sap xep category theo thu tu xac dinh, order chi dc so sanh khi cac category co cung level',
  `parent_id` INT(11) NULL,
  `path` VARCHAR(200) NULL DEFAULT NULL COMMENT 'chua duong dan tu root den node nay trong category tree, vi du: 1/3/18/4, voi 4 la id cua category hien tai',
  `level` INT(11) NULL DEFAULT NULL COMMENT '0 - root\n1 - category cap 2\n2 - category cap 3\n...',
  `child_count` INT(11) NULL DEFAULT NULL,
  `images` VARCHAR(500) NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_name` (`display_name` ASC),
  INDEX `idx_desc` (`description`(255) ASC),
  INDEX `idx_order_no` (`order_number` ASC),
  INDEX `idx_parent_id` (`parent_id` ASC),
  INDEX `idx_path` (`path` ASC),
  INDEX `idx_level` (`level` ASC),
  CONSTRAINT `fk_vod_category_vod_category`
    FOREIGN KEY (`parent_id`)
    REFERENCES `category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 29;



-- -----------------------------------------------------
-- Table `user_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_token` ;

CREATE TABLE IF NOT EXISTS `user_token` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `username` VARCHAR(20) NULL,
  `token` VARCHAR(100) NOT NULL,
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - wifi password\n2 - access token\n',
  `ip_address` VARCHAR(45) NULL,
  `created_at` INT(11) NULL,
  `expired_at` INT(11) NULL DEFAULT NULL,
  `cookies` VARCHAR(1000) NULL DEFAULT NULL,
  `status` INT(11) NOT NULL DEFAULT 10,
  `channel` SMALLINT(6) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_session_id` (`token` ASC),
  INDEX `idx_is_active` (`status` ASC),
  INDEX `idx_create_time` (`created_at` ASC),
  INDEX `idx_expire_time` (`expired_at` ASC),
  INDEX `fk_user_token_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_token_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
COMMENT = 'wifi password hoac access token khi dang nhap vao client';


-- -----------------------------------------------------
-- Table `transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `transaction` ;

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL COMMENT 'nguoi thuc hien donate',
  `username` VARCHAR(64) NULL,
  `payment_type` SMALLINT NOT NULL DEFAULT 1 COMMENT '1 - thanh toan = the cao\n2 - thanh toan = sms\n3 - thanh toan = internet banking\n4 - thanh toan chuyen khoan',
  `type` SMALLINT(6) NOT NULL DEFAULT 1 COMMENT '1 : donate\n2 : chi tien cho donee',
  `amount` DOUBLE(10,2) NULL DEFAULT '0.00',
  `transaction_time` INT(11) NULL,
  `status` INT(2) NOT NULL COMMENT '10 : success\n0 : fail\n',
  `telco` SMALLINT NOT NULL DEFAULT 1 COMMENT '1 - viettel\n2 - mobifone\n3 - vinaphone\n4 - vietnam mobile\n5 - gtel',
  `scratch_card_code` VARCHAR(45) NULL,
  `scratch_card_serial` VARCHAR(45) NULL,
  `shortcode` VARCHAR(45) NULL COMMENT '??u s? nh?n tin',
  `sms_mesage` VARCHAR(45) NULL,
  `bank_transaction_id` VARCHAR(45) NULL,
  `bank_transaction_detail` TEXT NULL,
  `description` VARCHAR(200) NULL DEFAULT NULL COMMENT 'm� t? nguy�n nh�n v� sao giao d?ch l?i\n',
  `error_code` VARCHAR(20) NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_create_date` (`created_at` ASC),
  INDEX `idx_status` (`status` ASC),
  INDEX `idx_purchase_type` (`type` ASC),
  INDEX `fk_transaction_campaign1_idx` (`campaign_id` ASC),
  INDEX `fk_transaction_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_transaction_campaign1`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `campaign` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_transaction_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 21
COMMENT = 'luu lai toan bo transaction cua subscriber'
DELAY_KEY_WRITE = 1;


-- -----------------------------------------------------
-- Table `user_activity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_activity` ;

CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `username` VARCHAR(255) NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` VARCHAR(255) NULL DEFAULT NULL,
  `action` VARCHAR(126) NULL DEFAULT NULL,
  `target_id` INT(11) NULL DEFAULT NULL COMMENT 'id cua doi tuong tac dong\n(phim, user...)',
  `target_type` SMALLINT(6) NULL DEFAULT NULL COMMENT '1 - user\n2 - cat\n3 - content\n4 - subscriber\n5 - ...',
  `created_at` INT(11) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NULL DEFAULT NULL,
  `site_id` INT(10) NULL DEFAULT NULL,
  `dealer_id` INT(10) NULL DEFAULT NULL,
  `request_detail` VARCHAR(256) NULL DEFAULT NULL,
  `request_params` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_activity_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_activity_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 160;



-- -----------------------------------------------------
-- Table `news`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `news` ;

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(512) NOT NULL,
  `title_en` VARCHAR(512) NOT NULL,
  `title_ascii` VARCHAR(512) NULL,
  `content` TEXT NULL DEFAULT NULL COMMENT 'HTML content',
  `content_en` TEXT NULL DEFAULT NULL COMMENT 'HTML content',
  `thumbnail` VARCHAR(512) NULL COMMENT 'anh de hien thi trong list',
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - html news\n2 - video (thumbnail c� n�t play)',
  `tags` VARCHAR(200) NULL,
  `short_description` VARCHAR(500) NULL,
  `short_description_en` VARCHAR(500) NULL,
  `description` TEXT NULL,
  `video_url` VARCHAR(500) NULL COMMENT 'json encoded array, link downoad (doi voi video, app). Doi voi app co the la link den apptota, googleplay, apple store hoac link download truc tiep',
  `view_count` INT(11) NOT NULL DEFAULT '0',
  `like_count` INT(11) NOT NULL DEFAULT '0',
  `comment_count` INT(11) NOT NULL DEFAULT '0',
  `favorite_count` INT(11) NOT NULL DEFAULT '0',
  `honor` INT(11) NULL DEFAULT '0' COMMENT '0 --> nothing\n1 --> featured\n2 --> hot\n3 --> especial',
  `source_name` VARCHAR(200) NULL,
  `source_url` VARCHAR(200) NULL,
  `status` INT(11) NOT NULL DEFAULT '10' COMMENT '0 - pending\n10 - active\n1 - waiting for trancoding\n2 - inactive\n3 - for test only\n4 - rejected vi nguyen nhan 1\n5 - rejected vi nguyen nhan 2\n6 - rejected vi nguyen nhan 3\n...',
  `created_user_id` INT(11) NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  `user_id` INT(11) NOT NULL,
  `published_at` INT(11) NOT NULL,
  `area_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_view_count` (`view_count` ASC),
  INDEX `idx_like_count` (`like_count` ASC),
  INDEX `idx_comment_count` (`comment_count` ASC),
  INDEX `idx_favorite_count` (`favorite_count` ASC),
  INDEX `idx_status` (`status` ASC),
  INDEX `fk_news_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_news_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 20;

-- -----------------------------------------------------
-- Table `campaign_related_asm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `news_category_asm` ;

CREATE TABLE IF NOT EXISTS `news_category_asm` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `news_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT NULL,
  PRIMARY KEY (`id`)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 18;

DROP TABLE IF EXISTS `area` ;

CREATE TABLE `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `name_en` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `province` ;

CREATE TABLE `province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `name_en` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `introduction` ;

CREATE TABLE `introduction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `content_en` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `unit_link` ;

CREATE TABLE `unit_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `village` ;

CREATE TABLE `village` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `number_code` int(11) DEFAULT NULL,
  `id_province` int(11) NOT NULL,
  `latitude` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL,
  `establish_date` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `name_en` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
