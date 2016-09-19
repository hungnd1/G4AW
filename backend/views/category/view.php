<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->display_name;
$this->params['breadcrumbs'][] = ['label' => 'Danh mục', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">
    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có muốn xóa danh mục này không?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'display_name',

            'description:ntext',
            [
                'attribute' => 'status',
                'label' => 'Trạng thái',
                'format' => 'raw',
                'value' => ($model->status == \common\models\Category::STATUS_ACTIVE) ?
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
