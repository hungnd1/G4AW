<?php

use yii\db\Migration;

class m161002_031234_drop_column_areaid extends Migration
{
    public function up()
    {
        $this->dropColumn('news','area_id');
    }

    public function down()
    {
        $this->createTable('column_areaid', [
            'id' => $this->primaryKey()
        ]);
    }
}
