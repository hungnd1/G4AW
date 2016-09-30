<?php
namespace frontend\models;

use common\models\User;
use frontend\helpers\UserHelper;
use Yii;

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
            ['username', 'filter', 'filter' => 'trim'],
            [['username'], 'required', 'on' => 'create', 'message' => UserHelper::multilanguage('Tên đăng nhập không được để trống','Username not empty')],
            [['username'], 'unique', 'on' => 'create', 'message' => UserHelper::multilanguage('Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!','Username already exists, please choose another name!')],
            [['username'], 'string', 'on' => 'create', 'min' => 2, 'max' => 255],
            [['username'], 'integer','on' => 'create', 'message' => UserHelper::multilanguage('Tên đăng nhập phải là số điện thoại','Username is phone number')],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['birthday', 'default', 'value' => null],
            [['gender'], 'integer'],
            [['fullname'], 'string', 'max' => 512],
            [['address'], 'string', 'max' => 255],
            ['email', 'email', 'message' => UserHelper::multilanguage('Email không được để trống','Email not empty')],
            ['email', 'unique', 'message' => UserHelper::multilanguage('Tài khoản email của bạn đã được đăng ký trên hệ thống!','Your email account is registered on the system!')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Tên đăng nhập'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
}
