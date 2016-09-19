<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 19/03/2016
 * Time: 4:40 PM
 */
use yii\helpers\Html;
/** @var $news \common\models\News[] */
/** @var $pages \yii\data\Pagination */
$this->title ='Tin tức';
?>
<!-- common page-->
<div class="content-common">
    <div class="container">
        <div class="brc-page">
            <a href="">Trang chủ</a><span>/</span><a href="" class="active">Tin tức</a>
        </div>
        <div class="list-news">
            <?php
            /** @var \common\models\News $item */
            foreach ($news as $item) {
                ?>
                <div class="media">
                    <div class="media-left thumb-common">
                        <?= \yii\helpers\Html::img('@web/img/blank.gif')?>
                        <?= Html::a(Html::img($item->getThumbnailLink(),['class'=>'media-object thumb-cm']),['/news/view','id'=>$item->id])?>

                    </div>
                    <div class="media-body">
                        <h4 class="media-heading"><?=$item->title?></h4>
                        <p><?=\common\helpers\StringUtils::getNWordsFromString($item->short_description) ?><?=Html::a('Đọc thêm',['/news/view','id'=>$item->id],['class'=>'read-more'])?></p>
                    </div>
                </div>
            <?php
            }
            ?>


        </div>
        <div class="page-link-common">
                <?= \frontend\widgets\LinkPager::widget(['pagination' => $pages]) ?>
        </div>
    </div>
</div>
<!-- end common page-->