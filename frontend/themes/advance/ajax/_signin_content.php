<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-body">

    <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['/site/login'],
        'enableClientValidation' => false,
        'enableAjaxValidation' => true
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true])  ?>

    <?= $form->field($model, 'password')->passwordInput()  ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>


    <div class="form-group">
        <?= Html::submitButton('Đăng nhập', ['class' => 'bt-login', 'name' => 'login-button']) ?>
        <?= \frontend\widgets\FBAuthChoice::widget([
            'baseAuthUrl' => ['social/auth']
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

