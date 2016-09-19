<?php

use yii\db\Migration;

class m160819_044225_add_video_leaddonor extends Migration
{
    public function up()
    {
        $this->addColumn('lead_donor', 'video', 'varchar(256)');
    }

    public function down()
    {
        echo "m160819_044225_add_video_leaddonor cannot be reverted.\n";

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
