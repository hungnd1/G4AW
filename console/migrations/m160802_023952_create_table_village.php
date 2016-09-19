<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_village`.
 */
class m160802_023952_create_table_village extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('province', array(
            'id' => 'pk',
            'name' => 'varchar(45) not null',
            'display_name' => 'varchar(45)',
            'description' => 'varchar(256)',
            'status' => 'int default 10',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));

        $this->createTable('village', array(
            'id' => 'pk',
            'name' => 'varchar(256) not null',
            'province_name' => 'varchar(256)',
            'district_id' => 'int',
            'status' => 'int default 10',
            'image' => 'varchar(256)',
            'description' => 'text',
            'natural_area' => 'double(10,2)',
            'arable_area' => 'double(10,2)',
            'main_industry' => 'text',
            'main_product' => 'text',
            'population' => 'int',
            'poor_family' => 'int',
            'no_house_family' => 'int',
            'missing_classes' => 'int',
            'lighting_condition' => 'varchar(256)',
            'water_condition' => 'varchar(256)',
            'missing_playground' => 'varchar(256)',
            'created_at' => 'int',
            'updated_at' => 'int',
        ));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('table_village');
    }
}
