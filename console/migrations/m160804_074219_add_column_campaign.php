<?php

use yii\db\Migration;

class m160804_074219_add_column_campaign extends Migration
{
    public function up()
    {
        $this->addColumn('campaign','village_id','int');
        $this->addColumn('campaign','lead_donor_id','int');
        $this->addColumn('campaign','direct_donation_address','text');

        $this->createTable('bank', array(
            'id' => 'pk',
            'name' => 'varchar(256)',
            'image' => 'varchar(256)',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));

        $this->createTable('campaign_bank_asm', array(
            'id' => 'pk',
            'campaign_id' => 'int',
            'bank_id' => 'int',
            'branch' => 'varchar(256)',
            'account_number' => 'varchar(256)',
            'account_owner' => 'varchar(256)',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    public function down()
    {
        echo "m160804_074219_add_column_campaign cannot be reverted.\n";

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
