<?php

use common\models\LeadDonor;
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
$avatarPreview1 = !$model->isNewRecord && !empty($model->map_images);
$avatarPreview2 = !$model->isNewRecord && !empty($model->file_upload);
?>

<div class="form-body">

    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'enableAjaxValidation'=>false,
        'fullSpan' => 8,
    ]);
    ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'district_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Province::find()->all(), 'id', 'display_name'),
        'options' => ['placeholder' => 'Chọn tỉnh'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'lead_donor_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(LeadDonor::find()->andWhere(['status' => LeadDonor::STATUS_ACTIVE])->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Chọn doanh nghiệp đỡ đầu'],
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
        ],
        'options' => [
            'accept' => 'image/*',
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'map_images')->widget(\kartik\file\FileInput::classname(), [
        'pluginOptions' => [

            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => 'Chọn bản đồ địa lý',
            'initialPreview' => $avatarPreview1 ? [
                Html::img(Yii::getAlias('@web') . '/' . Yii::getAlias('@village_image') . "/" . $model->map_images, ['class' => 'file-preview-image', 'style' => 'width: 100%;']),
            ] : [],
        ],
        'options' => [
            'accept' => 'image/*',
        ],
    ]);
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="text-center col-md-12">
        <div class="col-md-10">
            <a target="_blank" href="http://www.latlong.net/"><h5>Nhấn vào đây Nhập địa chỉ xã của bạn cụ thể để lấy Kinh độ(Longitude) và Vĩ độ(Latitude)</h5></a>
        </div>
    </div><br>

    <?= $form->field($model, 'longitude')->textInput(['id'=>'lon'])->label('Kinh độ(Longitude)') ?>

    <?= $form->field($model, 'latitude')->textInput(['id'=>'lat'])->label('Vĩ độ(Latitude)') ?>

    <?= $form->field($model, 'natural_area')->textInput() ?>

    <?= $form->field($model, 'arable_area')->textInput() ?>

    <?= $form->field($model, 'main_industry')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'main_product')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'population')->textInput() ?>

    <?= $form->field($model, 'poor_family')->textInput() ?>

    <?= $form->field($model, 'no_house_family')->textInput() ?>

    <?= $form->field($model, 'gdp')->textInput() ?>

    <?= $form->field($model, 'missing_classes')->textInput() ?>

    <?= $form->field($model, 'lighting_condition')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'water_condition')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'missing_playground')->textInput(['maxlength' => true]) ?>


    <?=
    $form->field($model, 'file_upload')->widget(\kartik\file\FileInput::classname(), [
        'pluginOptions' => [

            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => 'Chọn tệp',
            'initialPreview' => $avatarPreview2 ? [
                Html::img(Yii::getAlias('@web') . '/' . Yii::getAlias('@file_upload') . "/" . $model->file_upload, ['class' => 'file-preview-image',]),
            ] : [],
        ],
        'options' => [
            'accept' => '.doc,.xlsx,.pdf',
        ],
    ]);
    ?>

    <?= $form->field($model, 'establish_date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Ngày thành lập'],
        'pluginOptions' => [
            'autoclose' => true,
            'displayFormat' => 'd/m/yyyy'
        ]
    ]);
    ?>

    <div class="row text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo Mới') : Yii::t('app', 'Cập nhật'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

