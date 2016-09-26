<?php
namespace frontend\models;

use common\models\User;
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
            [['username'], 'required', 'on' => 'create', 'message' => 'Tên đăng nhập không được để trống'],
            [['username'], 'unique', 'on' => 'create', 'message' => 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!'],
            [['username'], 'string', 'on' => 'create', 'min' => 2, 'max' => 255],
            [['username'], 'integer','on' => 'create', 'message' => 'Tên đăng nhập phải là số điện thoại'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['birthday', 'default', 'value' => null],
            [['gender'], 'integer'],
            [['fullname'], 'string', 'max' => 512],
            [['address'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Địa chỉ email không hợp lệ!'],
            ['email', 'unique', 'message' => 'Tài khoản email của bạn đã được đăng ký trên hệ thống!'],
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
