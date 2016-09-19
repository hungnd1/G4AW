<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 20/03/2016
 * Time: 1:01 PM
 */

namespace frontend\controllers;


use common\models\User;
use yii\authclient\ClientInterface;
use yii\web\Controller;

class SocialController extends Controller
{
    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }
    /**
     * This function will be triggered when user is successfuly authenticated using some oAuth client.
     *
     * @param  ClientInterface $client
     * @return boolean
     */
    public function oAuthSuccess($client) {
        // get user data from client
        $userAttributes = $client->getUserAttributes();
        $user = User::loginViaFacebook($userAttributes);
        if (\Yii::$app->getUser()->login($user)) {
            return true;
        }
        return false;
        // do some thing with user data. for example with $userAttributes['email']
    }
}