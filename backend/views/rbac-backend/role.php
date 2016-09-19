<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý nhóm quyền';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Tạo nhóm quyền', ['create-role'], ['class' => 'btn btn-success']) ?>
        <?php // Html::a('Generate Role', ['generate-role'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'id' => 'rbac-role',
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'pjax' => false,
        'hover' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'header'=>'Tên nhóm quyền',
                'format' => 'html',
                'noWrap' => true,
                'value' => function ($model, $key, $index, $widget) {
                    /**
                     * @var $model \common\models\AuthItem
                     */
                    $res = Html::a($model->description, ['rbac-backend/update-role', 'name' => $model->name]);
                    $res .= " [". sizeof($model->children) . "]";
                    return $res;
                },
            ],
            [
                'attribute' => 'description',
                'header'=>'Mô tả',
                'format' => 'html',
                'noWrap' => true,
                'value' => function ($model, $key, $index, $widget) {

                    return $model->description;
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{set-password} {view} {update} {delete}',
               'buttons'=> [
                    'view' => function ($url, $model, $key) {
                        return   Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->urlManager->createUrl(['rbac-backend/view-role','name'=>$model->name]),[
                            'title' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ]) ;
                    },
                    'update' => function ($url, $model, $key) {
                        return   Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['rbac-backend/update-role','name'=>$model->name]),[
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                            ]) ;
                    },
                   'delete' => function ($url, $model) {
                       return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['rbac-backend/delete-role','name'=>$model->name]), [
                           'title' => Yii::t('yii', 'Delete'),
                           'data-confirm' => Yii::t('yii', 'Bạn có chắc muốn xóa nhóm quyền này?'),
                           'data-method' => 'post',
                           'data-pjax' => '0',
                       ]);
                   }
                 ],
            ],
        ],
    ]); ?>

</div>


