<?php

use yii\db\Migration;

class m160315_074820_add_table_request_gallery extends Migration
{
    public function up()
    {
        $sql=<<<SQL
-- -----------------------------------------------------
-- Table `request_gallery`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `request_gallery` ;

CREATE TABLE IF NOT EXISTS `request_gallery` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `donation_request_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `alt` VARCHAR(255) NULL COMMENT '',
  `size` DOUBLE NULL COMMENT '',
  `width` DOUBLE NULL COMMENT '',
  `height` DOUBLE NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_request_gallery_donation_request1_idx` (`donation_request_id` ASC)  COMMENT '',
  CONSTRAINT `fk_request_gallery_donation_request1`
    FOREIGN KEY (`donation_request_id`)
    REFERENCES `donation_request` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
SQL;
    $this->execute($sql);
    }

    public function down()
    {
        echo "m160315_074820_add_table_request_gallery cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
