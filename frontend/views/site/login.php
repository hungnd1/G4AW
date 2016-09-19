<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-common">

    <h2 class="title-cm">Đăng nhập</h2>
    <div class="form-regis form-2">
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <div>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        </div>
        <div>
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
        <div>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Login', ['class' => 'bt-common-1', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
