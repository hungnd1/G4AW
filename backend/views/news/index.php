<?php

use common\models\News;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $type */

$unPublishStatus = \common\models\News::STATUS_NEW;
$showStatus = \common\models\News::STATUS_ACTIVE;
$deleteStatus = \common\models\News::STATUS_INACTIVE;

$this->title = News::getNameByType($type);
$this->params['breadcrumbs'][] = $this->title;

$visible_campaign = false;
$visible_village = false;

?>
<?php
$updateLink = \yii\helpers\Url::to(['news/update-status-content']);

$js = <<<JS
    function updateStatusContent(newStatus){

        feedbacks = $("#content-index-grid").yiiGridView("getSelectedRows");
        if(feedbacks.length <= 0){
            alert("Chưa chọn content! Xin vui lòng chọn ít nhất một content để cập nhật.");
            return;
        }
        var delConfirm = true;

        if(newStatus == 2){
            delConfirm = confirm('Bạn có muốn xóa không?');
        }

        if(delConfirm){
            jQuery.post(
                '{$updateLink}',
                { ids:feedbacks ,newStatus:newStatus}
            )
            .done(function(result) {
                if(result.success){
                    toastr.success(result.message);
                    jQuery.pjax.reload({container:'#content-index-grid'});
                }else{
                    toastr.error(result.message);
                }
            })
            .fail(function() {
                toastr.error("server error");
            });
        }

        return;
    }
JS;

$this->registerJs($js, \yii\web\View::POS_HEAD);
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
                    'id' => 'content-index-grid',
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => 'Danh sách Nội dung'
                    ],
                    'toolbar' => [
                        [
                            'content' =>
                                Html::button('<i class="glyphicon glyphicon-ok"></i> Publish', [
                                    'type' => 'button',
                                    'title' => 'Publish',
                                    'class' => 'btn btn-success',
                                    'onclick' => 'updateStatusContent("' . $showStatus . '");'
                                ])

                        ],
                        [
                            'content' =>
                                Html::button('<i class="glyphicon glyphicon-minus"></i> Unpublish', [
                                    'type' => 'button',
                                    'title' => 'Unpublish',
                                    'class' => 'btn btn-danger',
                                    'onclick' => 'updateStatusContent("' . $unPublishStatus . '");'
                                ])

                        ],
                        [
                            'content' =>
                                Html::button('<i class="glyphicon glyphicon-trash"></i> Delete', [
                                    'type' => 'button',
                                    'title' => 'Delete',
                                    'class' => 'btn btn-danger',
                                    'onclick' => 'updateStatusContent("' . $deleteStatus . '");'
                                ])

                        ],

                    ],
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'title',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a(\common\helpers\CUtils::subString($model->title, 60), ['update', 'id' => $model->id], ['class' => 'label label-primary']);
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

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
//                            'attribute' => 'type',
                            'header' => 'Slide',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\News */
                                return $model->getSlide();
                            },

                        ],
//                        [
//                            'class' => '\kartik\grid\DataColumn',
//                            'attribute' => 'short_description',
//                            'format' => 'html',
//                            'value' => function ($model, $key, $index, $widget) {
//                                /** @var $model \common\models\News */
//                                return \common\helpers\CUtils::subString($model->short_description, 20);
//                            },
//                        ],
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
                        [
                            'class' => 'kartik\grid\CheckboxColumn',
                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
function submitForm(){
jQuery("#Form_Grid_Content").submit();
}
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
?>
