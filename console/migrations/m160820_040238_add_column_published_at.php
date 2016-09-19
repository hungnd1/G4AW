<?php

use yii\db\Migration;

class m160820_040238_add_column_published_at extends Migration
{
    public function up()
    {
        $this->addColumn('news','published_at','int');
        $this->addColumn('campaign','published_at','int');
    }

    public function down()
    {
        echo "m160820_040238_add_column_published_at cannot be reverted.\n";

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
