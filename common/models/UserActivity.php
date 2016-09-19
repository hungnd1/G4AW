<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_activity}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $ip_address
 * @property string $user_agent
 * @property string $action
 * @property integer $target_id
 * @property integer $target_type
 * @property integer $created_at
 * @property string $description
 * @property string $status
 * @property integer $site_id
 * @property integer $dealer_id
 * @property string $request_detail
 * @property string $request_params
 *
 * @property User $user
 */
class UserActivity extends \yii\db\ActiveRecord
{
    const ACTION_TARGET_TYPE_USER = 1;
    const ACTION_TARGET_TYPE_CAT = 2;
    const ACTION_TARGET_TYPE_CONTENT = 3;
    const ACTION_TARGET_TYPE_SUBSCRIBER = 4;
    const ACTION_TARGET_TYPE_SERVICE_PROVIDER = 5;
    const ACTION_TARGET_TYPE_CONTENT_PROVIDER = 6;
    const ACTION_TARGET_TYPE_PRICE_LEVEL = 7;
    const ACTION_TARGET_TYPE_SERVICE = 8;
    const ACTION_TARGET_TYPE_CREDENTIAL = 9;
    const ACTION_TARGET_TYPE_MO_SYNTAX = 10;
    const ACTION_TARGET_TYPE_MT_TEMPLATE = 11;
    const ACTION_TARGET_TYPE_OTHER = 12;


    public static $action_targets = [
        self::ACTION_TARGET_TYPE_USER => 'User',
        self::ACTION_TARGET_TYPE_CAT => 'Category',
        self::ACTION_TARGET_TYPE_CONTENT => 'Content',
        self::ACTION_TARGET_TYPE_SUBSCRIBER => 'Subscriber',
        self::ACTION_TARGET_TYPE_SERVICE_PROVIDER => 'Service Provider',
        self::ACTION_TARGET_TYPE_CONTENT_PROVIDER => 'Content Provider',
        self::ACTION_TARGET_TYPE_PRICE_LEVEL => 'Price Level',
        self::ACTION_TARGET_TYPE_SERVICE => 'Service',
        self::ACTION_TARGET_TYPE_CREDENTIAL => 'API Key',
        self::ACTION_TARGET_TYPE_MO_SYNTAX => 'MO Syntax',
        self::ACTION_TARGET_TYPE_MT_TEMPLATE => 'MT Template',
        self::ACTION_TARGET_TYPE_OTHER => 'Other'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'target_id', 'target_type', 'created_at', 'site_id', 'dealer_id'], 'integer'],
            [['description', 'request_params'], 'string'],
            [['username', 'user_agent', 'status'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 45],
            [['action'], 'string', 'max' => 126],
            [['request_detail'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'action' => Yii::t('app', 'Action'),
            'target_id' => Yii::t('app', 'Target ID'),
            'target_type' => Yii::t('app', 'Target Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'site_id' => Yii::t('app', 'Site ID'),
            'dealer_id' => Yii::t('app', 'Dealer ID'),
            'request_detail' => Yii::t('app', 'Request Detail'),
            'request_params' => Yii::t('app', 'Request Params'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
