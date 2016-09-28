<?php

use kartik\detail\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Village */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quản lý xã'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$active=1;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?= $this->title ?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="<?= ($active == 1) ? 'active' : '' ?>">
                            <a href="#tab1" data-toggle="tab">
                                Thông tin chung</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="tab1">
                            <p>
                                <?= Html::a(Yii::t('app', 'Thêm mới'), ['create'], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('app', 'Cập nhật'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('app', 'Hủy'), ['index'], [ 'class' => 'btn btn-danger' ]) ?>
                            </p>

                            <?= DetailView::widget([
                                'model' => $model,
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                                'attributes' => [
//                                    'donation_request_id',
                                    [
                                        'attribute' => 'image',
                                        'format'=>'html',
                                        'value' => Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@village_image') . "/" .$model->image, ['width' => '250px']),
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'value' => $model->name,
                                    ],
                                    [
                                        'attribute' => 'name_en',
                                        'value' => $model->name_en,
                                    ],
                                    [
                                        'attribute' => 'number_code',
                                        'value' => $model->number_code,
                                    ],
                                    [
                                        'attribute' => 'latitude',
                                        'value' => $model->latitude,
                                    ],
                                    [
                                        'attribute' => 'longitude',
                                        'value' => $model->longitude,
                                    ],
                                    [
                                        'attribute' => 'id_province',
                                        'format'=>'html',
                                        'value' => \common\models\Province::findOne(['id'=>$model->id_province])->name,
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Trạng thái',
                                        'format' => 'raw',
                                        'value' => ($model->status == \common\models\Village::STATUS_ACTIVE) ?
                                            '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                                            '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                                    ],
                                    'description:ntext',
                                    'description_en:ntext',
                                    [
                                        'attribute' => 'created_at',
                                        'label' => 'Ngày tham gia',
                                        'value' => date('d/m/Y H:i:s', $model->created_at),
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'label' => 'Ngày thay đổi thông tin',
                                        'value' => date('d/m/Y H:i:s', $model->updated_at),
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>