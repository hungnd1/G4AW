<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use frontend\helpers\UserHelper;
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

                    <h3><?= UserHelper::multilanguage('Đăng Nhập','Login') ?>
<!--                        <a href="--><?//= Url::toRoute(['site/signup']) ?><!--">--><?//= UserHelper::multilanguage('Đăng ký','Sign-in') ?><!--</a>-->
                    </h3>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

<!--                     $form->field($model, 'password')->passwordInput() -->

                    <div class="re-and-log">

<!--                        $form->field($model, 'rememberMe')->checkbox(['value'=>1])->label(UserHelper::multilanguage('Ghi nhớ','Remember')) -->
<!--                        <a href="--><?//= Url::toRoute(['site/request-password-reset'])?><!--" class="link-change-pass">Quên mật khẩu?</a>-->
                    </div>

                    <div class="line-bt" >
<!--                        <a href="#" class="bt-common-1" onclick="document.getElementById('login-form').submit()">ĐĂNG NHẬP</a>-->
                        <?= Html::submitButton(UserHelper::multilanguage('ĐĂNG NHẬP','LOGIN'), ['class' => 'bt-common-1', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
