<?php

use yii\db\Migration;

class m160802_072052_add_column_table_request extends Migration
{
    public function up()
    {
        $this->addColumn('donation_request','village_id','int');
        $this->addColumn('donation_request','lead_donor_id','int');
        $this->addColumn('donation_request','expected_items','varchar(512)');
    }

    public function down()
    {
        echo "m160802_072052_add_column_table_request cannot be reverted.\n";

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
