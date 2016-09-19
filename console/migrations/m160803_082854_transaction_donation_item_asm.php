<?php

use yii\db\Migration;

class m160803_082854_transaction_donation_item_asm extends Migration
{
    public function up()
    {
        $this->createTable('transaction_donation_item_asm', array(
            'id' => 'pk',
            'donation_item_id' => 'int not null',
            'transaction_id' => 'int not null',
            'donation_number' => 'int default 0',
            'status' => 'int default 10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    public function down()
    {
        echo "m160803_082854_transaction_donation_item_asm cannot be reverted.\n";

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
