<?php

use yii\db\Migration;

class m160928_065853_drop_column_es extends Migration
{
    public function up()
    {
        $this->dropColumn('village','establish_date');
    }

    public function down()
    {
        $this->createTable('column_es', [
            'id' => $this->primaryKey()
        ]);
    }
}
