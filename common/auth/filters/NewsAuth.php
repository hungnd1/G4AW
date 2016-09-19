<?php
/**
 * @author Nguyen Chi Thuc
 * @email gthuc.nguyen@gmail.com
 */

namespace common\auth\filters;

use common\models\News;
use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\User;

class NewsAuth extends ActionFilter
{

    public $superAdmin = 'admin';

    public $db = 'db';

    public $routeField = 'data';

    public $authManager = 'authManager';

    public $except = [];

    /**
     * @var bool default filter result if no permission found for given route
     */
    public $autoAllow = false;

    /**
     * @var User|string the user object representing the authentication status or the ID of the user application component.
     */
    public $user = 'user';
    /**
     * @var callable a callback that will be called if the access should be denied
     * to the current user. If not set, [[denyAccess()]] will be called.
     *
     * The signature of the callback should be as follows:
     *
     * ~~~
     * function ($rule, $action)
     * ~~~
     *
     * where `$rule` is the rule that denies the user, and `$action` is the current [[Action|action]] object.
     * `$rule` can be `null` if access is denied because none of the rules matched.
     */
    public $denyCallback;

    /**
     * @var callable a callback that will be called to check user is admin
     */
    public $validateAdminCallback;

    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     */
    public function init()
    {
        parent::init();
        $this->user = Instance::ensure($this->user, User::className());
        $this->db = Instance::ensure($this->db, Connection::className());
        $this->authManager = Instance::ensure($this->authManager, DbManager::className());

    }

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        $user = $this->user;

        if ($user && isset($user->identity->username) && $user->identity->username === $this->superAdmin) {
            return true;
        }

        /**
         * Check user is admin via callback function
         */
        if (isset($this->validateAdminCallback)) {
            if (call_user_func($this->validateAdminCallback, $user)) {
                return true;
            }
        }

        $owner = $this->owner;
        /* @var $owner Controller */
        foreach ($this->except as $exception) {
            if ($exception === $action->id) {
                return true;
            }
        }


        $route = $owner->route;
        Yii::info('Requested route: ' . $route, 'RouteAC');

        if ($user) {
            $type = \Yii::$app->request->get('type', 0);
            Yii::info($type, 'type news');
            Yii::info($user->getIdentity()->type, 'type user');

            if ($type == News::TYPE_COMMON) {
                Yii::info('vao day', 'type news');
                if ($user->getIdentity()->type != \common\models\User::TYPE_MINISTRY_EDITOR) {
                    Yii::info('vao day nua', 'type news');
                    $this->denyAccess($user);
                }
            }


            if ($type == News::TYPE_IDEA || $type == News::TYPE_TRADE || $type == News::TYPE_CAMPAIGN || $type == News::TYPE_DONOR) {
                if ($user->getIdentity()->type != \common\models\User::TYPE_LEAD_DONOR) {
                    $this->denyAccess($user);
                }
            }
        } else {
            $this->denyAccess($user);
        }

        return true;
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'Bạn không có quyền truy cập tính năng này.'));
        }
    }
}
