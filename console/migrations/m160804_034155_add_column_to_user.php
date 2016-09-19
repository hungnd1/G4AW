<?php

use yii\db\Migration;

class m160804_034155_add_column_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','lead_donor_id','int');
        $this->addColumn('user','village_id','int');
    }

    public function down()
    {
        echo "m160804_034155_add_column_to_user cannot be reverted.\n";

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
