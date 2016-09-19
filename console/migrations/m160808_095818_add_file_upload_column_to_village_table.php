<?php

use yii\db\Migration;

class m160808_095818_add_file_upload_column_to_village_table extends Migration
{
    public function up()
    {
        $this->addColumn('village', 'file_upload', 'varchar(500)');
    }

    public function down()
    {
        $this->dropColumn('village', 'file_upload');
    }
}
