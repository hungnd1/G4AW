<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title =  $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý nhóm quyền', 'url' => ['role']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">

    <div class="col-md-12">
        <p>
            <?= Html::a('Cập nhật', ['update-role', 'name' => $model->name], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Xóa', ['delete-role', 'name' => $model->name], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Bạn có chắc chắn muốn xóa nhóm quyền này?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i><?= $this->title ?>
                </div>
            </div>
            <div class="portlet-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        'description',
                        [
                            'attribute' => 'created_at',
                            'value' => date('d/m/Y H:i:s', $model->created_at),
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value' => date('d/m/Y H:i:s', $model->updated_at),
                        ],
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>