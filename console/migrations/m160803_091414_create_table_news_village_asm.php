<?php

use yii\db\Migration;

class m160803_091414_create_table_news_village_asm extends Migration
{
    public function up()
    {
        $this->createTable('news_village_asm', array(
            'id' => 'pk',
            'news_id' => 'int not null',
            'village_id' => 'int not null',
            'status' => 'int default 10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));

        $this->addColumn('news','all_village','int default 0');
    }

    public function down()
    {
        echo "m160803_091414_create_table_news_village_asm cannot be reverted.\n";

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
