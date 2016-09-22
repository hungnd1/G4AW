<?php

use common\models\Category;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="form-body">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 8,
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'display_name')->textInput() ?>
    <?= $form->field($model, 'display_name_en')->textInput() ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'status')->dropDownList(Category::listStatus()) ?>
    <?= $form->field($model, 'type')->hiddenInput(['id' => 'type'])->label(false) ?>



    <div class="row">
        <div class="col-md-offset-2 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>

