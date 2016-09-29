<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use frontend\helpers\UserHelper;
use yii\helpers\Url;

/** @var $model  \common\models\News */
?>

<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content" style="width: 100%;">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('Trang chủ','Home') ?></a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['site/about']) ?>"><?= UserHelper::multilanguage('Giới thiệu','Introduction') ?></a>
                </div>
                <div class="m-content">
                    <div class="content-dt">
                        <?= UserHelper::multilanguage(preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->content),preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->content_en)) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
