<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\Area */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-body">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'method' => 'post',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 8,
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(\common\models\Area::listStatus()) ?>
    <?php if ($model->isNewRecord) { ?>
    <?= $form->field($model, 'image')->label('Ảnh đại diện')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showPreview' => true,
            'overwriteInitial' => false,
            'showRemove' => false,
            'showUpload' => false
        ]
    ]); ?>
    <?php } else { ?>
        <?= $form->field($model, 'image')->label('Ảnh đại diện')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'previewFileType' => 'any',
                'initialPreview' => [
                    Html::img(Url::to($model->getThumbnailLink()), ['class' => 'file-preview-image', 'alt' => $model->image, 'title' => $model->image]),
                ],
                'showPreview' => true,
                'initialCaption' => $model->getThumbnailLink(),
                'overwriteInitial' => true,
                'showRemove' => false,
                'showUpload' => false
            ]
        ]); ?>
    <?php } ?>

    <br><br>
    <div class="row">
        <div class="col-md-offset-2 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <br>
    <br><br>

    <?php ActiveForm::end(); ?>

</div>
