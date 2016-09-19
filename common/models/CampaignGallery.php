<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "campaign_gallery".
 *
 * @property integer $id
 * @property integer $campaign_id
 * @property string $name
 * @property string $alt
 * @property double $size
 * @property double $width
 * @property double $height
 *
 * @property Campaign $campaign
 */
class CampaignGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'campaign_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campaign_id', 'name'], 'required'],
            [['campaign_id'], 'integer'],
            [['size', 'width', 'height'], 'number'],
            [['name', 'alt'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaign_id' => 'Campaign ID',
            'name' => 'Name',
            'alt' => 'Alt',
            'size' => 'Size',
            'width' => 'Width',
            'height' => 'Height',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign_id']);
    }

    public function getImage()
    {
        $image = $this->name;
        if ($image) {
            return Url::to(Yii::getAlias('@web') . '/' . Yii::getAlias('@uploads') . '/' . Yii::getAlias('@images') . '/' . $image, true);
        }
    }
}
