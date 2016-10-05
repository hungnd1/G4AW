<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/8/2016
 * Time: 11:47 AM
 */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Đổi mật khẩu</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="form-body">

                <?php $form = ActiveForm::begin([
                    'action'=>['site/change-password'],
                    'method' => 'post',
                    'id'=>'aaa',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'fullSpan' => 8,
                ]); ?>

                <div>
                    <?= $form->field($model, 'old_password')->passwordInput()->label('Mật khẩu cũ') ?>
                </div>

                <div>
                    <?= $form->field($model, 'setting_new_password')->passwordInput()->label('Mật khẩu mới')  ?>
                </div>

                <div>
                    <?= $form->field($model, 'confirm_password')->passwordInput()->label('Xác nhận mật khẩu mới')  ?>
                </div>
                <div class="text-center">
                    <?= Html::a('Hủy', ['site/index','id'=>Yii::$app->user->identity->id], ['class' => 'btn btn-danger']) ?>
                    <a href="#" class="btn btn-success" onclick="document.getElementById('aaa').submit()">Đổi mật khẩu</a>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>