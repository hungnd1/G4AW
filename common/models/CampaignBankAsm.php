<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%campaign_bank_asm}}".
 *
 * @property integer $id
 * @property integer $campaign_id
 * @property integer $bank_id
 * @property string $branch
 * @property string $account_number
 * @property string $account_owner
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property string $content
 *
 * @property Bank $bank
 * @property Campaign $campaign
 */
class CampaignBankAsm extends \yii\db\ActiveRecord
{
    const TYPE_BANK_ACCOUNT = 1;
    const TYPE_DIRECT_ADDRESS = 2;

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
        return '{{%campaign_bank_asm}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campaign_id', 'bank_id', 'created_at', 'updated_at', 'type'], 'integer'],
            [['content'], 'string'],
            [['branch', 'account_number', 'account_owner'], 'string', 'max' => 256],
            [['branch', 'account_number', 'account_owner', 'bank_id'], 'required', 'when' => function ($model) {
                return $model->type == self::TYPE_BANK_ACCOUNT;
            },
                'whenClient' => "function (attribute, value) {
                    return $('#type').val() == '1';
                }"
            ],
            [['content'], 'required', 'when' => function ($model) {
                return $model->type == self::TYPE_DIRECT_ADDRESS;
            },
                'whenClient' => "function (attribute, value) {
                    return $('#type').val() == '2';
                }"
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
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'bank_id' => Yii::t('app', 'Ngân hàng'),
            'branch' => Yii::t('app', 'Chi nhánh'),
            'account_number' => Yii::t('app', 'Số tài khoản'),
            'account_owner' => Yii::t('app', 'Chủ tài khoản'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Hình thức'),
            'content' => Yii::t('app', 'Thông tin'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'campaign_id']);
    }

    public static function getListType()
    {
        $lst = [
            self::TYPE_DIRECT_ADDRESS => Yii::t('app', 'Ủng hộ trực tiếp'),
            self::TYPE_BANK_ACCOUNT => Yii::t('app', 'Chuyển khoản'),
        ];
        return $lst;
    }
}
