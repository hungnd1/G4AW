<?php

use yii\db\Migration;

class m160809_034407_add_column_donation_number_trasaction extends Migration
{
    public function up()
    {
        $this->addColumn('transaction','donation_item_id','int');
        $this->addColumn('transaction','donation_number','double(12,2)');
        $this->alterColumn('campaign_donation_item_asm','expected_number','double(12,2) default 0');
        $this->alterColumn('campaign_donation_item_asm','current_number','double(12,2) default 0');
    }

    public function down()
    {
        echo "m160809_034407_add_column_donation_number_trasaction cannot be reverted.\n";

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
