<?php
use yii\helpers\Html;
use common\helpers\StringUtils;
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:35 AM
 */
/** @var $model \common\models\Campaign */
$transaction=$model->getTransactions()->orderBy('created_at desc')->limit(10)->all();
?>
<div class="block-give">
    <h3>Danh sách ủng hộ mới nhất <?=Html::a('<i class="fa fa-chevron-right"></i>',['/campaign/donate-transaction','campaign_id'=>$model->id])?></h3>
    <ul>
        <?php
        /** @var \common\models\Transaction $item */
        foreach($transaction as $item):?>
            <li><?=Html::a($item->user->getName() . Html::tag('span',\common\helpers\CommonUtils::formatNumber($item->amount) .$item->getCurrency()),'#')?></li>
        <?php endforeach;?>
    </ul>
</div>