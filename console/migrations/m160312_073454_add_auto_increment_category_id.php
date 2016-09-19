<?php

use yii\db\Migration;

class m160312_073454_add_auto_increment_category_id extends Migration
{
    public function up()
    {
//        $this->alterColumn('category','id','INT(11) NOT NULL AUTO_INCREMENT ');
    }

    public function down()
    {
        echo "m160312_073454_add_auto_increment_category_id cannot be reverted.\n";

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
