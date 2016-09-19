<?php

use common\models\Campaign;
use common\models\Category;
use common\models\LeadDonor;
use common\models\News;
use common\models\Village;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */

// http://kcfinder.sunhater.com/install#dynamic
$kcfOptions = array_merge(\common\widgets\CKEditor::$kcfDefaultOptions, [
    'uploadURL' => Yii::getAlias('@web') . '/uploads/',
    'access' => [
        'files' => [
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true,
        ],
        'dirs' => [
            'create' => true,
            'delete' => true,
            'rename' => true,
        ],
    ],
]);

// Set kcfinder session options
Yii::$app->session->set('KCFINDER', $kcfOptions);
?>

<div class="form-body">

    <?php $form = ActiveForm::begin(
        [
            'options' => ['enctype' => 'multipart/form-data'],
            'method' => 'post',
        ]
    ); ?>

    <?= $form->field($model, 'type')->hiddenInput(['id' => 'type'])->label(false) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php
    if ($model->type == News::TYPE_CAMPAIGN) {
        echo $form->field($model, 'campaign_id')->dropDownList(
            ArrayHelper::map(Campaign::getCampaignByUser(), 'id', 'name'),
            ['id' => 'campaign_id', ['prompt' => 'Chọn chiến dịch ...']])->label('Chiến dịch (*)');
    }
    ?>

    <?php
    if ($model->type == News::TYPE_IDEA || $model->type == News::TYPE_TRADE) {
        echo $form->field($model, 'village_array')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Village::getVillageByUser(), 'id', 'name'),
            'options' => [
                'placeholder' => 'Chọn xã ...',
                'multiple' => true
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Xã (*)');
        if ($model->type == News::TYPE_TRADE) {
            echo $form->field($model, 'price')->textInput(['maxlength' => true])->label('Giá (*)');
        }
    }
    ?>
    <?php
    if ($model->type == News::TYPE_DONOR) {
        echo $form->field($model, 'lead_donor_id')->dropDownList(
            ArrayHelper::map(LeadDonor::getLeadDonorByUser(), 'id', 'name'),
            [['prompt' => 'Chọn doanh nghiệp đỡ đầu ...']])->label('Doanh nghiệp đỡ đầu (*)');
    }
    ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\News::listStatus()) ?>

    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'thumbnail')->label('Ảnh đại diện')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showPreview' => true,
                'overwriteInitial' => false,
                'showRemove' => false,
                'showUpload' => false
            ]
        ]); ?>
    <?php } else { ?>
        <?= $form->field($model, 'thumbnail')->label('Ảnh đại diện')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'previewFileType' => 'any',
                'initialPreview' => [
                    Html::img(Url::to($model->getThumbnailLink()), ['class' => 'file-preview-image', 'alt' => $model->thumbnail, 'title' => $model->thumbnail]),
                ],
                'showPreview' => true,
                'initialCaption' => $model->getThumbnailLink(),
                'overwriteInitial' => true,
                'showRemove' => false,
                'showUpload' => false
            ]
        ]); ?>
    <?php } ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()
        ->andWhere(['status' => Category::STATUS_ACTIVE])->all(), 'id', 'display_name')) ?>

    <?= $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
        'options' => [
            'rows' => 6,
        ],
        'preset' => 'basic'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
