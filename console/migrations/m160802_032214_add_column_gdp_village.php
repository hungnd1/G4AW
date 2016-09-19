<?php

use yii\db\Migration;

class m160802_032214_add_column_gdp_village extends Migration
{
    public function up()
    {
        $this->addColumn('village', 'gdp', 'double(10,2)');
    }

    public function down()
    {
        echo "m160802_032214_add_column_gdp_village cannot be reverted.\n";

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
