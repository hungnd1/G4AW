<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'campaign_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'transaction_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'telco') ?>

    <?php // echo $form->field($model, 'scratch_card_code') ?>

    <?php // echo $form->field($model, 'scratch_card_serial') ?>

    <?php // echo $form->field($model, 'shortcode') ?>

    <?php // echo $form->field($model, 'sms_mesage') ?>

    <?php // echo $form->field($model, 'bank_transaction_id') ?>

    <?php // echo $form->field($model, 'bank_transaction_detail') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'error_code') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
