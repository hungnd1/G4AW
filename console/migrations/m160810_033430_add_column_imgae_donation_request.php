<?php

use yii\db\Migration;

class m160810_033430_add_column_imgae_donation_request extends Migration
{
    public function up()
    {
        $this->addColumn('donation_request', 'image', 'varchar(500)');
    }

    public function down()
    {
        echo "m160810_033430_add_column_imgae_donation_request cannot be reverted.\n";

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
