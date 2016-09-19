<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 14-Jul-16
 * Time: 4:34 PM
 */

namespace frontend\helpers;


use yii\i18n\Formatter;

class FormatNumber
{

    public static function formatNumber($number)
    {
        $formatter = new \yii\i18n\Formatter();
        $formatter->thousandSeparator = '.';
        $formatter->decimalSeparator = '.';
        return $formatter->asInteger($number);
    }

    public  static function sw_get_current_weekday() {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $weekday = date("l");
        $weekday = strtolower($weekday);
        switch($weekday) {
            case 'monday':
                $weekday = 'Thứ hai';
                break;
            case 'tuesday':
                $weekday = 'Thứ ba';
                break;
            case 'wednesday':
                $weekday = 'Thứ tư';
                break;
            case 'thursday':
                $weekday = 'Thứ năm';
                break;
            case 'friday':
                $weekday = 'Thứ sáu';
                break;
            case 'saturday':
                $weekday = 'Thứ bảy';
                break;
            default:
                $weekday = 'Chủ nhật';
                break;
        }
        return $weekday.', '.date('d/m/Y');
    }

    public static function  standardStringPreventSqlInjection($string){
        $string = trim($string);
        $string = str_replace("'","",$string);
        $string = str_replace('"','',$string);
        $string = strip_tags($string);

        while (strpos($string, '  ') !== false) {
            $string = str_replace('  ', ' ', $string);
        }

        return $string;
    }
}