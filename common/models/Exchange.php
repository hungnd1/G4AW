<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exchange".
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property integer $total_quantity
 * @property integer $type_coffee
 * @property string $location
 * @property string $location_name
 * @property string $price
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $province_id
 */
class Exchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_coffee','price'],'required'],
            [['subscriber_id', 'total_quantity','sold_id', 'type_coffee', 'created_at', 'updated_at','province_id'], 'integer'],
            [['location','price','location_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber_id' => 'Subscriber ID',
            'total_quantity' => 'Tổng sản lượng',
            'type_coffee' => 'Loại coffee',
            'price' => 'Giá cafe',
            'location' => 'Vị trí',
            'province_id' => 'Tỉnh',
            'location_name' => 'Vị trí',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
