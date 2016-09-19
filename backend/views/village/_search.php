<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VillageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="village-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'province_name') ?>

    <?= $form->field($model, 'district_id') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'natural_area') ?>

    <?php // echo $form->field($model, 'arable_area') ?>

    <?php // echo $form->field($model, 'main_industry') ?>

    <?php // echo $form->field($model, 'main_product') ?>

    <?php // echo $form->field($model, 'population') ?>

    <?php // echo $form->field($model, 'poor_family') ?>

    <?php // echo $form->field($model, 'no_house_family') ?>

    <?php // echo $form->field($model, 'missing_classes') ?>

    <?php // echo $form->field($model, 'lighting_condition') ?>

    <?php // echo $form->field($model, 'water_condition') ?>

    <?php // echo $form->field($model, 'missing_playground') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
