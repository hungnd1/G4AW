<?php

use yii\db\Migration;

class m160802_080344_create_table_donation_item extends Migration
{
    public function up()
    {
        $this->createTable('donation_item', array(
            'id' => 'pk',
            'name' => 'varchar(256) not null',
            'unit' => 'varchar(256) not null',
            'status' => 'int default 10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));

        $this->createTable('campaign_donation_item_asm', array(
            'id' => 'pk',
            'donation_item_id' => 'int not null',
            'campaign_id' => 'int not null',
            'expected_number' => 'int not null',
            'current_number' => 'int default 0',
            'status' => 'int default 10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));

    }

    public function down()
    {
        echo "m160802_080344_create_table_donation_item cannot be reverted.\n";

        return false;
    }

}
