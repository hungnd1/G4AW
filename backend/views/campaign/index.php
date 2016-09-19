<?php

use common\models\Campaign;
use common\models\DonationRequest;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý chiến dịch';
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
                <p><?php // Html::a('Thêm mới chiến dịch', ['create'], ['class' => 'btn btn-success']) ?></p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'campaign_code',
                            'width' => '120px',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->campaign_code ? $model->campaign_code : "";
                            },

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'name',
                            'label' => 'Tên chiến dịch',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a($model->name, ['view', 'id' => $model->id], ['class' => 'label label-primary']);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'started_at',
                            'value' => function ($model, $key, $index, $widget) {
                                return date('d/m/Y', $model->started_at);
                            },

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'finished_at',
                            'value' => function ($model, $key, $index, $widget) {
                                return date('d/m/Y', $model->finished_at);
                            },

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'donation_request_id',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model Campaign */
                                return $model->donationRequest ? $model->donationRequest->title : '';
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => ArrayHelper::map(
                                DonationRequest::find()->andWhere(['status'=>DonationRequest::STATUS_APPROVED])->asArray()->all(), 'id', 'title'),
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
                                /** @var $model Campaign */
                                return $model->getStatusName();
                            },
                            'width' => '150px',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => Campaign::listStatus(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],

                        ],

                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'village_id',
                            'header' => 'Thuộc xã',
//                            'width'=>'150px',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model Campaign */
                                return $model->village ? $model->village->name : '';
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => ArrayHelper::map(\common\models\Village::find()->asArray()->all(), 'id', 'name'),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'header' => Yii::t('app', 'Tỉ lệ đóng góp'),
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model Campaign */
                                return $model->getRateDonation();
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}{update}{delete}',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
