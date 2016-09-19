<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/8/2016
 * Time: 9:54 AM
 */
use common\models\User;
use kartik\widgets\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$avatarPreview = $model->isNewRecord;
?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index'])?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/my-page','id'=>Yii::$app->user->identity->id])?>">Cá nhân</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/update','id'=>Yii::$app->user->identity->id])?>">Cập nhật thông tin</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <?php $image = $model->getAvatar() ?>
                    <img src="<?= $image ?>"><br>
                    <h4><?= $model->fullname ?></h4>
                    <p><?= $model->address ?></p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Số điện thoại</span><br>
                    <span class="b-span"><?= $model->phone_number ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span"><?= $model->email ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Giới tính</span><br>
                    <span class="b-span">
                        <?= User::getGenderName($model->gender)?>
                    </span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Tuổi</span><br>
                    <span class="b-span"><?= User::getOld($model->birthday) ?></span>
                </div>
            </div>
            <div class="right-cn">
                <div class="info-update">
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                        'method' => 'post',
                        'action' => ['user/update','id'=>$model->id],
                        'id' => 'change_pass'
                    ]); ?>
                    <div class="block-edit top-bl-edit">
                        <a class="change-avt">
                            <img src="<?= $model->getAvatar() ?>"><br>
                        </a>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'username')->textInput(['readonly' => true])->label('Tên đăng nhập') ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'fullname')->textInput(['class'=>'t-span', 'maxlength' => 100])->label('Họ và Tên') ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'address')->textInput(['class'=>'t-span', 'maxlength' => 100])->label('Địa chỉ') ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'email')->textInput(['class'=>'t-span', 'maxlength' => 100])->label('Email (*)') ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'phone_number')->textInput(['class'=>'t-span', 'maxlength' => 100])->label('Số điện thoại') ?>
                    </div>
                    <div class="block-edit">
                        <?=
                            $form->field($model, 'gender')->dropDownList([
                                    'Chọn giới tính' => User::listGender(),
                                ])
                                ->label('Giới tính')
                        ?>
                    </div>

                    <div class="block-edit">
                        <?=
                            $form->field($model, 'birthday')->widget(DatePicker::classname(), [
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'placeholder' => ''
                                ],
                            ])->label('Ngày sinh')
                        ?>
                    </div>
                    <div class="block-edit">
                        <?=
                        $form->field($model, 'avatar')->widget(\kartik\file\FileInput::classname(), [
                            'pluginOptions' => [

                                'showCaption' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'browseClass' => 'btn btn-primary btn-block',
                                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                                'browseLabel' => 'Chọn hình ảnh đại diện',
                                'initialPreview' => $avatarPreview
                            ],
                            'options' => [
                                'accept' => 'image/*',
                            ],
                        ])->label('Ảnh đại diện')
                        ?>
                    </div>
                    <div class="line-bt line-bt-3">
                        <?= Html::a('Huỷ', ['user/my-page','id'=>$model->id], ['class' => 'bt-common-1 bt-st-2']) ?>
                        <?= Html::submitButton('Lưu thay đổi', ['class' => 'bt-common-1','style'=>'width:180px']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
