<?php

use yii\db\Migration;

class m160809_092327_remove_not_null_transaction extends Migration
{
    public function up()
    {
        $this->alterColumn('transaction','user_id','int default null');
    }

    public function down()
    {
        echo "m160809_092327_remove_not_null_transaction cannot be reverted.\n";
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
