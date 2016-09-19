<?php

use yii\db\Migration;

class m160808_033534_add_column_village extends Migration
{
    public function up()
    {
        $this->addColumn('village','establish_date','date');
        $this->addColumn('village','geographical_location','varchar(500)');
        $this->addColumn('village','map_images','varchar(500)');
    }

    public function down()
    {
        echo "m160808_033534_add_column_village cannot be reverted.\n";

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
