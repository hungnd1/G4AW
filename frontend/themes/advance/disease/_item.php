<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 13/03/2016
 * Time: 1:45 PM
 */
use \yii\helpers\Html;
/** @var $model \common\models\News */
?>
<div class="news">
    <div class="thumb-common">
        <?=Html::img('@web/img/blank.gif')?>
        <?= Html::a(Html::img($model->getThumbnailLink(),['class'=>'thumb-cm']),['/news/view','id'=>$model->id])?>
    </div>
    <div class="if-cm-1">
        <?= Html::a(Html::tag('h3',$model->title,['class'=>'name-1']),['/news/view','id'=>$model->id])?>
        <span class="des-cm-1"><?=\common\helpers\StringUtils::getNWordsFromString($model->short_description)?><?=Html::a('Đọc thêm',['/news/view','id'=>$model->id],['class'=>'read-more'])?></span>
    </div>
</div>
