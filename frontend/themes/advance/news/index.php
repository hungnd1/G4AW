<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 10:43 AM
 */
use frontend\helpers\UserHelper;
use yii\helpers\Url;
?>
<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('Trang chủ','Home') ?></a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['news/index']) ?>"><?= $title ?></a>
                </div>
                <div class="m-content">
                    <div class="list-news-block">
                        <?php if(isset($listNews) && !empty($listNews)){
                            foreach($listNews as $item){
                                /** @var $item \common\models\News */
                                ?>
                                <div class="list-new-1">
                                    <div class="thumb-common">
                                        <img class="blank-img" src="../img/blank.gif">
                                        <a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>"><img class="thumb-cm" src="<?= $item->getImageLink() ?>"></a>
                                    </div>
                                    <div class="left-list">
                                        <h3><a href="<?= Url::toRoute(['news/detail','id'=>$item->id]) ?>"><?= $item->title ?></a></h3>
                                        <span class="time-up"><?= date('d/m/Y',$item->created_at) ?></span>
                                        <p><?= str_replace(mb_substr($item->short_description, 150, strlen($item->short_description), 'utf-8'), '...', $item->short_description) ?></p>
                                    </div>
                                </div>
                            <?php }
                        }else{?>
                        <span style="color: red"><?= UserHelper::multilanguage('Không có dữ liệu','Not found data') ?></span>
                        <?php } ?>
                        <?php
                         $pagination = new \yii\data\Pagination(['totalCount' => $pages->totalCount,'pageSize' =>10]);
                          echo \yii\widgets\LinkPager::widget([
                            'pagination' => $pagination,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="block-related block-cm-2">
                    <h3><?= UserHelper::multilanguage('CÓ THỂ BẠN QUAN TÂM','Could you care!') ?></h3>
                    <div class="list-related">
                    <?php if(isset($listNewRelated) && !empty($listNewRelated)){
                    foreach($listNewRelated as $item ) {?>
                        <?= \frontend\widgets\NewsWidget::widget(['content'=>$item]) ?>
                    <?php } }?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end content -->
