<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <div class="container">
        <div class="box-login-page">
            <div class="form-login">
                <div class=" form-login">

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                    ]); ?>

                    <h3>Đăng Nhập<a href="<?= Url::toRoute(['site/signup']) ?>">Đăng ký</a></h3>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <div class="re-and-log">

                        <?= $form->field($model, 'rememberMe')->checkbox(['value'=>1])->label('Ghi nhớ') ?>
<!--                        <a href="--><?//= Url::toRoute(['site/request-password-reset'])?><!--" class="link-change-pass">Quên mật khẩu?</a>-->
                    </div>

                    <div class="line-bt" >
<!--                        <a href="#" class="bt-common-1" onclick="document.getElementById('login-form').submit()">ĐĂNG NHẬP</a>-->
                        <?= Html::submitButton('ĐĂNG NHẬP', ['class' => 'bt-common-1', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
