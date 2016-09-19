<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class Muser extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['birthday', 'default', 'value' => null],
            ['email', 'email', 'message' => 'Địa chỉ email không hợp lệ!'],
            ['email', 'unique', 'message' => 'Tài khoản email của bạn đã được đăng ký trên hệ thống!'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
}
