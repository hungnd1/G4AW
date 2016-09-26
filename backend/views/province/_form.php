<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Province */
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

    <?= $form->field($model, 'name' )->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_en' )->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'status')
        ->dropDownList([
            'Chọn trạng thái' => \common\models\Province::getListStatus(),
        ]);
    ?>




    <div class="row text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo Mới') : Yii::t('app', 'Cập nhật'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
