<?php

use yii\db\Migration;

class m160819_085047_alter_gdp_village extends Migration
{
    public function up()
    {
        $this->alterColumn('village', 'gdp', 'varchar(500)');
    }

    public function down()
    {
        echo "m160819_085047_alter_gdp_village cannot be reverted.\n";

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
