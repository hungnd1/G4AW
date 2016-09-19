<?php
/**
 * Created by PhpStorm.
 * User: Thuc
 * Date: 3/11/2015
 * Time: 2:29 PM
 */

namespace common\helpers;


class Misc {
    /**
     * tra ra quyet dinh true/false theo xac suat truyen vao
     * @param float $probability, 0.0 - 1.0
     * @return bool
     */
    public static function decide($probability = 0.5) {
        $rand = rand(0, getrandmax())/getrandmax();
        return  $rand <= $probability;
    }

    /**
     * @param int $max
     * @return float|int [0.0..1.0] or [0..max]
     */
    public static function rand($max = 0) {
        if (!$max) {
            $rand = rand(0, getrandmax())/getrandmax();
        }
        else {
            $rand = rand(0, $max);
        }
        return  $rand;
    }
}