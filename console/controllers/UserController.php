<?php

/**
 * Swiss army knife to work with user and rbac in command line
 * @author: Nguyen Chi Thuc
 * @email: gthuc.nguyen@gmail.com
 */
namespace console\controllers;

use common\auth\helpers\AuthHelper;
use common\helpers\StringUtils;
use common\models\AuthItem;
use common\models\User;
use ReflectionClass;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\rbac\DbManager;
use yii\rbac\Item;

/**
 * UserController create user in commandline
 */
class UserController extends Controller
{
    /**
     * Sample: ./yii be-user/create-admin-user "thucnc@vivas.vn" "123456"
     * @param $email
     * @param $password
     * @throws Exception
     */
    public function actionCreateAdminUser($email, $password) {
        $this->actionCreateUser('admin', $email, $password, User::TYPE_ADMIN);
    }


    public function actionCreateUser($username, $email, $password,  $type, $full_name = "") {
        $user = new User();
        $user->username = $username;
        $user->type = $type;
//        $user->full_name = $full_name;
        $user->email = $email;
//        $user->type = $type;
        $user->setPassword($password);
        $user->generateAuthKey();


        if ($user->save()) {
            echo 'User created!\n';
            return 0;
        }
        else {
            Yii::error($user->getErrors());
            VarDumper::dump($user->getErrors());
            throw new Exception("Cannot create User!");
        }
    }

    public function actionSetPassword($username, $password) {
        /* @var $user User */
        $user = User::findByUsername($username);
        if (!$user) {
            echo "User not found!\n";
        }

        $user->setPassword($password);

        if ($user->save()) {
            echo 'Password changed!\n';
            return 0;
        }
        else {
            Yii::error($user->getErrors());
            VarDumper::dump($user->getErrors());
            throw new Exception("Cannot change password!");
        }
    }


}
