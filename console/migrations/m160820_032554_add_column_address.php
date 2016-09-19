<?php

use yii\db\Migration;

class m160820_032554_add_column_address extends Migration
{
    public function up()
    {
        $this->addColumn('transaction','address','varchar(256)');
    }

    public function down()
    {
        echo "m160820_032554_add_column_address cannot be reverted.\n";

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
