<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_following".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $user_id
 * @property integer $user_followed_id
 */
class UserFollowing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_following';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'user_id', 'user_followed_id'], 'integer'],
            [['user_id', 'user_followed_id'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_followed_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_followed_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
            'user_followed_id' => 'User Followed ID',
        ];
    }

    /**
     * @inheritdoc
     * @return UserFollowingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserFollowingQuery(get_called_class());
    }
}
