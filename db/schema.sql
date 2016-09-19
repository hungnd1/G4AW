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
-- Table `donation_request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `donation_request` ;

CREATE TABLE IF NOT EXISTS `donation_request` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(1024) NOT NULL COMMENT 'title cua chien dich',
  `short_description` VARCHAR(500) NULL,
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - tien\n2 - hien vat',
  `content` TEXT NULL COMMENT 'HTML content',
  `expected_amount` DOUBLE NOT NULL DEFAULT 0,
  `status` INT(11) NOT NULL DEFAULT 0 COMMENT '0 - new (chua duyet)\n9 - dang duyet\n10 - active\n1 - expired\n2 - inactive\n5 - rejected\n',
  `admin_note` VARCHAR(4000) NULL COMMENT 'ly do reject...',
  `current_amount` DOUBLE NOT NULL DEFAULT 0,
  `currency` VARCHAR(20) NULL DEFAULT 'VND',
  `approved_at` INT(11) NULL,
  `created_by` INT(11) NOT NULL,
  `created_at` INT(11) NULL,
  `updated_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_name` (`title` ASC),
  INDEX `idx_short_desc` (`short_description`(255) ASC),
  INDEX `idx_is_deleted` (`status` ASC),
  INDEX `fk_campaign_user1_idx` (`created_by` ASC),
  CONSTRAINT `fk_campaign_user10`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 20
COMMENT = 'TODO: thong tin ve cac thuoc tinh nhu dao dien, tac gia, ca ';


-- -----------------------------------------------------
-- Table `campaign`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `campaign` ;

CREATE TABLE IF NOT EXISTS `campaign` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `donation_request_id` INT(11) NULL COMMENT 'campaign cho request nao',
  `name` VARCHAR(1024) NOT NULL COMMENT 'title cua chien dich',
  `ascii_name` VARCHAR(200) NULL COMMENT 'string khong dau cua name --> de search',
  `short_description` VARCHAR(500) NULL,
  `thumbnail` VARCHAR(200) NULL COMMENT 'danh sach cac images, json encoded\n',
  `campaign_code` VARCHAR(20) NOT NULL COMMENT 'ma de mua noi dung (qua SMS)',
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - tien\n2 - hien vat',
  `tags` VARCHAR(500) NULL,
  `description` TEXT NULL,
  `content` TEXT NULL COMMENT 'HTML content',
  `view_count` INT(11) NOT NULL DEFAULT '0',
  `like_count` INT(11) NOT NULL DEFAULT '0',
  `comment_count` INT(11) NOT NULL DEFAULT '0',
  `follower_count` INT(11) NOT NULL DEFAULT '0',
  `status` INT(11) NOT NULL DEFAULT 0 COMMENT '0 - new (chua duyet)\n9 - dang duyet\n10 - active\n1 - expired\n2 - inactive\n5 - rejected\n',
  `admin_note` VARCHAR(4000) NULL COMMENT 'ly do reject...',
  `expected_amount` DOUBLE NOT NULL DEFAULT 0,
  `current_amount` DOUBLE NOT NULL DEFAULT 0,
  `donor_count` INT NOT NULL DEFAULT 0,
  `currency` VARCHAR(20) NULL DEFAULT 'VND',
  `honor` INT(11) NULL DEFAULT '0' COMMENT '0 --> nothing\n1 --> featured\n2 --> hot\n3 --> especial',
  `approved_at` INT(11) NULL,
  `created_by` INT(11) NOT NULL,
  `created_for_user` INT(11) NOT NULL,
  `created_at` INT(11) NULL,
  `updated_at` INT(11) NULL,
  `donation_status` SMALLINT NOT NULL DEFAULT 0 COMMENT '10 - gold reach',
  PRIMARY KEY (`id`),
  INDEX `idx_name` (`name` ASC),
  INDEX `idx_tags` (`tags`(255) ASC),
  INDEX `idx_short_desc` (`short_description`(255) ASC),
  INDEX `idx_desc` (`description`(255) ASC),
  INDEX `idx_view_count` (`view_count` ASC),
  INDEX `idx_like_count` (`like_count` ASC),
  INDEX `idx_comment_count` (`comment_count` ASC),
  INDEX `idx_favorite_count` (`follower_count` ASC),
  INDEX `idx_is_deleted` (`status` ASC),
  UNIQUE INDEX `code_UNIQUE` (`campaign_code` ASC),
  INDEX `fk_campaign_user1_idx` (`created_by` ASC),
  INDEX `fk_campaign_user2_idx` (`created_for_user` ASC),
  INDEX `fk_campaign_donation_request1_idx` (`donation_request_id` ASC),
  CONSTRAINT `fk_campaign_user1`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaign_user2`
    FOREIGN KEY (`created_for_user`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaign_donation_request1`
    FOREIGN KEY (`donation_request_id`)
    REFERENCES `donation_request` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 20
COMMENT = 'TODO: thong tin ve cac thuoc tinh nhu dao dien, tac gia, ca ';


-- -----------------------------------------------------
-- Table `campaign_category_asm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `campaign_category_asm` ;

CREATE TABLE IF NOT EXISTS `campaign_category_asm` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_vod_category_asset_mapping_vod_asset1_idx` (`campaign_id` ASC),
  INDEX `fk_vod_category_asset_mapping_vod_category1_idx` (`category_id` ASC),
  CONSTRAINT `fk_vod_category_asset_mapping_vod_asset1`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `campaign` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vod_category_asset_mapping_vod_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 18;


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
-- Table `campaign_following`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `campaign_following` ;

CREATE TABLE IF NOT EXISTS `campaign_following` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `campaign_id` INT(11) NOT NULL,
  `created_at` INT NULL,
  `updated_at` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_campaign_following_user1_idx` (`user_id` ASC),
  INDEX `fk_campaign_following_campaign1_idx` (`campaign_id` ASC),
  CONSTRAINT `fk_campaign_following_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaign_following_campaign1`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `campaign` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
COMMENT = 'nguoi tao, nguoi request, nguoi donate mac dinh la follower (add luon record vao bang nay)';


-- -----------------------------------------------------
-- Table `news`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `news` ;

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NULL COMMENT 'tin tuc cua campaign nao',
  `title` VARCHAR(512) NOT NULL,
  `title_ascii` VARCHAR(512) NULL,
  `content` TEXT NULL DEFAULT NULL COMMENT 'HTML content',
  `thumbnail` VARCHAR(512) NULL COMMENT 'anh de hien thi trong list',
  `type` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - html news\n2 - video (thumbnail c� n�t play)',
  `tags` VARCHAR(200) NULL,
  `short_description` VARCHAR(500) NULL,
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
  PRIMARY KEY (`id`),
  INDEX `idx_name` (`title` ASC),
  INDEX `idx_view_count` (`view_count` ASC),
  INDEX `idx_like_count` (`like_count` ASC),
  INDEX `idx_comment_count` (`comment_count` ASC),
  INDEX `idx_favorite_count` (`favorite_count` ASC),
  INDEX `idx_status` (`status` ASC),
  INDEX `fk_news_campaign1_idx` (`campaign_id` ASC),
  INDEX `fk_news_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_news_campaign1`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `campaign` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_news_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 20
COMMENT = 'TODO: thong tin ve cac thuoc tinh nhu dao dien, tac gia, ca ';


-- -----------------------------------------------------
-- Table `campaign_related_asm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `campaign_related_asm` ;

CREATE TABLE IF NOT EXISTS `campaign_related_asm` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `source_id` INT(11) NOT NULL,
  `destination_id` INT(11) NOT NULL,
  `relation_type` SMALLINT NOT NULL DEFAULT 1,
  `description` VARCHAR(255) NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_vod_category_asset_mapping_vod_asset1_idx` (`source_id` ASC),
  INDEX `fk_campaign_related_asm_campaign1_idx` (`destination_id` ASC),
  CONSTRAINT `fk_vod_category_asset_mapping_vod_asset10`
    FOREIGN KEY (`source_id`)
    REFERENCES `campaign` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaign_related_asm_campaign1`
    FOREIGN KEY (`destination_id`)
    REFERENCES `campaign` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DYNAMIC
AUTO_INCREMENT = 18;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
