<?php

use yii\db\Migration;

class m161025_090250_add_column_is_slide extends Migration
{
    public function up()
    {
        $this->addColumn('news','is_slide','int(11)');
    }

    public function down()
    {
        echo "m161025_090250_add_column_is_slide cannot be reverted.\n";

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
