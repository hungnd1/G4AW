<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%campaign_donation_item_asm}}".
 *
 * @property integer $id
 * @property integer $donation_item_id
 * @property integer $campaign_id
 * @property number $expected_number
 * @property number $current_number
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Campaign $campaign
 * @property DonationItem $donationItem
 */
class CampaignDonationItemAsm extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%campaign_donation_item_asm}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['donation_item_id', 'campaign_id', 'expected_number'], 'required'],
            [['donation_item_id', 'campaign_id', 'status', 'created_at',
                'updated_at','current_number','expected_number'], 'integer'],
            ['expected_number', 'compare', 'compareValue' => 0, 'operator' => '>', 'message'=>'Trường Số lượng phải là kiểu số nguyên dương'],
//            [['expected_number', 'current_number'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'donation_item_id' => Yii::t('app', 'Tên'),
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'expected_number' => Yii::t('app', 'Số lượng'),
            'current_number' => Yii::t('app', 'Current Number'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonationItem()
    {
        return $this->hasOne(DonationItem::className(), ['id' => 'donation_item_id']);
    }
}
