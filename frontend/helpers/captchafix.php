<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15/8/2016
 * Time: 9:28 AM
 */

namespace frontend\helpers;


use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;

class captchafix extends CaptchaAction
{
    protected function renderImage($code)
    {
        if (Captcha::checkRequirements() === 'gd') {
            return $this->renderImageByGD($code);
            if (isset($this->imageLibrary)) {
                $imageLibrary = $this->imageLibrary;
            } else {
                $imageLibrary = Captcha::checkRequirements();
            }
            if ($imageLibrary === 'gd') {
                return $this->renderImageByGD($code);
            } elseif ($imageLibrary === 'imagick') {
                return $this->renderImageByImagick($code);
            } else {
                throw new InvalidConfigException("Defined library '{$imageLibrary}' is not supported");
            }
        }
    }
}