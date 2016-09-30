<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use frontend\helpers\UserHelper;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <div class="container">

        <div class="box-login-page">
            <div class="form-login">
                <h3><?= UserHelper::multilanguage('Đăng Ký','Sign-in') ?><a href="<?= Url::toRoute(['site/login']) ?>"><?= UserHelper::multilanguage('Đăng nhập','Login') ?></a></h3>

                <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' =>true,
                ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true,'maxlength'=>11,'minlength'=>9])->label(UserHelper::multilanguage('Tên đăng nhập (*)','Username (*)')) ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(UserHelper::multilanguage('Email (*)','Email (*)')) ?>

                <?= $form->field($model, 'password')->passwordInput()->label(UserHelper::multilanguage('Mật khẩu (*)','Password (*)')) ?>

                <?= $form->field($model, 'confirm_password')->passwordInput()->label(UserHelper::multilanguage('Xác nhận mật khẩu (*)','Confirm password (*)')) ?>


                <?= $form->field($model, 'address')->textInput() ?>

                <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="re-and-log re-and-log2">
                    <?= $form->field($model,'accept')->checkbox()?>
                </div>

                <div class="text-center line-bt" >
                    <a href="#" class="bt-common-1" onclick="document.getElementById('form-signup').submit()"><?= UserHelper::multilanguage('Đăng Ký','Sign-in') ?></a>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

