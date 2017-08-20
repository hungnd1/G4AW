<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use frontend\helpers\UserHelper;
use yii\helpers\Url;

/** @var $model  \common\models\Term */
?>

<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content" style="width: 100%;">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['site/about']) ?>">Giới thiệu</a>
                </div>
                <div class="m-content">
                    <div class="content-dt">
                        <?= preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->term) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
