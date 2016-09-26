<?php

use common\models\News;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $type */

$this->title = News::getNameByType($type);
$this->params['breadcrumbs'][] = $this->title;

$visible_campaign = false;
$visible_village = false;

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
                <p><?= Html::a('Thêm tin tức', ['create', 'type' => $type], ['class' => 'btn btn-success']) ?> </p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'title',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a($model->title, ['update', 'id' => $model->id], ['class' => 'label label-primary']);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'header' => 'Danh mục',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\News */
                                return $model->getCategory() ? $model->getCategory() : '';
                            }
                        ],

                        [
                            'class' => '\kartik\grid\DataColumn',
//                            'attribute' => 'type',
                            'header' => 'Loại bài viết',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\News */
                                return $model->getTypeName();
                            },
//                            'filterType' => GridView::FILTER_SELECT2,
//                            'filter' => \common\models\News::listType(),
//                            'filterWidgetOptions' => [
//                                'pluginOptions' => ['allowClear' => true],
//                            ],
//                            'filterInputOptions' => ['placeholder' => "Tất cả"],

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'short_description',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\News */
                                return \common\helpers\CUtils::subString($model->short_description, 20);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\News */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\News::listStatus(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
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
