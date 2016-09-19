<?php

use yii\db\Migration;

class m160811_072758_add_column_price_news extends Migration
{
    public function up()
    {
        $this->addColumn('news', 'price', 'integer default 0');
    }

    public function down()
    {
        echo "m160811_072758_add_column_price_news cannot be reverted.\n";

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
