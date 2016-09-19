<?php

use yii\db\Migration;

class m160802_065934_create_table_lead_donor extends Migration
{
    public function up()
    {
        $this->addColumn('village','lead_donor_id','int');
        $this->createTable('lead_donor', array(
            'id' => 'pk',
            'name' => 'varchar(256) not null',
            'address' => 'varchar(256)',
            'website' => 'varchar(256)',
            'status' => 'int default 10',
            'image' => 'varchar(256)',
            'description' => 'text',
            'phone' => 'varchar(20)',
            'email' => 'varchar(64)',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    public function down()
    {
        echo "m160802_065934_create_table_lead_donor cannot be reverted.\n";

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
