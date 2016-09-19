<?php

use common\models\LeadDonor;
use common\models\Province;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\Village;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VillageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quản lý xã');
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
                <?php
                    if(Yii::$app->user->identity->type == User::TYPE_MANAGER || Yii::$app->user->identity->type == User::TYPE_ADMIN ) {
                        ?>
                        <p><?= Html::a(Yii::t('app', 'Thêm mới xã'), ['create'], ['class' => 'btn btn-success']) ?></p>
                        <?php
                    }
                ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'image',
                            'format' => 'html',
                            'width' => '250px',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@village_image') . "/" .$model->image, ['width' => '150px']);
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
                            'attribute' => 'lead_donor_id',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                $lead = LeadDonor::findOne($model->lead_donor_id);
                                return $model->lead_donor_id?Html::a($lead->name, ['lead-donor/view', 'id' => $model->lead_donor_id]):'';
                            },
                            'filter' => ArrayHelper::map(
                                LeadDonor::find()->andWhere(['status'=>LeadDonor::STATUS_ACTIVE])->asArray()->all(),'id','name'
                            ),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'district_id',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Province::findOne(['id'=>$model->district_id])->display_name;
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => ArrayHelper::map(
                                Province::find()->andWhere(['status'=>Province::STATUS_ACTIVE])->asArray()->all(),'id','display_name'
                            ),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                if ($model->status == Village::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
                                }

                            },
                            'filter' => Village::getListStatus(),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{view}{update}{delete}',
                            'buttons'=>[
                                'view' => function ($url,$model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['village/view','id'=>$model->id]), [
                                        'title' => 'Xem chi tiết xã',
                                    ]);

                                },
                                'update' => function ($url,$model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['village/update','id'=>$model->id]), [
                                        'title' => 'Cập nhật xã',
                                    ]);

                                },
                                'delete' => function ($url,$model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['village/delete','id'=>$model->id]), [
                                        'title' => 'Xóa yêu cầu',
                                        'data-confirm' => "Bạn chắc chắn muốn xóa xã này?",
                                        'data-method' => 'post',
                                    ]);
                                },
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
