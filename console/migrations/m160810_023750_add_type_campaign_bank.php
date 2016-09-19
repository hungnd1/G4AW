<?php

use yii\db\Migration;

class m160810_023750_add_type_campaign_bank extends Migration
{
    public function up()
    {
        $this->addColumn('campaign_bank_asm', 'type', 'int default 1');
        $this->addColumn('campaign_bank_asm', 'content', 'text');
    }

    public function down()
    {
        echo "m160810_023750_add_type_campaign_bank cannot be reverted.\n";

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
