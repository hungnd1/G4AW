<?php

use yii\db\Migration;

/**
 * Handles adding pass1 to table `user`.
 */
class m160926_031805_add_pass1_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'pass1', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'pass1');
    }
}
