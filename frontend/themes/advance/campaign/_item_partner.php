<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 13/03/2016
 * Time: 1:45 PM
 */
use \yii\helpers\Html;
use \common\models\Campaign;

/** @var $model \common\models\Campaign */
/** @var  $processPercent */
$processPercent = (int)$model->current_amount * 100 / $model->expected_amount;
$processPercent = $processPercent>=100 ? 100:$processPercent;
?>
<div class="campain">
    <div class="thumb-common">

        <div class="drop-edit">
            <i class="fa fa-pencil"></i>
            <div class="u-drop-edit">
                <ul>

                    <li> <?= Html::a('<i class="fa fa-pencil-square-o"></i>Sửa', ['/campaign/update', 'id' => $model->id]) ?></li>
                    <li><?= Html::a('<i class="fa fa-trash-o"></i>Xóa', ['/campaign/delete', 'id' => $model->id], ['data-method' => 'post']) ?></li>
                </ul>
            </div>
        </div>


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
            <?= Html::a(\yii\helpers\Html::img($model->createdBy->getAvatar()), ['/user/detail', 'id' => $model->created_by], ['class' => 'logo-cp']) ?>
            <?= Html::a(Html::tag('H4', $model->createdBy->getName()), ['/user/detail', 'id' => $model->created_by]) ?>

            <span class="add-cp"><?= $model->createdBy->address ?></span>
            <div class="bar">
                <?php
                switch ($model->status) {
                    case  Campaign::STATUS_NEW:
                        echo Html::tag('span', '<i class="fa fa-clock-o"></i>Đang chờ', ['class' => 'stt-1 stt-wait']);
                        break;
                    case  Campaign::STATUS_APPROVED:
                        echo Html::tag('span', 'Đã duyệt', ['class' => 'stt-1 stt-ok']);
                        break;
                    case  Campaign::STATUS_REJECT:
                        echo Html::tag('span', '<i class="fa fa-ban"></i>Từ chối', ['class' => 'stt-1 stt-rj']);
                        break;
                    case  Campaign::STATUS_ACTIVE:
                        echo Html::tag('span', '<i class="fa fa-check"></i>Hoạt động', ['class' => 'stt-1 stt-act']);
                        break;
                    case  Campaign::STATUS_DONE:
                        echo Html::tag('span', '<i class="fa fa-thumbs-o-up"></i>Đã hoàn thành', ['class' => 'stt-1 stt-ok']);
                        break;
                }

                ?>
                <div class="bar-status" style="width: <?= $processPercent . '%' ?>"></div>
            </div>
            <div class="line-i">
                <span class="num-donor"><?= $model->donor_count ?><i class="fa fa-user"></i></span>
                <span
                    class="pr-need">Cần thêm: <span><?= \common\helpers\CommonUtils::formatNumber($model->getNeedAmount()) . $model->getCurrency(); ?> </span></span>
            </div>
        </div>
    </div>
</div>
