<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Campaign */

$this->title = Yii::t('app', 'CẬP NHẬT CHIẾN DỊCH');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Campaigns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- common page-->
<div class="content-common">
    <h2 class="title-cm"><?= Html::encode($this->title) ?></h2>
    <div class="form-regis form-2">
        <?= $this->render('_form', [
            'model' => $model,
            'requestModel'=>$requestModel
        ]) ?>
    </div>
</div>
<!-- end common page-->