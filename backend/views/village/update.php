<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Village */

$this->title = Yii::t('app', 'Cập nhật {modelClass}: ', [
    'modelClass' => 'xã',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quản lý xã'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'cập nhật');
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-gift"></i><?=$this->title?></div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('form_update', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
