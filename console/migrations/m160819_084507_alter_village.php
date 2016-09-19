<?php

use yii\db\Migration;

class m160819_084507_alter_village extends Migration
{
    public function up()
    {
        $this->alterColumn('village', 'natural_area', 'varchar(500)');
        $this->alterColumn('village', 'arable_area', 'varchar(500)');
        $this->alterColumn('village', 'population', 'varchar(500)');
        $this->alterColumn('village', 'poor_family', 'varchar(500)');
        $this->alterColumn('village', 'poor_family', 'varchar(500)');
        $this->alterColumn('village', 'no_house_family', 'varchar(500)');
        $this->alterColumn('village', 'missing_classes', 'varchar(500)');
        
    }

    public function down()
    {
        echo "m160819_084507_alter_village cannot be reverted.\n";

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
