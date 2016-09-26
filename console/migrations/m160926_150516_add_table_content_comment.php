<?php

use yii\db\Migration;

class m160926_150516_add_table_content_comment extends Migration
{
    public function up()
    {
        $this->createTable('comment', array(
            'id' => 'pk',
            'id_new'=>'int',
            'content' => 'varchar(500)',
            'status'=>'int',
            'type'=>'int',
            'user_id'=>'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    public function down()
    {
        echo "m160926_150516_add_table_content_comment cannot be reverted.\n";

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
