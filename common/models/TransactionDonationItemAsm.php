<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%transaction_donation_item_asm}}".
 *
 * @property integer $id
 * @property integer $donation_item_id
 * @property integer $transaction_id
 * @property integer $donation_number
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Transaction $transaction
 * @property DonationItem $donationItem
 */
class TransactionDonationItemAsm extends \yii\db\ActiveRecord
{
    const STATUS_SUCCESS = 10;


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_donation_item_asm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['donation_item_id', 'transaction_id'], 'required'],
            [['donation_item_id', 'transaction_id', 'donation_number', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'donation_item_id' => Yii::t('app', 'Donation Item ID'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'donation_number' => Yii::t('app', 'Donation Number'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDonationItem()
    {
        return $this->hasOne(DonationItem::className(), ['id' => 'donation_item_id']);
    }
}
