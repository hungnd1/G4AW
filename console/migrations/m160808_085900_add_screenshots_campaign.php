<?php

use yii\db\Migration;

class m160808_085900_add_screenshots_campaign extends Migration
{
    public function up()
    {
        $this->addColumn('campaign','screenshots','text');
    }

    public function down()
    {
        echo "m160808_085900_add_screenshots_campaign cannot be reverted.\n";

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
