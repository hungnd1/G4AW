<?php

use yii\db\Migration;

class m160319_163914_add_campaign_start_end_column extends Migration
{
    public function up()
    {
        $this->addColumn('campaign','started_at','int(11)');
        $this->addColumn('campaign','finished_at','int(11)');
    }

    public function down()
    {
        echo "m160319_163914_add_campaign_start_end_column cannot be reverted.\n";

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
