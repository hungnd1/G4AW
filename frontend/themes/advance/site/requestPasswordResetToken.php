<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Quên mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <div class="container">
        <div class="box-login-page">
            <div class="form-login">
                <h3><?= Html::encode($this->title) ?></h3>

                <p>Điền địa chỉ email của bạn, sau đó click theo link gửi về email để lấy lại mật khẩu.</p>

                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true,'placeholder'=>'Nhập địa chỉ email'])->label('') ?>

                <div class="form-group">
                      <?= Html::submitButton('Gửi', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
