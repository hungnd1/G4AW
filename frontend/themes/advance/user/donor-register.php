<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 12/03/2016
 * Time: 2:30 PM
 */
use \kartik\form\ActiveForm;

/** @var $model \frontend\models\SignupForm */
$this->title ='Đăng ký tài khoản nhà hảo tâm';
?>
<!-- common page-->
<div class="content-common">
    <h2 class="title-cm">ĐĂNG KÝ NHÀ HẢO TÂM<p>Đăng ký để đóng góp từ thiện, theo dõi các chiến dịch</p></h2>
    <div class="form-regis form-1">
        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',

            'options' => ['name' => 'information'],
        ]); ?>
        <div>

            <?= $form->field($model, 'username')->textInput() ?>
        </div>
        <div>

            <?= $form->field($model, 'email')->textInput() ?>
        </div>
        <div>

            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
        <div>

            <?= $form->field($model, 'confirm_password')->passwordInput() ?>
        </div>
        <div>

            <?= $form->field($model, 'fullname')->textInput() ?>
        </div>
        <div>

            <?= $form->field($model, 'phone_number')->textInput() ?>
        </div>
        <div>

            <?= $form->field($model, 'address')->textInput() ?>
        </div>
        <div>
            <button type="submit" class="bt-common-1">Đăng ký</button>
            <?= \frontend\widgets\FBAuthChoice::widget([
                'baseAuthUrl' => ['social/auth']
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
<!-- end common page-->

