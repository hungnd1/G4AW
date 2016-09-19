<?php

use common\models\DonationRequest;
use common\models\User;
use common\models\Village;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\helpers\FormatNumber;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DonationRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Yêu cầu trợ giúp';
$this->params['breadcrumbs'][] = $this->title;
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'title',
                            'label' => 'Yêu cầu',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a($model->title, ['view', 'id' => $model->id], ['class' => 'label label-primary']);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'village_id',

                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return $model->village ? $model->village->name : "";
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => ArrayHelper::map(Village::find()->andWhere(['status'=>Village::STATUS_ACTIVE])->all(),'id','name'),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\DonationRequest::listStatus(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'expected_amount',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return FormatNumber::formatNumber($model->expected_amount).' VND';
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'expected_items',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->expected_items;
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {campaign}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['donation-request/view', 'id' => $model->id]), [
                                        'title' => 'Xem chi tiết yêu cầu',
                                    ]);

                                },
//                                'reject' => function ($url, $model) {
//                                    if ($model->status == DonationRequest::STATUS_NEW) {
//                                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::toRoute(['update-status',
//                                            'type' => DonationRequest::STATUS_REJECTED, 'id' => $model->id]), [
//                                            'title' => 'Từ chối yêu cầu',
//                                        ]);
//                                    }
//                                },
                                'campaign' => function ($url, $model) {
                                    if ($model->status == DonationRequest::STATUS_NEW) {
                                        return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', Url::toRoute(
                                            ['campaign/create', 'donation_request_id' => $model->id]), [
                                            'title' => 'Tạo chiến dịch',
                                        ]);
                                    }
                                },
                            ],
                        ]
//                        [
//                            'class' => '\kartik\grid\DataColumn',
//                            'attribute' => 'organization_id',
//                            'label' => 'Tổ chức',
//                            'value' => function ($model, $key, $index, $widget) {
//                                /** @var $model \common\models\DonationRequest */
//                                return $model->organization ? $model->organization->getName() : "";
//                            },
//                            'filterType' => GridView::FILTER_SELECT2,
//                            'filter' => \yii\helpers\ArrayHelper::map(
//                                User::find()->andWhere(['type' => User::TYPE_ORGANIZATION])
//                                    ->asArray()->all(), 'id', 'fullname'
//                            ),
//                            'filterWidgetOptions' => [
//                                'pluginOptions' => ['allowClear' => true],
//                            ],
//                            'filterInputOptions' => ['placeholder' => "Tất cả"],
//                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
