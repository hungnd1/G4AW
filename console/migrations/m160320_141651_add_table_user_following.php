<?php

use yii\db\Migration;

class m160320_141651_add_table_user_following extends Migration
{
    public function up()
    {
    $sql=<<<SQL

-- -----------------------------------------------------
-- Table `user_following`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_following` ;

CREATE TABLE IF NOT EXISTS `user_following` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `created_at` INT(11) NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `user_followed_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_user_following_user1_idx` (`user_id` ASC)  COMMENT '',
  INDEX `fk_user_following_user2_idx` (`user_followed_id` ASC)  COMMENT '',
  CONSTRAINT `fk_user_following_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_following_user2`
    FOREIGN KEY (`user_followed_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
SQL;
            $this->execute($sql);

    }

    public function down()
    {
        echo "m160320_141651_add_table_user_following cannot be reverted.\n";

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
