<?php

use yii\db\Migration;

class m160812_051241_alter_campaign_code extends Migration
{
    public function up()
    {
        $this->alterColumn('campaign','campaign_code','varchar(64) default "CP"');
    }

    public function down()
    {
        echo "m160812_051241_alter_campaign_code cannot be reverted.\n";

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
