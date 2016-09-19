<?php

use yii\db\Migration;

class m160810_041406_add_description_donation_item extends Migration
{
    public function up()
    {
        $this->addColumn('donation_item', 'description', 'text');
    }

    public function down()
    {
        echo "m160810_041406_add_description_donation_item cannot be reverted.\n";

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
