<?php
/**
 * Created by PhpStorm.
 * User: linhpv
 * Date: 2/11/15
 * Time: 10:57 AM
 */

namespace common\helpers;


class FileUtils {

    public static function getRealFile($str){
        preg_match("/(.*)_(.*)(\.[.a-zA-Z0-9]*)$/", $str, $output_array);
        return isset($output_array[1])?$output_array[1]:$str;
    }

    public static function getNormalFileName($str){
        return StringUtils::convertNormalName(self::getRealFile($str));
    }

} 