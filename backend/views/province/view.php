<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Province */

$this->params['breadcrumbs'][] = ['label' => 'Quản lý tỉnh', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Thêm mới', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có muốn xóa đơn vị này không?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'name_en',
            [
                'attribute' => 'status',
                'label' => 'Trạng thái',
                'format' => 'raw',
                'value' => ($model->status == \common\models\Province::STATUS_ACTIVE) ?
                    '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                    '<span class="label label-danger">' . $model->getStatusName() . '</span>',
            ],
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
