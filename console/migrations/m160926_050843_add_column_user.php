<?php

use yii\db\Migration;

class m160926_050843_add_column_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','fb_email','VARCHAR(255)');
        $this->addColumn('user','fb_id','VARCHAR(50)');
        $this->addColumn('user','gender','SMALLINT(6)');
        $this->addColumn('user','birthday','DATETIME');
    }

    public function down()
    {
        echo "m160926_050843_add_column_user cannot be reverted.\n";

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
