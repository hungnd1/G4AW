<?php
namespace frontend\models;

use common\models\Subscriber;
use common\models\User;
use frontend\helpers\UserHelper;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required','message'=>UserHelper::multilanguage('Tên đăng nhập không được để trống','Username not empty')],
            [[ 'password'], 'required','message'=>UserHelper::multilanguage('Mật khẩu không được để trống','Password not empty')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, UserHelper::multilanguage('Tên đăng nhập hoặc mật khẩu không đúng.','Username or password wrong'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Subscriber::findByUsernameFE($this->username);
        }
        Yii::warning($this->_user);

        return $this->_user;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => UserHelper::multilanguage('Tên đăng nhập','Username'),
            'password' =>UserHelper::multilanguage('Mật khẩu','Password'),
            'rememberMe' =>UserHelper::multilanguage('Remember password','Nhớ mật khẩu')

        ];
    }
}
