<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%donation_item}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $unit
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class DonationItem extends \yii\db\ActiveRecord
{

    public $expected_number;
    public $current_number;
    public $number_least;


    const STATUS_ACTIVE = 10;
    const STATUS_DEACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%donation_item}}';
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
            [['name', 'unit'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'unit'], 'string', 'max' => 256],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Tên'),
            'unit' => Yii::t('app', 'Đơn vị'),
            'description' => Yii::t('app', 'Mô tả'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getFullname()
    {
        return $this->name . "(" . $this->unit . ")";
    }

    public function getUsedStatus(){
        $campaign_donation_item_asm = CampaignDonationItemAsm::findOne(['donation_item_id' => $this->id]);
        $transaction_donation_item_asm = TransactionDonationItemAsm::findOne(['donation_item_id' => $this->id]);


        if ($campaign_donation_item_asm || $transaction_donation_item_asm) {
           return true;
        }

        return false;
    }
}
