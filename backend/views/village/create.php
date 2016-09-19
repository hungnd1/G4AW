<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Village */

$this->title = Yii::t('app', 'Thêm mới xã');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quản lý xã'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"> <i class="fa fa-gift"></i><?= Html::encode($this->title) ?></div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
