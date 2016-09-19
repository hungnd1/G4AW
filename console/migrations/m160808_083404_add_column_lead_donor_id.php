<?php

use yii\db\Migration;

class m160808_083404_add_column_lead_donor_id extends Migration
{
    public function up()
    {
        $this->addColumn('news', 'lead_donor_id', 'int');
    }

    public function down()
    {
        echo "m160808_083404_add_column_lead_donor_id cannot be reverted.\n";

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
