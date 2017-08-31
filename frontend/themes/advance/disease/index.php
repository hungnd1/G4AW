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
                    <a href="<?= Url::toRoute(['disease/index']) ?>"><?= $title ?></a>
                </div>
                <div class="m-content">
                    <div class="list-disease-block">
                        <?php if(isset($listdisease) && !empty($listdisease)){
                            foreach($listdisease as $item){
                                /** @var $item \common\models\GapGeneral */
                                ?>
                                <div class="list-new-1">
                                    <div class="thumb-common">
                                        <img class="blank-img" src="../img/blank.gif">
                                        <a href="<?= Url::toRoute(['disease/detail','id'=>$item->id]) ?>"><img class="thumb-cm" src="<?= $item->getImageLink() ?>"></a>
                                    </div>
                                    <div class="left-list">
                                        <h3><a href="<?= Url::toRoute(['disease/detail','id'=>$item->id]) ?>"><?= $item->title ?></a></h3>
                                        <span class="time-up"><?= date('d/m/Y',$item->created_at) ?></span>
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
                        <?= \frontend\widgets\DiseaseWidget::widget(['content'=>$item]) ?>
                    <?php } }?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end content -->
