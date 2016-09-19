<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_token}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $token
 * @property integer $type
 * @property string $ip_address
 * @property integer $created_at
 * @property integer $expired_at
 * @property string $cookies
 * @property integer $status
 * @property integer $channel
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token'], 'required'],
            [['user_id', 'type', 'created_at', 'expired_at', 'status', 'channel'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['token'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 45],
            [['cookies'], 'string', 'max' => 1000]
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
            'token' => Yii::t('app', 'Token'),
            'type' => Yii::t('app', 'Type'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'created_at' => Yii::t('app', 'Created At'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'cookies' => Yii::t('app', 'Cookies'),
            'status' => Yii::t('app', 'Status'),
            'channel' => Yii::t('app', 'Channel'),
        ];
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

    /**
     * @param $token
     * @return UserToken
     */
    public static function findByAccessToken($token)
    {
        return UserToken::find()
            ->andWhere(['status' => UserToken::STATUS_ACTIVE, 'token' => $token])
//            ->andWhere("expired_at is null OR expired_at > :time", [":time" => time()])
            ->one();
    }
}
