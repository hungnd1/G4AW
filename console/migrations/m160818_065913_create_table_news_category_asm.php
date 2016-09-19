<?php

use yii\db\Migration;

class m160818_065913_create_table_news_category_asm extends Migration
{
    public function up()
    {
        $this->createTable('news_category_asm', array(
            'id' => 'pk',
            'news_id' => 'int not null',
            'category_id' => 'int not null',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    public function down()
    {
        echo "m160818_065913_create_table_news_category_asm cannot be reverted.\n";

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
