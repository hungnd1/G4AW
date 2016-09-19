<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 22/05/2015
 * Time: 2:28 PM
 */
namespace api\controllers;

use api\models\CategoryItem;
use api\models\Organization;
use common\models\User;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\base\InvalidValueException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class UserController extends ApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'index',
            'login',
            'register',
            'organization',
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'login' => ['POST'],
            'register' => ['POST'],
            'profile' => ['GET'],
            'update-profile' => ['POST'],
            'organization' => ['GET'],
        ];
    }

    public function actionRegister()
    {
        $type = $this->getParameterPost("type", 0);
        $username = $this->getParameterPost("username", "");
        $password = $this->getParameterPost("password", "");
        $email = $this->getParameterPost("email", "");
        $full_name = $this->getParameterPost("full_name", "");
        $phone_number = $this->getParameterPost("phone_number", "");
        $address = $this->getParameterPost("address", "");

        if ($username == "") {
            throw new BadRequestHttpException("Không được để trống tên đăng nhập");
        }

        if ($password == "") {
            throw new BadRequestHttpException("Không được để trống mật khẩu");
        }

        if ($full_name == "") {
            throw new BadRequestHttpException("Không được để trống tên cá nhân tổ chức");
        }

        if ($type == 0) {
            throw new BadRequestHttpException("Vui lòng chọn loại tài khoản");
        }

        $user = User::findOne(['username' => $username]);
        if ($user) {
            throw new BadRequestHttpException("Tên đăng nhập đã được sử dụng, vui lòng chọn tên khác");
        }

        $user = User::newUser($username, $password, $type, $full_name, $phone_number, $address, $email);

        if ($user) {
            $uploadedFile = UploadedFile::getInstanceByName('avatar_file');

            if ($uploadedFile) {
                $ext = $uploadedFile->getExtension();
                if (!$ext) {
                    $ext = ".jpg";
                    Yii::info("Empty extension, set to .jpg manually");
                }
                $file_save = Yii::$app->security->generateRandomString() . ".{$ext}";
                Yii::info($file_save, "File name");
                $folder = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Yii::getAlias('@avatar') . DIRECTORY_SEPARATOR;
                if (!is_dir($folder)) {
                    FileHelper::createDirectory($folder);
                }
                $path = $folder . $file_save;
                $uploadedFile->saveAs($path);
                $user->avatar = $file_save;
                $user->save();
            } else {
                Yii::error("Cannot save file", "ERROR UPLOAD");
            }
        } else {
            throw new InternalErrorException("Hệ thống đang bận, vui lòng thử lại");
        }

        return ['message' => 'Đăng ký tài khoản thành công'];
    }

    public function actionUpdateProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $email = $this->getParameterPost("email", "");
        $full_name = $this->getParameterPost("full_name", "");
        $phone_number = $this->getParameterPost("phone_number", "");
        $address = $this->getParameterPost("address", "");

        if ($email != "") {
            $user->email = $email;
        }

        if ($full_name != "") {
            $user->fullname = $full_name;
        }

        if ($phone_number != "") {
            $user->phone_number = $phone_number;
        }

        if ($address != "") {
            $user->address = $phone_number;
        }

        $uploadedFile = UploadedFile::getInstanceByName('avatar_file');

        if ($uploadedFile) {
            $ext = $uploadedFile->getExtension();
            if (!$ext) {
                $ext = ".jpg";
                Yii::info("Empty extension, set to .jpg manually");
            }
            $file_save = Yii::$app->security->generateRandomString() . ".{$ext}";
            Yii::info($file_save, "File name");
            $folder = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Yii::getAlias('@avatar') . DIRECTORY_SEPARATOR;
            if (!is_dir($folder)) {
                FileHelper::createDirectory($folder);
            }
            $path = $folder . $file_save;
            $uploadedFile->saveAs($path);
            $user->avatar = $file_save;
        } else {
            Yii::error("Cannot save file", "ERROR UPLOAD");
        }
        if ($user->save()) {
            $res['id'] = $user->id;
            $res['username'] = $user->username;
            $res["access_token"] = $user->userTokens[0] ? $user->userTokens[0]->token : "";
            $res["full_name"] = $user->fullname;
            $res['email'] = $user->email;
            $res['phone_number'] = $user->phone_number;
            $res['address'] = $user->address;
            $res['status'] = $user->status;
            $res['type'] = $user->type;
            $res['avatar'] = $user->getAvatar();
            $res['user_code'] = $user->user_code;
            return $res;
        } else {
            throw new InternalErrorException("Hệ thống đang bận vui lòng thử lại sau");
        }

    }

    public function actionProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $res['id'] = $user->id;
        $res['username'] = $user->username;
        $res["access_token"] = $user->userTokens[0] ? $user->userTokens[0]->token : "";
        $res["full_name"] = $user->fullname;
        $res['email'] = $user->email;
        $res['phone_number'] = $user->phone_number;
        $res['address'] = $user->address;
        $res['status'] = $user->status;
        $res['type'] = $user->type;
        $res['avatar'] = $user->getAvatar();
        $res['user_code'] = $user->user_code;

        return $res;
    }

    public function actionChangePassword($old_password, $new_password)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ( $user->validatePassword($old_password)) {
            $user->setPassword($new_password);
            if ($user->save()) {
                return ['message' => "Đổi mật khẩu thành công"];
            } else {
                throw new InternalErrorException("Hệ thống đang bận, vui lòng thử lại");
            }
        }else{
            throw new BadRequestHttpException("Mật khẩu cũ không đúng");
        }



    }

    public function actionLogin()
    {
        $username = $this->getParameterPost("username", "");
        $password = $this->getParameterPost("password", "");

        /** Valid data*/
        if ($username == '' && $password == '') {
            throw new BadRequestHttpException("Vui lòng điền tên đăng nhập và mật khẩu");
        }

        /** @var User $user */
        $user = User::findByUsernameActive($username);
        if (!$user) {
            throw new BadRequestHttpException("Tài khoản không tồn tại hoặc chưa kích hoạt trên hệ thống");
        }

        if ($user->validatePassword($password)) {
            $userToken = $user->generateAccessToken();

            if (!$userToken) {
                throw new InternalErrorException("Hệ thống đang bận, vui lòng thử lại");
            }
            $res['id'] = $user->id;
            $res['username'] = $user->username;
            $res["access_token"] = $userToken;
            $res["full_name"] = $user->fullname;
            $res['email'] = $user->email;
            $res['phone_number'] = $user->phone_number;
            $res['address'] = $user->address;
            $res['status'] = $user->status;
            $res['type'] = $user->type;
            $res['avatar'] = $user->getAvatar();
            $res['user_code'] = $user->user_code;
        } else {
            throw new BadRequestHttpException("Sai tên đăng nhập hoặc mật khẩu");
        }
        return $res;
    }

    public function actionOrganization(){
        return Organization::listOrganization();
    }
}