<?php

use yii\db\Migration;

/**
 * Handles adding pass1 to table `user`.
 */
class m160926_031810_add_pass1_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'pass2', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'pass2');
    }
}
