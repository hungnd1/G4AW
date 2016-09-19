<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

?>
<!-- content -->
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= \yii\helpers\Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="">Liên hệ</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="block-contact">
                <h5>Mọi thông tin vui lòng liên hệ:</h5>

                <p><span class="left-span">Ông/bà:</span>  <span class="right-span">Nguyễn Văn A</span><br>
                    <span class="left-span">Địa chỉ:</span> <span class="right-span">Hà Nội</span><br>
                    <span class="left-span">SĐT:</span> <span class="right-span"> 0123456789</span><br>
                    <span class="left-span">Email:</span>  <span class="right-span"><a href="" class="color-1">abc@gmail.com</a></span><br><br>
                    <span class="left-span">Đơn vị:</span> <span class="right-span">Cty THHH ABC</span><br>
                    <span class="left-span">Website:</span><span class="right-span"> www.abc.com.vn</span><br>
                    <span class="left-span">Địa chỉ:</span>  <span class="right-span">Hà Nội</span><br>
                    <span class="left-span">SĐT:</span>  <span class="right-span">0123456789</span><br>
                    <span class="left-span">Email:</span>  <span class="right-span"><a href="" class="color-2">abc@gmail.com</a></span></p>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
