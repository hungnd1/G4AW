<?php

use yii\db\Migration;

class m160810_030228_add_gender_column_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'gender', $this->smallInteger());
    }

    public function down()
    {
        $this->dropColumn('user', 'gender');
    }
}
