<?php

use yii\db\Migration;

class m160818_093520_add_column_village extends Migration
{
    public function up()
    {
        $this->addColumn('village', 'latitude', 'integer default 0');
        $this->addColumn('village', 'longitude', 'integer default 0');
    }

    public function down()
    {
        echo "m160818_093520_add_column_village cannot be reverted.\n";

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
