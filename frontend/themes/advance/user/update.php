<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/8/2016
 * Time: 9:54 AM
 */
use common\models\User;
use frontend\helpers\UserHelper;
use kartik\widgets\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$avatarPreview = $model->isNewRecord;
?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index'])?>"><?= UserHelper::multilanguage('Trang chủ','Home') ?></a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/my-page','id'=>Yii::$app->user->identity->id])?>"><?= UserHelper::multilanguage('Cá nhân','Personal') ?></a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/update','id'=>Yii::$app->user->identity->id])?>"><?= UserHelper::multilanguage('Cập nhật thông tin','Update information') ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <?php $image = $model->getImageLink() ? $model->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png'; ?>
                    <img src="<?= $image ?>"><br>
                    <h4><?= $model->full_name ?></h4>
                    <p><?= $model->address ?></p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Số điện thoại','Phone number') ?></span><br>
                    <span class="b-span"><?= $model->username ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span"><?= $model->email ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Giới tính','Gender') ?></span><br>
                    <span class="b-span">
                        <?= \common\models\Subscriber::getGenderName($model->sex)?>
                    </span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?=  UserHelper::multilanguage('Tuổi','Old') ?></span><br>
                    <span class="b-span"><?= \common\models\Subscriber::getOld($model->birthday) ?></span>
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
                            <img src="<?= $model->getImageLink() ? $model->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>"><br>
                        </a>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'username')->textInput(['readonly' => true])->label(UserHelper::multilanguage('Tên đăng nhập (*)','Username (*)')) ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'full_name')->textInput(['class'=>'t-span', 'maxlength' => 100])->label(UserHelper::multilanguage('Họ và tên','Fullname')) ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'address')->textInput(['class'=>'t-span', 'maxlength' => 100])->label(UserHelper::multilanguage('Địa chỉ','Address')) ?>
                    </div>
                    <div class="block-edit">
                        <?= $form->field($model, 'email')->textInput(['class'=>'t-span', 'maxlength' => 100])->label(UserHelper::multilanguage('Email (*)','Email (*)')) ?>
                    </div>
                    <div class="block-edit">
                        <?=
                            $form->field($model, 'sex')->dropDownList([
                                    'Chọn giới tính' => User::listGender(),
                                ])
                                ->label(UserHelper::multilanguage('Giới tính','Gender'))
                        ?>
                    </div>

                    <div class="block-edit">
                        <?=
                            $form->field($model, 'birthday')->widget(DatePicker::classname(), [
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'placeholder' => '',
                                    'dateFormat'=>'yyyy-MM-dd'
                                ],
                            ])->label(UserHelper::multilanguage('Ngày sinh','Birthday'))
                        ?>
                    </div>
                    <div class="block-edit">
                        <?=
                        $form->field($model, 'avatar_url')->widget(\kartik\file\FileInput::classname(), [
                            'pluginOptions' => [

                                'showCaption' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'browseClass' => 'btn btn-primary btn-block',
                                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                                'browseLabel' => UserHelper::multilanguage('Chọn ảnh đại diện','Choose image'),
                                'initialPreview' => $avatarPreview
                            ],
                            'options' => [
                                'accept' => 'image/*',
                            ],
                        ])->label(UserHelper::multilanguage('Ảnh đại diện','Avatar'))
                        ?>
                    </div>
                    <div class="line-bt line-bt-3">
                        <?= Html::a(UserHelper::multilanguage('Hủy','Cancel'), ['user/my-page','id'=>$model->id], ['class' => 'bt-common-1 bt-st-2']) ?>
                        <?= Html::submitButton(UserHelper::multilanguage('Lưu thay đổi','Save'), ['class' => 'bt-common-1','style'=>'width:180px']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
