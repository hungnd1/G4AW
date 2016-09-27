<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use common\models\Village;
use common\models\Province;

/* @var $this yii\web\View */
/* @var $model common\models\Village */
/* @var $form yii\widgets\ActiveForm */


$avatarPreview = !$model->isNewRecord && !empty($model->image);
?>

<div class="form-body">

    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'enableClientValidation' => false,
        'fullSpan' => 8,
    ]);
    ?>
    <?= $form->field($model, 'name' )->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_en' )->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'number_code' )->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'latitude' )->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'longitude' )->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'establish_date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Ngày thành lập'],
        'pluginOptions' => [
            'autoclose' => true,
            'displayFormat' => 'd/m/yyyy'
        ]
    ]);
    ?>

    <?=
    $form->field($model, 'id_province')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Province::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Chọn tỉnh'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'status')
        ->dropDownList([
            'Chọn trạng thái' => Village::getListStatus(),
        ]);
    ?>



    <?=
    $form->field($model, 'image')->widget(\kartik\file\FileInput::classname(), [
        'pluginOptions' => [

            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => 'Chọn hình ảnh đại diện',
            'initialPreview' => $avatarPreview ? [
                Html::img(Yii::getAlias('@web') . '/' . Yii::getAlias('@village_image') . "/" . $model->image, ['class' => 'file-preview-image', 'style' => 'width: 100%;']),
            ] : [],
            'overwriteInitial' => false,
            'initialCaption'=>"The Moon and the Earth",
        ],
        'options' => [
            'accept' => 'image/*',
        ],
    ]);
    ?>


    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'description_en')->textarea(['rows' => 6]) ?>


    <div class="row text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo Mới') : Yii::t('app', 'Cập nhật'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

