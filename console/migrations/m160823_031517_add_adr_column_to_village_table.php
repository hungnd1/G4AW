<?php

use yii\db\Migration;

/**
 * Handles adding adr to table `village`.
 */
class m160823_031517_add_adr_column_to_village_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('village', 'adr', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('village', 'adr');
    }
}
