<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $description
 * @property integer $status
 * @property integer $order_number
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'required'],
            [['description'], 'string'],
            [['status', 'order_number', 'created_at', 'updated_at'], 'integer'],
            [['display_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Display Name',
            'description' => 'Description',
            'status' => 'Status',
            'order_number' => 'Order Number',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
