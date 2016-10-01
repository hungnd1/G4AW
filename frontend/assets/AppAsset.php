<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/templates/advance';
    public $css = [
        'css/bootstrap.min.css',
        'css/new.css',
        'css/style-responsive.css',
        'css/font-awesome.css',
        'css/jquery.bxslider.css'

    ];
    public $js = [
//        'js/jquery.min.js',
        'js/bootstrap.min.js',
        'js/jquery.bxslider.min.js',
        'js/jquery.mobile.custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
