<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use frontend\helpers\UserHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/** @var $model  \common\models\GapGeneral */
?>

<!-- content -->
<script src="<?= Yii::$app->request->baseUrl ?>/js/jwplayer/jwplayer.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ng_player.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ng_swfobject.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ParsedQueryString.js"></script>
<script>jwplayer.key = "tOf3A+hW+N76uJtefCw6XMUSRejNvQozOQIaBw==";</script>
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content" style="width: 100%;">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href=""><?= $model->title ?></a>
                </div>
                <div class="m-content">
                    <h1><?= $model->title ?></h1>
                    <!--                    --><?php //if($model->type == \common\models\News::TYPE_VIDEO){?>
                    <!--                    <a id="player" onclick="playVideo();"><img-->
                    <!--                            src="--><!--"-->
                    <!--                            width="100%"></a>-->
                    <!--                    <p style="text-align: center;font-size: 18px;">-->
                    <? //= UserHelper::multilanguage('Click ảnh trên để xem video','Click image to watch video') ?><!--</p>-->
                    <!--                    --><?php //} ?>
                    <div class="content-dt">
                        <?=$model->gap ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->


