<?php

use yii\db\Migration;

class m160314_154040_add_donation_request_column extends Migration
{
    public function up()
    {
        $this->addColumn('donation_request','organization_id','int(11)');
        $this->addForeignKey('organization_fk_1','donation_request','organization_id','user','id');
    }

    public function down()
    {
        echo "m160314_154040_add_donation_request_column cannot be reverted.\n";

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
