<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 13/03/2016
 * Time: 1:45 PM
 */
use \yii\helpers\Html;

/** @var $model \common\models\Campaign */
$showAvatar = isset($showAvatar) ? $showAvatar : true;
$processPercent = (int)$model->current_amount * 100 / $model->expected_amount;
$processPercent = $processPercent>=100 ? 100:$processPercent;
?>
<div class="campain">
    <div class="thumb-common">
        <?= Html::img('@web/img/blank.gif') ?>

        <?= Html::a(Html::img($model->getThumbnailLink(), ['class' => 'thumb-cm']) . '<br>', ['/campaign/view', 'id' => $model->id]) ?>
    </div>
    <div class="if-cm-1">
        <div class="top-cp">
            <?= Html::a(Html::tag('H3', $model->name, ['class' => 'name-1']) . '<br>', ['/campaign/view', 'id' => $model->id]) ?>
            <span
                class="des-cm-1"><?= \common\helpers\StringUtils::getNWordsFromString($model->short_description) ?></span>
        </div>
        <div class="bt-cp">
            <?= $showAvatar ? Html::a(\yii\helpers\Html::img($model->createdBy->getAvatar()), ['/user/detail', 'id' => $model->created_by], ['class' => 'logo-cp']) : '' ?>
            <?= Html::a(Html::tag('H4', $model->createdBy->getName()), ['/user/detail', 'id' => $model->created_by]) ?>

            <span class="add-cp"><?= $model->createdBy->address ?></span>
            <div class="bar">
                <div class="bar-status" style="width:<?= $processPercent . '%' ?>"></div>
            </div>
            <div class="line-i">
                <span class="num-donor"><?= $model->donor_count ?><i class="fa fa-user"></i></span>
                <span
                    class="pr-need">Cần thêm: <span><?= \common\helpers\CommonUtils::formatNumber($model->getNeedAmount()) . $model->getCurrency() ?> </span></span>
            </div>
        </div>
    </div>
</div>
