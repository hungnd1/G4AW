<?php

use yii\db\Migration;

class m160319_040726_alter_table_campaign extends Migration
{
    public function up()
    {
            $this->alterColumn('campaign','created_for_user','int(11)');
    }

    public function down()
    {
        echo "m160319_040726_alter_table_campaign cannot be reverted.\n";

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
