<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use yii\helpers\Url;

/** @var $model  \common\models\News */
?>

<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['news/index']) ?>">Tin tức</a>
                    <span>/</span>
                    <a href=""><?= $model->title ?></a>
                </div>
                <div class="m-content">
                    <h1><?= $model->title ?></h1>
                    <p class="des-dt"><?= $model->short_description ?></p>
                    <div class="content-dt">
                        <?= preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->content) ?>
                    </div>
                    <div class="post-related">
                        <h2>Tin tức liên quan</h2>
                        <div>
                            <?php if(isset($otherModels) && !empty($otherModels)){
                                foreach($otherModels as $item){
                                    /** @var $item \common\models\News */
                                    ?>
                                    <div class="news">
                                        <div class="thumb-common">
                                            <img src="../img/blank.gif">
                                            <a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>"><img class="thumb-cm" src="<?= $item->getThumbnailLink() ?>"><br></a>
                                        </div>
                                        <div class="if-cm-2">
                                            <a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>"><h3 class="name-1"><?= $item->title ?></h3></a>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="block-related block-cm-2">
                    <h3>Có thể bạn quan tâm</h3>
                    <div class="list-related">
                        <?php if(isset($listCampaign) && !empty($listCampaign)){
                            foreach($listCampaign as $item ) {?>
                                <?= \frontend\widgets\NewsWidget::widget(['content'=>$item]) ?>
                            <?php } }?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end content -->
