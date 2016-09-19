<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\assets;

use yii\web\AssetBundle;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MetronicAdminAsset extends AssetBundle
{
    public $sourcePath = '@common/template/metronic';
    public $css = [
        //<!-- BEGIN GLOBAL MANDATORY STYLES -->
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/css/components.min.css',
        'global/plugins/uniform/css/uniform.default.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'global/plugins/datatables/datatables.min.css',
        'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css' ,

        //<!-- END GLOBAL MANDATORY STYLES -->

        //<!-- BEGIN THEME STYLES -->
//        'global/css/components-md.css',
        'global/css/plugins-md.css',
        'admin/layout3/css/layout.css',
        'admin/layout3/css/themes/default.css',
        'admin/layout3/css/custom.css',
        //<!-- END THEME STYLES -->

        'global/css/components.min.css',

    ];
    public $js = [
        //<!-- BEGIN CORE PLUGINS -->
        'global/plugins/jquery-ui/jquery-ui.min.js',
        'global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/jquery.cokie.min.js',
        'global/plugins/uniform/jquery.uniform.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
//        'global/scripts/datatable.js',
//        'global/plugins/datatables/datatables.min.js',
//        'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js',

        //<!-- END CORE PLUGINS -->
        'global/scripts/app.min.js',
        'admin/layout3/scripts/layout.js',
        'admin/pages/scripts/table-datatables-managed.min.js'


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\MetronicRespondAsset',
        'yii\web\JqueryAsset'
    ];
}
