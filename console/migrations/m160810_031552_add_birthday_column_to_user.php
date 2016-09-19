<?php

use yii\db\Migration;

class m160810_031552_add_birthday_column_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'birthday', $this->datetime());
    }

    public function down()
    {
        $this->dropColumn('user', 'birthday');
    }
}
