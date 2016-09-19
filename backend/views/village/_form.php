<?php

use common\models\LeadDonor;
use common\models\User;
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
            'fullSpan' => 8,
        ]);
    ?>

    <?= $form->field($model, 'name' )->textInput(['maxlength' => true]) ?>

    <?=
        $form->field($model, 'lead_donor_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(LeadDonor::find()->andWhere(['status'=>LeadDonor::STATUS_ACTIVE])->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Chọn doanh nghiệp đỡ đầu'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

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
        $form->field($model, 'status')
            ->dropDownList([
                'Chọn trạng thái' => User::listStatus(),
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
                    Html::img(Yii::getAlias('@web').'/'.Yii::getAlias('@village_image'). "/" . $model->image, ['class' => 'file-preview-image','style'=>'width: 100%;']),

                ] : [],
            ],
            'options' => [
                'accept' => 'image/*',
            ],
        ]);
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>



    <div class="row text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tạo Mới') : Yii::t('app', 'Cập nhật'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
