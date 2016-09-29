<?php
/**
 * Created by PhpStorm.
 * User: thuc
 * Date: 10/17/14
 * Time: 3:39 PM
 */

namespace frontend\helpers;


use Yii;

class UserHelper {
    const SESSION_USER_ID = 'user_id';
    const SESSION_MSISDN = 'MSISDN';
    const SESSION_CAS = 'CAS';
    const SESSION_ACCESS_TOKEN = 'user_access_token';
    const SESSION_DISPLAY_NAME = 'username';
    const SESSION_AVATAR = 'avartar';

    /**
     * @param $loginApiResult
     * @param int $duration
     */
    public static function loginGooglePlus($loginApiResult, $duration = 0) {
        $data = $loginApiResult['data'];
        if ($data['access_token']) {
            Yii::$app->session->set(UserHelper::SESSION_ACCESS_TOKEN, $data['access_token']);
            Yii::$app->session->set(UserHelper::SESSION_USER_ID, $data['id']);
            isset($data['picture'])? Yii::$app->session->set(UserHelper::SESSION_AVATAR, $data['picture']): '';
        }

        Yii::$app->session->set(UserHelper::SESSION_DISPLAY_NAME, $data['display_name']);
    }

    /**
     * get user login avatar
     *
     * @return mixed
     */
    public static function getUserLoginAvatar() {
        return Yii::$app->session->get(UserHelper::SESSION_AVATAR);
    }
    /**
     * @param $loginApiResult
     * @param int $duration
     */
    public static function login($loginApiResult, $duration = 0) {
        $data = $loginApiResult['data'];
        if ($data['access_token']) {
            Yii::$app->session->set(UserHelper::SESSION_MSISDN, $data['username']);
            isset($data['avartar'])? Yii::$app->session->set(UserHelper::SESSION_AVATAR, $data['avartar']): '';
        }

        Yii::$app->session->set(UserHelper::SESSION_DISPLAY_NAME, $data['display_name']);
    }

    public static function logout() {
        Yii::$app->session->remove(UserHelper::SESSION_MSISDN);
        Yii::$app->session->remove(UserHelper::SESSION_DISPLAY_NAME);
        Yii::$app->session->remove(UserHelper::SESSION_AVATAR);
    }

    public static function isGuest() {
        return self::getMsisdn() == null;
    }

    public static function getAccessToken() {
        return Yii::$app->session->get(UserHelper::SESSION_ACCESS_TOKEN);
    }

    public static function getAvatar() {
        return Yii::$app->session->get(UserHelper::SESSION_AVATAR) ? Yii::$app->session->get(UserHelper::SESSION_AVATAR) : Yii::$app->request->baseUrl.'/advance/img/avatar_default.png';
    }
    public static function getUserName() {
        return Yii::$app->session->get(UserHelper::SESSION_DISPLAY_NAME);
    }

    public static function getMsisdn() {
        return Yii::$app->session->get(UserHelper::SESSION_MSISDN);
    }

    public static function getUserId() {
        return Yii::$app->session->get(UserHelper::SESSION_USER_ID);
    }

    public static function setUserId($id) {
        return Yii::$app->session->set(UserHelper::SESSION_USER_ID, $id);
    }

    public static function setAvatar($avatar) {
        return Yii::$app->session->set(UserHelper::SESSION_AVATAR, $avatar);
    }

    public static function setUserName($display_name) {
        return Yii::$app->session->set(UserHelper::SESSION_DISPLAY_NAME, $display_name);
    }

    public static function setMsisdn($msdi) {
        return Yii::$app->session->set(UserHelper::SESSION_MSISDN, $msdi);
    }

    public static function setCasLogin($val=false) {
        return Yii::$app->session->set(UserHelper::SESSION_CAS, $val);
    }

    public static function getCasLogin() {
        return Yii::$app->session->get(UserHelper::SESSION_CAS);
    }

    public static function removeCasLogin() {
        Yii::$app->session->remove(UserHelper::SESSION_CAS);
    }

    public static function removeMsisdn() {
        Yii::$app->session->remove(UserHelper::SESSION_MSISDN);
    }
    public static function multilanguage($contentVi,$contentEn){
        if(!isset($_SESSION['vi'])){
            $_SESSION['vi'] = 'vi';
        }
         if($_SESSION['vi'] == 'vi'){
             return $contentVi;
         }else{
             return $contentEn;
         }
    }

    public static function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}