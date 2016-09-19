<?php

use yii\db\Migration;

class m160822_070644_alter_column_latitude extends Migration
{
    public function up()
    {
        $this->alterColumn('village','latitude','double default 0');
        $this->alterColumn('village','longitude','double default 0');
    }

    public function down()
    {
        echo "m160822_070644_alter_column_latitude cannot be reverted.\n";

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
