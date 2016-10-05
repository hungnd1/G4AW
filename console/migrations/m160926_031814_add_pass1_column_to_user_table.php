<?php

use yii\db\Migration;

/**
 * Handles adding pass1 to table `user`.
 */
class m160926_031814_add_pass1_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'pass3', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'pass3');
    }
}
