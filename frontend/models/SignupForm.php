<?php
namespace frontend\models;

use common\models\User;
use frontend\helpers\UserHelper;
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
            [['username'], 'required','message'=>UserHelper::multilanguage('Tên đăng nhập không được để trống','Username not empty')],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => UserHelper::multilanguage('Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!','Username already exists, please choose another name!')],
            [['username'], 'integer', 'message' => UserHelper::multilanguage('Tên đăng nhập phải là số điện thoại','Username is phone number')],
            ['username', 'is8NumbersOnly'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required','message'=>UserHelper::multilanguage('Email không được để trống','Email not empty')],
            ['birthday', 'default', 'value' => null],
            ['email', 'email', 'message' => UserHelper::multilanguage('Địa chỉ email không hợp lệ!','Email invalid')],
            ['captcha', 'required', 'message' => UserHelper::multilanguage('Mã captcha không được để trống.','Captcha code not empty')],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => UserHelper::multilanguage('Tài khoản email của bạn đã được đăng ký trên hệ thống!','Your email account is registered on the system!')],
            ['password', 'string', 'min' => 6, 'message' => UserHelper::multilanguage('Mật khẩu phải tối thiểu 6 ký tự','Passwords must be at least 6 characters')],
            ['password', 'checkPassword', 'on' => 'signup'],
            [['confirm_password'], 'required','message'=>UserHelper::multilanguage('Xác nhận mật khẩu không được để trống','Confirm password not empty')],
            [['password'], 'required','message'=>UserHelper::multilanguage('Mật khẩu không được để trống','Password not empty')],
            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'password',
                'message' => UserHelper::multilanguage('Xác nhận mật khẩu không khớp','Confirm password not match'),

            ],
            ['accept', 'compare', 'compareValue' => 1, 'message' => ''],
            [['address'], 'safe'],
            [['captcha'], 'captcha'],
        ];
    }

    public function checkPassword($attribute)
    {
        if (strlen($this->password) < '6') {
            $this->addError('password', UserHelper::multilanguage('Mật khẩu phải chứa tối thiểu 6 ký tự.','Password has least 6 character'));
        }
        elseif(!preg_match("@[0-9]@",$this->password)) {
            $this->addError('password', UserHelper::multilanguage('Mật khẩu phải chứa ít nhất 1 số.','Password has to least 1 number'));
        } elseif(!preg_match("@[A-Z]@",$this->password)) {
            $this->addError('password',  UserHelper::multilanguage('Mật khẩu phải chứa ít nhất 1 chữ viết hoa.','Password has to least 1 upper character'));
        }
    }

    public function is8NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{9}$/', $this->$attribute) && !preg_match('/^[0-9]{10}$/', $this->$attribute) && !preg_match('/^[0-9]{11}$/', $this->$attribute) ) {
            $this->addError($attribute, UserHelper::multilanguage('Tên đăng nhập phải là số điện thoại','Username must be phone number'));
        }
    }

        public
        function attributeLabels()
        {
            return [
                'username' => UserHelper::multilanguage('Tên đăng nhập','Username'),
                'phone_number' => UserHelper::multilanguage('Số điện thoại','Telephone'),
                'email' => UserHelper::multilanguage('Email','Email'),
                'address' => UserHelper::multilanguage('Địa chỉ','Address'),
                'password' => UserHelper::multilanguage('Mật khẩu','Password'),
                'confirm_password' => UserHelper::multilanguage('Xác nhận mật khẩu','Confirm password'),
                'captcha' => UserHelper::multilanguage('Mã captcha','Captcha code'),
                'accept' => UserHelper::multilanguage('Vui lòng đồng ý với quy định và điều khoản của trang (*)','Please agree as regulations and the terms of the page(*)')
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
