<?php
/**
 *
 * @author Nguyen Chi Thuc
 */

namespace common\helpers;

class StringUtils {

    public static function startsWith($s, $prefix)
    {
        return empty($prefix) || strpos($s, $prefix) === 0;
    }

    public static function endsWith($s, $suffix)
    {
        return empty($suffix) || substr($s, -strlen($suffix)) === $suffix;
    }

    public static function contain($s, $subStr)
    {
        if (empty($subStr)) return true;
        return strpos($s, $subStr) !== false;
    }

    /**
     * @param $s1
     * @param $s2
     * @return bool
     */
    public static function equal($s1, $s2) {
       return strcmp($s1, $s2) === 0;
    }

    public static function removeTail($s, $tail) {
        if (empty($tail) || !StringUtils::endsWith($s, $tail)) {
            return $s;
        }

        return substr($s, 0, - strlen($tail));
    }

    public static function removeHead($s, $head) {
        if (empty($head) ||!StringUtils::startsWith($s, $head)) {
            return $s;
        }

        return substr($s,strlen($head));
    }

    /**
     * Convert camel string (BeUser) to dash format (be-user)
     * @param $s
     * @param $head
     * @return string
     */
    public static function camel2Dash($s) {

        $s2 = preg_replace("%([A-Z])%se", "'-' . strtolower('\\1')",$s);
        if (!StringUtils::startsWith($s, '-')) {
            $s2 = StringUtils::removeHead($s2, '-');
        }
        return $s2;
    }

    public static function randomStr($code_length=10){
        $code = "";
        $raw_code = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $raw_code_length = strlen($raw_code);
        for($i=0; $i < $code_length; $i++){
            $code .= $raw_code[rand(0, $raw_code_length - 1)];
        }
        return $code;
    }

    public static function convertNormalName($str){
        //Convert '_' to ' '
        $result =  str_replace("_", " ", $str);
        return ucwords(strtolower($result));
    }
    public static function getNWordsFromString($text,$numberOfWords = 25)
    {
        if($text != null)
        {
            $textArray = explode(" ", $text);
            if(count($textArray) > $numberOfWords)
            {
                return implode(" ",array_slice($textArray, 0, $numberOfWords))."...";
            }
            return $text;
        }
        return "";
    }
    public static function hideTextInString($content,$showPrefix=3,$showSuffix=3){
        $prefix= substr($content,0,3);
        $suffix =substr($content,-3);
        return $prefix.'***'.$suffix;
    }


}

?>
