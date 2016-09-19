<?php

use yii\db\Migration;

class m160320_065405_create_table_report_donation extends Migration
{
    public function up()
    {
        $this->createTable('report_donation', array(
            'id' => 'pk',
            'report_date' => 'datetime',
            'organization_id' => 'int',
            'campaign_id' => 'int',
            'revenues' => 'double(10,2) default 0',
            'donate_count' => 'int default 0',
        ));
    }

    public function down()
    {
        echo "m160320_065405_create_table_report_donation cannot be reverted.\n";
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
