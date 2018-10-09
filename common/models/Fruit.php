<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "fruit".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property integer $parent_id
 * @property integer $have_child
 * @property integer $is_primary
 * @property integer $order
 */
class Fruit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fruit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'have_child', 'is_primary', 'order'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'parent_id' => 'Parent ID',
            'have_child' => 'Have Child',
            'is_primary' => 'Is Primary',
            'order' => 'Order',
        ];
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}
