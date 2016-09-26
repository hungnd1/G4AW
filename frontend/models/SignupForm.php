<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $fullname;
    public $address;
    public $email;
    public $password;
    public $confirm_password;
    public $type;
    public $phone_number;
    public $captcha;
    public $accept;
    public $birthday;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!'],
            [['username'], 'integer', 'message' => 'Tên đăng nhập phải là số điện thoại'],
            ['username', 'is8NumbersOnly'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['birthday', 'default', 'value' => null],
            ['email', 'email', 'message' => 'Địa chỉ email không hợp lệ!'],
            ['captcha', 'required', 'message' => 'Mã captcha không được để trống.'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Tài khoản email của bạn đã được đăng ký trên hệ thống!'],
            ['password', 'string', 'min' => 6, 'message' => 'Mật khẩu phải tối thiểu 6 ký tự'],
            ['password', 'checkPassword', 'on' => 'create'],
            [['confirm_password', 'password'], 'required'],

            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Xác nhận mật khẩu không khớp',

            ],
            ['accept', 'compare', 'compareValue' => 1, 'message' => ''],
            [['address'], 'safe'],
            [['captcha'], 'captcha'],
        ];
    }

    public function checkPassword($attribute)
    {
        if (strlen($this->password) < '6') {
            $this->addError('password', 'Mật khẩu phải chứa tối thiểu 6 ký tự.');
        }
        elseif(!preg_match("@[0-9]@",$this->password)) {
            $this->addError('password', 'Mật khẩu phải chứa ít nhất 1 số.');
        } elseif(!preg_match("@[A-Z]@",$this->password)) {
            $this->addError('password', 'Mật khẩu phải chứa ít nhất 1 chữ viết hoa.');
        }
    }

    public function is8NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{9}$/', $this->$attribute) && !preg_match('/^[0-9]{10}$/', $this->$attribute) && !preg_match('/^[0-9]{11}$/', $this->$attribute) ) {
            $this->addError($attribute, 'Tên đăng nhập phải là số điện thoại');
        }
    }

        public
        function attributeLabels()
        {
            return [
                'username' => Yii::t('app', 'Tên đăng nhập'),
                'phone_number' => Yii::t('app', 'Số điện thoại'),
                'email' => Yii::t('app', 'Email'),
                'address' => Yii::t('app', 'Địa chỉ'),
                'password' => Yii::t('app', 'Mật khẩu'),
                'confirm_password' => Yii::t('app', 'Xác nhận mật khẩu'),
                'captcha' => Yii::t('app', 'Mã captcha'),
                'accept' => Yii::t('app', 'Vui lòng đồng ý với quy định và điều khoản của trang (*)')
            ];
        }

        /**
         * Signs user up.
         *
         * @return User|null the saved model or null if saving fails
         */
        public
        function signup()
        {
            if (!$this->validate()) {
                return null;
            }

//        $result = ForumHelper::createNewUser($this->username, $this->password, $this->email);
//        $result = true;
//        Yii::info($result);

            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->address = $this->address;
            $user->phone_number = $this->username;
            $user->birthday = $this->birthday;
            $user->password_reset_token = $this->password;
            $user->type = User::TYPE_USER;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            return $user->save() ? $user : null;

        }
    }
