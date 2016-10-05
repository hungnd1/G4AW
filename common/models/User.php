<?php

namespace common\models;

use common\helpers\CommonUtils;
use common\helpers\CUtils;
use frontend\helpers\UserHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $fullname
 * @property string $user_code
 * @property string $phone_number
 * @property string $avatar
 * @property string $cover_photo
 * @property string $email
 * @property string $address
 * @property string $other_profile
 * @property integer $individual
 * @property string $auth_key
 * @property string $pass1
 * @property string $pass2
 * @property string $pass3
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property string $access_login_token
 * @property string $fb_email
 * @property string $fb_id
 * @property integer $gender
 * @property string $birthday
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property News[] $news
 * @property UserActivity[] $userActivities
 * @property UserToken[] $userTokens
 * @property UserFollowing[] $follows
 */
class User extends ActiveRecord implements IdentityInterface
{
    const TYPE_ADMIN = 1;
    const TYPE_MINISTRY_EDITOR = 2;    // tai khoan quan ly cac bai viet chung
    const TYPE_LEAD_DONOR = 3;      // tai khoan cua doanh nghiep do dau
    const TYPE_VILLAGE = 4;         // tai khoan cua xa
    const TYPE_USER = 5;            // tai khoan cua nguoi dung
    const TYPE_MANAGER = 6;         // tai khoan cua manager


    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;


    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    public static function listGender()
    {
        $lst = [
            self::GENDER_MALE => UserHelper::multilanguage('Nam','Male'),
            self::GENDER_FEMALE => UserHelper::multilanguage('Nữ','Female'),
        ];
        return $lst;
    }

    public static function getGenderName($type)
    {
        $lst = self::listGender();
        if (array_key_exists($type, $lst)) {
            return $lst[$type];
        }
        return $type;
    }

    public static function getOld($birthday)
    {
        if ($birthday != null) {
            $y = date('Y', strtotime($birthday));
            $ynow = date('Y');
//        echo "<pre>";
//        print_r($ynow);
//        die();
            $old = $ynow - $y;
            return $old;
        } else {
            return null;
        }
    }

    public $access_token;
    /*
    * @var string password for register scenario
    */
    public $password;
    public $confirm_password;
    public $new_password;
    public $old_password;
    public $file_excel;
    public $setting_new_password;

