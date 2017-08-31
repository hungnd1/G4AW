<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "gap_general".
 *
 * @property integer $id
 * @property string $gap
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $title
 * @property string $image
 * @property integer $type
 * @property double $temperature_max
 * @property double $temperature_min
 * @property double $evaporation
 * @property double $humidity
 */
class GapGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gap_general';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const GAP_GENERAL = 1;
    const GAP_DETAIL = 2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gap','image'], 'string'],
            [['status', 'created_at', 'updated_at', 'type'], 'integer'],
            [['temperature_max', 'temperature_min', 'evaporation', 'humidity'], 'number'],
            [['title'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gap' => 'Gap',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'title' => 'Title',
            'type' => 'Type',
            'temperature_max' => 'Temperature Max',
            'temperature_min' => 'Temperature Min',
            'evaporation' => 'Evaporation',
            'humidity' => 'Humidity',
        ];
    }

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@news_image') . DIRECTORY_SEPARATOR . $this->image, true) : Yii::$app->getUrlManager()->getBaseUrl().'/img/blank.jpg' ;
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
}
