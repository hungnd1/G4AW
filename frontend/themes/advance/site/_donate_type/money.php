<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */
/* @var $campaign common\models\Campaign */
$this->title ='Ủng hộ chiến dịch';

?>

<!-- common page-->
<div class="content-common">
    <h1 class="t-test">Ủng hộ tiền
        <p>Chiến dịch từ thiện "<span><?=$campaign->name?></span>"</p></h1>

    <div class="form-regis form-1">
        <?= \common\widgets\Alert::widget() ?>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false
        ]); ?>
        <div>
            <?= $form->field($model, 'username' )->textInput(['maxlength' => true])->label('Họ và tên') ?>
        </div>
        <div>
            <?= $form->field($model, 'amount', [
                'addon' => ['append' => ['content' => 'VND']],])->textInput(['maxlength' => true])->label('Số tiền') ?>
        </div>
        <?= $form->field($model, 'campaign_id' )->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'user_id' )->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'payment_type' )->hiddenInput()->label(false) ?>
        <div>
            <button type="submit" class="bt-common-1">ĐỒNG Ý</button>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

</div>
<!-- end common page-->