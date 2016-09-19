<?php

use yii\db\Migration;

class m160822_042949_create_table_comment extends Migration
{
    public function up()
    {
        $this->createTable('comment', array(
            'id' => 'pk',
            'village_id'=>'int',
            'content' => 'varchar(256)',
            'user_id'=>'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    public function down()
    {
        $this->dropTable('comment');
    }
}
