<?php

use common\models\Campaign;
use common\models\LeadDonor;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model common\models\Village */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xã của tôi'), 'url' => ['index']];
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
                        <li class=" <?= ($active == 2) ? 'active' : '' ?>">
                            <a href="#tab2" data-toggle="tab">
                                Danh sách chiến dịch</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="tab1">
                            <p>
                                <?= Html::a(Yii::t('app', 'cập nhật'), ['update_my_village', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                                        'attribute' => 'map_images',
                                        'format'=>'html',
                                        'value' => Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@village_image') . "/" .$model->map_images, ['width' => '250px']),
                                    ],[
                                        'attribute' => 'lead_donor_id',
                                        'format'=>'html',
                                        'value' => $model->leadDonor?$model->leadDonor->name:'',
                                    ],
                                    [
                                        'attribute' => 'establish_date',
                                        'value' => $model->establish_date?date('m/d/Y',strtotime($model->establish_date)):'',
                                    ],

                                    [
                                        'attribute' => 'status',
                                        'label' => 'Trạng thái',
                                        'format' => 'raw',
                                        'value' => ($model->status == \common\models\Campaign::STATUS_ACTIVE) ?
                                            '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                                            '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                                    ],
                                    'description:ntext',
                                    [
                                        'attribute' => 'natural_area',
                                        'value' => $model->natural_area,
                                    ],
                                    [
                                        'attribute' => 'arable_area',
                                        'value' => $model->arable_area,
                                    ],
                                    [
                                        'attribute' => 'main_industry',
                                        'value' => $model->main_industry,
                                    ],
                                    [
                                        'attribute' => 'main_product',
                                        'value' => $model->main_product,
                                    ],
                                    [
                                        'attribute' => 'population',
                                        'value' => $model->population,
                                    ],
                                    [
                                        'attribute' => 'gdp',
                                        'value' => $model->gdp,
                                    ],
                                    [
                                        'attribute' => 'poor_family',
                                        'value' => $model->poor_family,
                                    ],
                                    [
                                        'attribute' => 'no_house_family',
                                        'value' => $model->no_house_family,
                                    ],
                                    [
                                        'attribute' => 'missing_classes',
                                        'value' => $model->missing_classes,
                                    ],
                                    [
                                        'attribute' => 'lighting_condition',
                                        'value' => $model->lighting_condition,
                                    ],
                                    [
                                        'attribute' => 'water_condition',
                                        'value' => $model->water_condition,
                                    ],
                                    [
                                        'attribute' => 'missing_playground',
                                        'value' => $model->missing_playground,
                                    ],
                                    [                      // the owner name of the model
                                        'attribute' => 'created_at',
                                        'label' => 'Ngày tham gia',
                                        'value' => date('d/m/Y H:i:s', $model->created_at),
                                    ],
                                    [                      // the owner name of the model
                                        'attribute' => 'updated_at',
                                        'label' => 'Ngày thay đổi thông tin',
                                        'value' => date('d/m/Y H:i:s', $model->updated_at),
                                    ],
                                ],
                            ]) ?>
                        </div>

                        <div class="tab-pane <?= ($active == 2) ? 'active' : '' ?>" id="tab2">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'pjax'=>true,
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                                'columns' => [
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'name',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            return $modelcam->id;
                                        },
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'name',
                                        'format' => 'html',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            return Html::a($modelcam->name, ['campaign/view', 'id' => $modelcam->id], ['class' => 'label label-primary']);
                                        },
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            if ($modelcam->status == Campaign::STATUS_ACTIVE) {
                                                return '<span class="label label-success">' . $modelcam->getStatusName() . '</span>';
                                            } else {
                                                return '<span class="label label-danger">' . $modelcam->getStatusName() . '</span>';
                                            }

                                        },
                                        'filter' => Campaign::listStatus(),
                                        'filterType' => GridView::FILTER_SELECT2,
                                        'filterWidgetOptions' => [
                                            'pluginOptions' => ['allowClear' => true],
                                        ],
                                        'filterInputOptions' => ['placeholder' => "Tất cả"],
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'lead_donor_id',
                                        'header' => Yii::t('app', 'Doanh nghiệp đỡ đầu'),
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            return $modelcam->leadDonor?$modelcam->leadDonor->name:'';
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
                                        'header' => Yii::t('app', 'Tỉ lệ đóng góp'),
                                        'format' => 'html',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            /** @var $model Campaign */
                                            return $modelcam->getRateDonation();
                                        },
                                    ],
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
