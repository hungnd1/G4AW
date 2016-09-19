<?php

use yii\db\Migration;

class m160320_061111_add_user_fb_column extends Migration
{
    public function up()
    {

        $this->addColumn('user','fb_email','varchar(255)');
        $this->addColumn('user','fb_id','varchar(50)');

    }

    public function down()
    {
        echo "m160320_061111_add_user_fb_column cannot be reverted.\n";

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
