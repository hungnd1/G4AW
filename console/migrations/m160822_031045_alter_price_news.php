<?php

use yii\db\Migration;

class m160822_031045_alter_price_news extends Migration
{
    public function up()
    {
        $this->alterColumn('news','price','varchar(256)');
    }

    public function down()
    {
        echo "m160822_031045_alter_price_news cannot be reverted.\n";

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
