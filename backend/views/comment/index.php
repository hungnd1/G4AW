<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$unPublishStatus = \common\models\Comment::STATUS_DRAFT;
$showStatus = \common\models\Comment::STATUS_ACTIVE;
$deleteStatus = \common\models\Comment::STATUS_INACTIVE;

$this->title = \common\models\Comment::getNameByType($type);
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
$updateLink = \yii\helpers\Url::to(['comment/update-status-content']);

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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'content-index-grid',
                    'filterModel' => $searchModel,
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
                            'header' => 'Tên nội dung',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Comment */
                                if($model->type == \common\models\Comment::TYPE_NEW){
                                    /** @var $news \common\models\News */
                                    $news = \common\models\News::findOne(['id'=>$model->id_new]);
                                    return $news->title;
                                }else{
                                    /** @var $news \common\models\Village */
                                    $news = \common\models\Village::findOne(['id'=>$model->id_new]);
                                    return $news->name;
                                }
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'content',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return $model->content;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'header' => 'Người bình luận',
                            'value' => function ($model, $key, $index, $widget) {
                                    /** @var $model \common\models\Comment */
                                    /** @var $user \common\models\User */
                                    $user = \common\models\User::findOne(['id'=>$model->user_id]);
                                    return $user->username;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Comment */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\Comment::listStatus(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
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
