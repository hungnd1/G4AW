<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bank}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 256],
            [['image'], 'image', 'extensions' => 'png,jpg,jpeg,gif',
                'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Ảnh upload vượt quá dung lượng cho phép!'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getBankImage()
    {
        return $this->image ? Yii::getAlias('@web') . "/" . Yii::getAlias('@bank_image') . "/" . $this->image : '../img/bank-icon.jpg';
    }
}