    public function scenarios()
    {

        return ArrayHelper::merge(parent::scenarios(), [
            'user-setting' => ['setting_new_password', 'old_password', 'confirm_password'],

        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        if ($insert) {
            $code = str_pad($this->id, 4, "0", STR_PAD_LEFT);
            $prefix = '';
//            } else if ($this->type == self::TYPE_DONOR) {
//                $prefix = 'DN';
//            }
            $this->user_code = $prefix . $code;
            $this->save(false);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key', 'password_hash'], 'required'],

            ['username', 'filter', 'filter' => 'trim'],
            [['username'], 'required', 'on' => 'create', 'message' => 'Tên đăng nhập không được để trống'],
            [['username'], 'unique', 'on' => 'create', 'message' => 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!'],
            [['username'], 'string', 'on' => 'create', 'min' => 2, 'max' => 255],


            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Địa chỉ email không được để trống'],
            ['email', 'email', 'message' => 'Địa chỉ email không hợp lệ!'],
            ['email', 'string', 'max' => 255],
//            ['email', 'unique', 'on' => 'create', 'message' => 'Địa chỉ Email đã tồn tại.'],

            [['other_profile'], 'string'],
            [['birthday'], 'safe'],
            [['individual', 'role', 'status', 'created_at', 'updated_at', 'gender', 'type'], 'integer'],
            [['avatar', 'cover_photo', 'address','pass1','pass2','pass3', 'password_hash', 'password_reset_token', 'access_login_token', 'fb_email', 'fb_id'], 'string', 'max' => 255],
            [['fullname'], 'string', 'max' => 512],
            [['user_code'], 'string', 'max' => 20],
//            [['phone_number'], 'integer', 'message'=>'Vui lòng nhập kiểu số'],
            [['auth_key', 'phone_number'], 'string', 'max' => 32],
            [['avatar'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif'], 'maxSize' => 1024 * 1024 * 10, 'tooBig' => 'Dung lượng ảnh vượt quá 10mb'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['birthday', 'default', 'value' => null],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_INACTIVE]],

//            ['village_id', 'required','message'=>'Vui lòng chọn xã'],
//            ['lead_donor_id', 'required','message'=>'Vui lòng chọn doanh nghiệp đỡ đầu'],

            // validate for create
            ['password', 'string', 'min' => '6', 'tooShort' => 'Mật khẩu phải tối thiểu 6 ký tự'],
            ['password', 'checkPassword', 'on' => 'create'],
            ['old_password', 'string'],
            ['confirm_password', 'string'],
            ['new_password', 'string', 'min' => '6', 'tooShort' => 'Mật khẩu phải tối thiểu 6 ký tự'],
            [['confirm_password'], 'required', 'message' => 'Xác nhận mật khẩu không được để trống.', 'on' => 'create'],
            [['password'], 'required', 'message' => 'Mật khẩu không được để trống.', 'on' => 'create'],
            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'password',
                'message' => UserHelper::multilanguage('Xác nhận mật khẩu  không đúng.','Confirm  password not match'),
                'on' => 'create'
            ],
            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'new_password',
                'message' => UserHelper::multilanguage('Xác nhận mật khẩu  không đúng.','Confirm  password not match'),
                'on' => 'change-password'
            ],
            [['new_password'], 'required', 'message' => 'Mật khẩu không được để trống.', 'on' => 'change-password'],
            [['confirm_password'], 'required', 'message' => 'Xác nhận mật khẩu không được để trống.', 'on' => 'change-password'],
            [['file_excel'], 'file', 'extensions' => 'xlsx, xls'],


            [['file_excel', 'setting_new_password', 'old_password', 'fb_email', 'fb_id'], 'safe'],
            [['setting_new_password'], 'required', 'message' => UserHelper::multilanguage('Mật khẩu mới không được để trống.','New password not empty'), 'on' => 'user-setting'],
            [['setting_new_password'], 'string', 'min' => 6, 'message' => UserHelper::multilanguage('Mật khẩu mới không được để trống.','New password not empty'), 'on' => 'user-setting'],
            [['old_password'], 'required', 'message' => UserHelper::multilanguage('Mật khẩu cũ không được để trống.','Old password not empty'), 'on' => 'user-setting'],
            ['old_password', 'validator_password', 'on' => 'user-setting'],
            ['setting_new_password','checkNewPassword','on' => 'user-setting'],
            ['setting_new_password','validate_new','on' => 'user-setting'],
            ['new_password', 'checkPassword', 'on' => 'user-setting'],
            [['confirm_password'], 'required', 'message' => UserHelper::multilanguage('Xác nhận mật khẩu không được để trống.','Confirm password not empty'), 'on' => 'user-setting'],
            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'setting_new_password',
                'message' => UserHelper::multilanguage('Xác nhận mật khẩu mới không đúng.','Confirm new password not match'),
                'on' => 'user-setting'
            ],
//            [
//                'phone_number',
////                'match', 'pattern' => '/^0[0-9]$/',
//                'match', 'pattern' => '/^(0)\d{9,10}$/',
//                'message' => 'Số điện thoại không hợp lệ - Định dạng số điện thoại bắt đầu với số 0, ví dụ 0912345678, 012312341234'
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Tên đăng nhập'),
            'fullname' => Yii::t('app', 'Tên đầy đủ'),
            'user_code' => Yii::t('app', 'User Code'),
            'phone_number' => Yii::t('app', 'Số điện thoại'),
            'avatar' => Yii::t('app', 'Avatar'),
            'cover_photo' => Yii::t('app', 'Cover Photo'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Địa chỉ'),
            'other_profile' => Yii::t('app', 'Other Profile'),
            'individual' => Yii::t('app', 'Individual'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Trạng thái'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'access_login_token' => Yii::t('app', 'Access Login Token'),
            'setting_new_password' => Yii::t('app', 'Mật khẩu mới'),
            'old_password' => UserHelper::multilanguage('Mật khẩu cũ','Old password'),
            'confirm_password' => UserHelper::multilanguage('Xác nhận mật khẩu','Confirm password'),
            'new_password' => UserHelper::multilanguage('Mật khẩu mới','New password'),
            'gender' => Yii::t('app', 'Giới tính'),
            'birthday' => Yii::t('app', 'Ngày sinh'),
        ];
    }


    public function validate_new($attribute, $params){
        $id = Yii::$app->user->identity->id;
        $model = User::findOne($id);
        $pass1 = $model->pass1;
        $pass2 = $model->pass2;
        $pass3 = $model->pass3;
        if($pass1 != null) {
            if (Yii::$app->security->validatePassword($this->setting_new_password, $pass1)) {
                $this->addError('setting_new_password', 'Mật khẩu mới không được trùng với mật khẩu ba lần gần nhất');
            }else {
                if ($pass2 != null) {
                    if (Yii::$app->security->validatePassword($this->setting_new_password, $pass2)) {
                        $this->addError('setting_new_password', 'Mật khẩu mới không được trùng với mật khẩu ba lần gần nhất');
                    }else {
                        if ($pass3 != null) {
                            if (Yii::$app->security->validatePassword($this->setting_new_password, $pass3)) {
                                $this->addError('setting_new_password', 'Mật khẩu mới không được trùng với mật khẩu ba lần gần nhất');
                            }
                        }
                    }
                }
            }
        }
    }

    public function checkNewPassword($attribute)
    {
        if (strlen($this->setting_new_password) < '6') {
            $this->addError('setting_new_password', 'Mật khẩu phải chứa tối thiểu 6 ký tự.');
        }
        elseif(!preg_match("@[0-9]@",$this->setting_new_password)) {
            $this->addError('setting_new_password', 'Mật khẩu phải chứa ít nhất 1 số.');
        } elseif(!preg_match("@[A-Z]@",$this->setting_new_password)) {
            $this->addError('setting_new_password', 'Mật khẩu phải chứa ít nhất 1 chữ viết hoa.');
        }
    }


    public function validator_password($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->validatePassword($this->old_password)) {
                $this->addError('old_password', UserHelper::multilanguage('Mật khẩu cũ không đúng.','Old password wrong'));
            }
        }
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('{{%auth_assignment}}', ['user_id' => 'id']);
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollows()
    {
        return $this->hasMany(UserFollowing::className(), ['user_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['user_id' => 'id']);
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserActivities()
    {
        return $this->hasMany(UserActivity::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokens()
    {
        return $this->hasMany(UserToken::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => [self::STATUS_ACTIVE]]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $userToken = UserToken::findByAccessToken($token);
        if ($userToken) {
            $user = $userToken->getUser();
            if ($user) {
                $user->access_token = $token;
            }

            return $user;
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => [self::STATUS_ACTIVE]]);
    }

    public static function findByUsernameFE($username)
    {
        return static::findOne(['username' => $username, 'status' => [self::STATUS_ACTIVE],'type'=>User::TYPE_USER]);
    }

    public static function findByUsernameActive($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByAdminUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE,
            'type' => [self::TYPE_ADMIN, self::TYPE_MINISTRY_EDITOR,self::TYPE_VILLAGE]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public function getAvatar()
    {
        //TODO get partner avatar
        $avatar = Yii::$app->params['avatar'];
        $pathLink = Yii::getAlias("@web/" . $avatar . "/");
        $filename = null;
        if ($this->avatar) {
            $filename = $this->avatar;
        }
        if (!$filename) {
            $pathLink = Yii::getAlias("@web/img/");
            $filename = 'avt_df.png';
        }

        return Url::to($pathLink . $filename, true);

    }
    /**
     * ******************************** MY FUNCTION ***********************
     */

    /**
     * @return ActiveDataProvider
     */
    public function getAuthItemProvider()
    {
        return new ActiveDataProvider([
            'query' => $this->getAuthItems()
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return AuthItem::find()->andWhere(['name' => AuthAssignment::find()->select(['item_name'])->andWhere(['user_id' => $this->id])]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissingRoles()
    {
        $roles = AuthItem::find()->andWhere(['type' => AuthItem::TYPE_ROLE])
            ->andWhere('name not in (select item_name from auth_assignment where user_id = :id)', [':id' => $this->id]);

        return $roles->all();
    }

    /**
     * @return string
     */
    public function getRolesName()
    {
        $str = "";
        $roles = $this->getAuthItems()->all();
        $action = 'rbac/update-role';
        foreach ($roles as $role) {
            $res = Html::a($role['description'], [$action, 'name' => $role['name']]);
            $res .= " [" . sizeof($role['children']) . "]";
            $str = $str . $res . '  ,';
        }
        return $str;
    }

    /**
     * @return array
     */
    public static function listType()
    {
        $lst = [
            self::TYPE_ADMIN => 'Admin',
            self::TYPE_MINISTRY_EDITOR => 'Ban biên tập tin tức',
            self::TYPE_VILLAGE => 'Tài khoản xã',
            self::TYPE_USER => 'Người dùng',
        ];
        return $lst;
    }

    public static function getTypeNameByID($type)
    {
        $lst = self::listType();
        if (array_key_exists($type, $lst)) {
            return $lst[$type];
        }
        return $type;
    }

    /**
     * @return int
     */
    public function getTypeName()
    {
        $lst = self::listType();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }

    /**
     * @return array
     */
    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm khóa',
//            self::STATUS_DELETED => 'Bị xóa',
        ];
        return $lst;
    }

    /**
     * @return int
     */
    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    public static function getItemNameFromAuthAssignment($id_user, $type)
    {
        $auth = new AuthAssignment();
        if ($type == User::TYPE_ADMIN) {
            $auth->item_name = 'Admin';
            $auth->user_id = $id_user;
            if ($auth->save()) {
            } else {
                return Yii::$app->session->setFlash('error', 'Tạo quyền cho tài khoản không thành công!');
            };
        }
        if ($type == User::TYPE_MINISTRY_EDITOR) {
            $auth->item_name = 'Editor';
            $auth->user_id = $id_user;
            if ($auth->save()) {
            } else {
                return Yii::$app->session->setFlash('error', 'Tạo quyền cho tài khoản không thành công!');
            };
        }


    }

    public static function checkUser($username, $pass)
    {
        $user = User::findOne(['username' => $username]);
        if ($user) {
            if ($user->validatePassword($pass)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    public static function newUser($username, $password, $type, $full_name, $phone_number, $address, $email)
    {
        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->fullname = $full_name;
        $user->phone_number = $phone_number;
        $user->address = $address;
        $user->email = $email;
        $user->type = $type;
        $user->password_reset_token = $password;
        $user->status = User::STATUS_ACTIVE;
//        else {
//            $user->status = User::STATUS_WAITING;
//        }
        $user->setPassword($password);

        if ($user->save()) {
            $user->setUserCode();
            return $user;
        } else {
            Yii::error($user->getErrors());
            return null;
        }

    }


    public function followCampaign($campaign)
    {
        $campaignFollowing = CampaignFollowing::find()->andWhere(['user_id' => $this->id])->andWhere(['campaign_id' => $campaign->id])->one();
        if ($campaignFollowing) {
            $campaignFollowing->delete();
            return 1;
        }
        $campaignFollowing = new CampaignFollowing();
        $campaignFollowing->user_id = $this->id;
        $campaignFollowing->campaign_id = $campaign->id;
        return $campaignFollowing->save();
    }

    public function getName()
    {
        return $this->fullname ? $this->fullname : $this->username;
    }


    public static function loginViaFacebook($data)
    {
        $email = isset($data['email']) ? $data['email'] : '';
        $fbId = isset($data['id']) ? $data['id'] : '';
        $fullname = isset($data['name']) ? $data['name'] : '';
        /** @var User $user */
        $user = User::findOne(['fb_email' => $email]);
        if (!$user) {
            $user = new User();
            $user->username = $email;
            $user->email = $email;
            $user->fb_id = $fbId;
            $user->fb_email = $email;
            $user->type = self::TYPE_MINISTRY_EDITOR;
            $user->phone_number = $fbId;
            $password = time();
            $user->password_reset_token = $password;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->save();
        }
        return $user ? $user : null;
    }

    public function generateAccessToken()
    {
        $userToken = new UserToken();
        $userToken->token = Yii::$app->security->generateRandomString();
        $userToken->user_id = $this->id;
        $userToken->created_at = time();
        $userToken->expired_at = time() + 30 * 86400;
        $userToken->status = UserToken::STATUS_ACTIVE;

        if ($userToken->save()) {
            return $userToken->token;
        }

        return null;

    }

    public function totalMyCampaign()
    {
        return $this->getCampaigns()->count();
    }

    public function totalRequestToMe()
    {
        return $this->getDonationRequestsTo()->count();
    }

    public function totalMyCampaignDone()
    {
        return $this->getCampaigns()->andWhere(['campaign.status' => Campaign::STATUS_DONE])->count();
    }

    public function totalMyCampaignActive()
    {
        return $this->getCampaigns()->andWhere(['campaign.status' => Campaign::STATUS_ACTIVE])->count();
    }

    /**
     * @param $followed User
     * @return bool|int
     * @throws \Exception
     */
    public function followUser($followed)
    {
        $userFollowing = UserFollowing::find()->andWhere(['user_id' => $this->id])->andWhere(['user_followed_id' => $followed->id])->one();
        if ($userFollowing) {
            $userFollowing->delete();
            return 1;
        }
        $userFollow = new UserFollowing();
        $userFollow->user_id = $this->id;
        $userFollow->user_followed_id = $followed->id;
        return $userFollow->save();
    }

}
