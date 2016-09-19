<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "request_gallery".
 *
 * @property integer $id
 * @property integer $donation_request_id
 * @property string $name
 * @property string $alt
 * @property double $size
 * @property double $width
 * @property double $height
 *
 * @property DonationRequest $donationRequest
 */
class RequestGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['donation_request_id', 'name'], 'required'],
            [['donation_request_id'], 'integer'],
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
            'donation_request_id' => 'Donation Request ID',
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
    public function getDonationRequest()
    {
        return $this->hasOne(DonationRequest::className(), ['id' => 'donation_request_id']);
    }

    public function getImage(){
        $image = $this->name;
        if ($image) {
            return Url::to(Yii::getAlias('@web') . '/' . Yii::getAlias('@uploads') . '/' . Yii::getAlias('@images') . '/' . $image, true);
        }
    }
}
