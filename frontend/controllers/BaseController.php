<?php
namespace frontend\controllers;

use common\models\AuthItem;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class BaseController extends Controller
{
    /** @var $seller User */
    protected $user=null;

    public function init(){

        $user = Yii::$app->user;
        if(!$user->isGuest){
            $this->user = User::findOne($user->id);

        }

    }
    public function getParameter($param_name, $default = null) {
        return \Yii::$app->request->get($param_name, $default);
    }

    /**
     * get value of parameter
     *
     * @param $param_name
     * @param null $default
     * @return mixed
     */
    public function getParameterPost($param_name, $default = null) {
        return \Yii::$app->request->post($param_name, $default);
    }
}
