<?php

use common\widgets\Player;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\LeadDonor;

/* @var $this yii\web\View */
/* @var $model common\models\LeadDonor */
/* @var $form yii\widgets\ActiveForm */
$avatarPreview = !$model->isNewRecord && !empty($model->image);
$videoPreview = !$model->isNewRecord && !empty($model->video);
?>

<div class="form-body">

    <?php $form = ActiveForm::begin(
        [
            'options' => ['enctype' => 'multipart/form-data'],
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'fullSpan' => 8,
        ]
    ); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'status')->dropDownList(LeadDonor::getListStatus());
    ?>

    <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::classname(), [
        'pluginOptions' => [

            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => 'Chọn hình ảnh đại diện',
            'initialPreview' => $avatarPreview ? [
                Html::img(Yii::getAlias('@web') . '/' . Yii::getAlias('@lead_donor_image') . "/" . $model->image, ['class' => 'file-preview-image', 'style' => 'width: 100%;']),
            ] : [],
        ],
        'options' => [
            'accept' => 'image/*',
        ],
    ]);
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'video')->widget(\kartik\file\FileInput::classname(), [
        'pluginOptions' => [

//            'showPreview' => false,
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => 'Chọn video giới thiệu',
            'initialPreview' => $videoPreview ? [
                "<video width='213px' height='160px' controls>
                    <source src='".Yii::getAlias('@web') . '/' . Yii::getAlias('@lead_donor_video') . "/" . $model->video."' type='video/mp4'>
                    <div class='file-preview-other'>
                        <span class='file-icon-4x'><i class='glyphicon glyphicon-file'></i></span>
                    </div>
                </video>"
            ] : [],
        ],
        'options' => [
            'accept' => 'video/*',
        ],
    ]);
    ?>

    <?php
    if($model->video){
        echo Player::widget(['video_url' => $model->getVideoUrl()]);
    }
    ?>

    <div class="row text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo mới doanh nghiệp đỡ đầu') : Yii::t('app', 'Cập nhật'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
