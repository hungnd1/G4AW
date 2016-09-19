<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 12/03/2016
 * Time: 2:30 PM
 */
use \kartik\form\ActiveForm;
use yii\helpers\Html;
/** @var $model \common\models\User*/
$avatarPreview = !$model->isNewRecord && !empty($model->avatar);
$this->title ='Cài đặt tài khoản';
?>
<!-- common page-->
<!-- common page-->
<div class="content-common">
    <!--ac-block-->
    <div class="account-block">
        <div class="container-fluid">
            <div class="tp-account">
                <div class="edit-av">

                    <?=   \yii\helpers\Html::img($model->getAvatar(),['class'=>'avt-account']); ?>
                </div>
                <h2 class="us-name"><?=$model->username?></h2>
                <span class="us-add"><?=$model->address?></span>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?= ($active ==1)? 'active':''?>"><a href="#info-user" aria-controls="info-user" role="tab" data-toggle="tab">Thông tin</a></li>
                <li role="presentation" class="<?= ($active ==2)? 'active':''?>"><a href="#password-pr" aria-controls="password-pr" role="tab" data-toggle="tab">Mật khẩu</a></li>
            </ul>

            <div class="container">
                <?= \common\widgets\Alert::widget() ?>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane <?= ($active ==1)? 'active':''?>" id="info-user">
                        <div class="info-user">
                            <div class="form-regis form-1">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'user-setting-form',
                                    'action'=>\yii\helpers\Url::to('/user/setting'),
                                    'options' => ['name' => 'user-setting-form', 'enctype' => 'multipart/form-data'],
                                    'enableClientValidation' => true,
                                    'enableAjaxValidation' => false
                                ]); ?>
                                <div>
                                    <?= $form->field($model, 'fullname')->textInput()  ?>
                                </div>
                                <div>
                                    <?= $form->field($model, 'email')->textInput() ?>
                                </div>
                                <div>
                                    <?= $form->field($model, 'address')->textInput()  ?>
                                </div>
                                <?= $form->field($model, 'avatar')->widget(\kartik\file\FileInput::classname(), [
                                    'pluginOptions' => [
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'browseClass' => 'btn btn-primary btn-block',
                                        'browseIcon' => '<i class="fa fa-camera"></i> ',
                                        'browseLabel' => 'Select Photo',
                                        'initialPreview' => $avatarPreview ? [
                                            Html::img(Yii::getAlias('@web').'/'.Yii::$app->params['avatar'] . "/" . $model->avatar, ['class' => 'file-preview-image',]),

                                        ] : [],
                                    ],
                                    'options' => ['accept' => 'image/*'],
                                ]); ?>
                                <div>
                                    <button type="submit" class="bt-common-1">Lưu thay đổi</button>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane <?= ($active ==2)? 'active':''?>" id="password-pr">
                        <div class="form-regis form-1">
                            <?php $form = ActiveForm::begin([
                                'id' => 'user-change-pass-form',
                                'action'=>\yii\helpers\Url::to('/user/change-password'),
                                'options' => ['name' => 'user-change-pass-form'],
                                'enableClientValidation' => false,
                                'enableAjaxValidation' => true
                            ]); ?>

                            <div>
                                <?= $form->field($model, 'old_password')->passwordInput() ?>
                            </div>
                            <div>
                                <?= $form->field($model, 'setting_new_password')->passwordInput()  ?>
                            </div>
                            <div>
                                <?= $form->field($model, 'confirm_password')->passwordInput()  ?>
                            </div>
                            <div>
                                <button type="submit" class="bt-common-1">Lưu thay đổi</button>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end ac block-->
</div>
<!-- end common page-->
