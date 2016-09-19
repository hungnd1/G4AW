<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 8/23/2016
 * Time: 3:32 PM
 */

namespace common\helpers;


use XenForo_Application;
use XenForo_Autoloader;
use XenForo_DataWriter;
use XenForo_Model_User;
use yii\base\Exception;

class ForumHelper
{
    public static function createNewUser($newusername, $newpassword, $newemail)
    {

//        $newusername = "shit123";
//        $newpassword = "12345678";
//        $newemail = "joseph1232@yahoo.com";

        $startTime = microtime(true);
        $fileDir = dirname(dirname(__DIR__)) . '/forum';
        require($fileDir . '/library/XenForo/Autoloader.php');
        XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');

        XenForo_Application::initialize($fileDir . '/library', $fileDir);
        XenForo_Application::set('page_start_time', $startTime);
        XenForo_Application::setDebugMode(true);
        XenForo_Application::disablePhpErrorHandler();


        /** @var XenForo_DataWriter $writer */
        $writer = XenForo_DataWriter::create('XenForo_DataWriter_User');

        // set all the values
        $writer->set('username', $newusername);
        $writer->set('email', $newemail);
        $writer->setPassword($newpassword, $newpassword);
        $writer->set('user_group_id', XenForo_Model_User::$defaultRegisteredGroupId);

        // save user
        try {
            $result = $writer->save();
        } catch (Exception $e) {
            \Yii::error($e);
            $result = false;
        }


        return $result;

    }
} 