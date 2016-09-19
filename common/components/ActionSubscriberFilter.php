<?php
/**
 * Created by PhpStorm.
 * User: qhuy
 * Date: 21/05/2015
 * Time: 09:42
 */
namespace common\components;

;

use common\helpers\CUtils;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\User;

class ActionSubscriberFilter extends ActionFilter
{
    public $subscriber = null;

    public function beforeAction($action)
    {

        if($this->subscriber == null){
            Yii::error('Subscriber null!!!!');
            $this->denyAccess();
        }
        return parent::beforeAction($action);
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess()
    {
        throw new ForbiddenHttpException('Bạn phải sử dụng 3G của Mobifone mới truy cập được trang web này');
    }

}
