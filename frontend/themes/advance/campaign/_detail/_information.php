<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:44 AM
 */
/** @var $model \common\models\Campaign */
$processPercent = (int)$model->current_amount*100/ $model->expected_amount;
$processPercent = $processPercent>=100 ? 100:$processPercent;
?>
<div class="block-need">
    <span class="pr-need2">Cần thêm: <span><?=\common\helpers\CommonUtils::formatNumber($model->getNeedAmount()).$model->getCurrency();?> </span></span><br>
    <div class="bar">
        <div class="bar-status" style="width:<?= $processPercent.'%' ?>"></div>
    </div>
    <div class="line-i">
        <span class="num-donor"><?=$model->donor_count ?><i class="fa fa-user"></i></span>
        <span class="pr-need">Đã quyên góp: <span><?= \common\helpers\CommonUtils::formatNumber($model->current_amount).$model->getCurrency(); ?></span></span>
    </div>
</div>
