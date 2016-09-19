<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\LeadDonor;
use yii\helpers\Url;
use kartik\widgets\Alert;
/* @var $this yii\web\View */
/* @var $searchModel common\models\LeadDonorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quản lý doanh nghiệp đỡ đầu');
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
                <p><?= Html::a(Yii::t('app', 'Tạo doanh nghiệp đỡ đầu'), ['create'], ['class' => 'btn btn-success']) ?></p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'image',
                            'format' => 'html',
                            'headerOptions' => ['style' => 'width:10%'],
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@lead_donor_image') . "/" .$model->image, ['width' => '150px']);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'name',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a($model->name, ['view', 'id' => $model->id], ['class' => 'label label-primary']);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'width'=>'150px',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                if ($model->status == LeadDonor::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => LeadDonor::getListStatus(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'address',
                            'width' => '200px',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->address;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'website',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->website;
                            },
                        ],
//                        [
//                            'class' => '\kartik\grid\DataColumn',
//                            'attribute' => 'description',
//                            'headerOptions' => ['style' => 'width:20%'],
//                            'format' => 'html',
//                            'value' => function ($model, $key, $index, $widget) {
//                               return $model->description?LeadDonor::_substr($model->description,50):'';
//                            },
//                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'phone',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->phone;
                            },
                        ],
                        'email:email',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{view}{update}',
                            'buttons'=>[
                                'view' => function ($url,$model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['lead-donor/view','id'=>$model->id]), [
                                        'title' => 'Xem chi tiết doanh nghiệp đỡ đầu',
                                    ]);

                                },
                                'update' => function ($url,$model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['lead-donor/update','id'=>$model->id]), [
                                        'title' => 'Thay đổi thông tin doanh nghiệp đỡ đầu',
                                    ]);
                                },
//                                'delete' => function ($url,$model) {
//                                    if ($model->status == 10) {
//                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['lead-donor/delete', 'id' => $model->id]), [
//                                            'title' => 'Thay đổi trạng thái doanh nghệp đỡ đầu',
//                                            'data-confirm' => "Bạn chắc chắn muốn tạm dừng doanh nghiệp đỡ đầu này?",
//                                            'data-method' => 'post',
//                                        ]);
//                                    }
//                                },
//
//                                'change_status' => function ($url,$model) {
//                                    if ($model->status == LeadDonor::STATUS_BLOCK){
//                                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', Url::toRoute(['lead-donor/change_to_start', 'id' => $model->id]), [
//                                            'title' => 'Kích hoạt doanh nghiệp đỡ đầu',
//                                        ]);
//                                    }
//                                },
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
