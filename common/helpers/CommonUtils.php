<?php
/**
 *
 * @author Nguyen Chi Thuc
 */

namespace common\helpers;

use common\models\ServiceProvider;
use DateInterval;
use Madcoda\Youtube;
use Yii;
use yii\helpers\Url;

class CommonUtils
{
    const YOUTUBE_API_KEY = "AIzaSyAHKwVG8t1Ox7TX5el3TRa_KI2fuxrWnZ8";

    public static function pre($content)
    {
        echo '<pre>';
        print_r($content);
        echo '</pre>';
        die;
    }

    public static function rrmdir($path)
    {
        $path = rtrim($path, '/') . '/';

        // Remove all child files and directories.
        $items = glob($path . '*');

        foreach ($items as $item) {
            is_dir($item) ? self::rrmdir($item) : unlink($item);
        }

        // Remove directory.
        rmdir($path);
    }

    public static function getListParent($item, &$result = [])
    {
        if ($item->parent === null) {
            return $result;
        } else {
            if (!in_array($item->parent->id, $result)) {
                $result[] = $item->parent->id;
                CommonUtils::getListParent($item->parent, $result);
            }
            return $result;
        }
    }

    public static function columnLabel($value, $data)
    {
        if (array_key_exists($value, $data)) {
            return $data[$value];
        }
        return $value;
    }

    public static function displayDate($ts, $format = "d/m/Y")
    {
        if (!$ts) return '';
        $date = new \DateTime("@$ts");
        return $date->format($format);
    }

    public static function displayDateTime($ts, $format = "d/m/Y , H:i:s")
    {
        if (!$ts) return '';
        $date = new \DateTime("@$ts");
        return $date->format($format);
    }

    public static function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    public static function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    public static function time2String($seconds)
    {
        $etime = $seconds;
        if ($etime < 1) {
            return '0 giây';
        }

        $a = array(
            60 * 60 => 'giờ',
            60 => 'phút',
            1 => 'giây'
        );
        $time = '';
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            $etime = $etime % $secs;
            if ($d >= 1) {
                $r = round($d);
                $time .= ' ' . $r . ' ' . $str;
            }
        }
        return $time;
    }

    public static function randomString($length = 32, $chars = "abcdefghijklmnopqrstuvwxyz0123456789")
    {
        $max_ind = strlen($chars) - 1;
        $res = "";
        for ($i = 0; $i < $length; $i++) {
            $res .= $chars{rand(0, $max_ind)};
        }

        return $res;
    }

    public static function getVideoYoutubeInfo($yt_id)
    {
        $youtube = new Youtube(array('key' => CommonUtils::YOUTUBE_API_KEY));
        $video = $youtube->getVideoInfo($yt_id);
        return $video;
    }

    public static function convertYtDurationToSeconds($ytDuration)
    {
        $di = new DateInterval($ytDuration);
        return (3600 * $di->h + 60 * $di->m + $di->s);
    }

    public static function validateImage($imagePath)
    {
        if (!getimagesize($imagePath)) {
            return false;
        } else {
            return true;
        }
    }

    public static function getMoneyString($money)
    {
        $str = '';
        if (strlen($money) <= 3) {
            return $money;
        } else {
            $length = strlen($money);
            for ($i = $length; $i > 0; $i -= 3) {
                if (strlen($money) < 3) {
                    $str = '.' . $money . $str;
                } else {
                    $str = '.' . substr($money, strlen($money) - 3, 3) . $str;
                    $money = substr($money, 0, strlen($money) - 3);
                }

            }
        }

        return substr($str, 1, strlen($str) - 1);
    }

    public static function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENTIP'])) {
            return $_SERVER['HTTP_CLIENTIP'];
        }

        if (!empty($_SERVER['X_REAL_ADDR'])) {
            return $_SERVER['X_REAL_ADDR'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(':', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return $ips[0];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return gethostbyname(gethostname()); // tra ve ip local khi chay CLI
    }

    /**
     * Tao link redirect voi tuong ung voi domain cua SP
     * @param $sp ServiceProvider
     * @param $params
     * @return string
     */
    public static function createAbsolutePublicUrl($sp, $params)
    {
//        if($sp){
//            Yii::$app->urlManager->setHostInfo('http://'.$sp->getPrimaryDomain());
//        }
        return Yii::$app->urlManager->createAbsoluteUrl($params);
    }

    public static function getHostInfo()
    {
        $secure = Yii::$app->request->getIsSecureConnection();
        $http = $secure ? 'https' : 'http';
        if (isset($_SERVER['SERVER_NAME'])) {
            $host_info = $http . '://' . $_SERVER['SERVER_NAME'];
        } else {
            $host_info = $http . '://' . $_SERVER['HTTP_HOST'];
            $port = $secure ? Yii::$app->request->getSecurePort() : Yii::$app->request->getPort();
            if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                $host_info .= ':' . $port;
            }
        }
        return $host_info;
    }

    public static function replaceParamMT($message, $params, $values)
    {
        if (is_array($params)) {
            $cnt = count($params);
            for ($i = 0; $i < $cnt; $i++) {
                $message = str_replace('{' . $params[$i] . '}', $values[$i], $message);
            }
        }
        return $message;
    }

    public static function formatNumber($number)
    {
        return (new \yii\i18n\Formatter())->asInteger($number);
    }

    public static function addNumberZero($str, $length)
    {
        $length_str = strlen($str);
        if (strlen($str) < $length) {
            $k = $length - $length_str;
            for ($i = 0; $i < $k; $i++) {
                $str = "0" . $str;
            }
        }
        return $str;
    }

    public static function removeSign($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    public static function removeSpace($string)
    {
        return str_replace(' ', '', $string);
    }
}
