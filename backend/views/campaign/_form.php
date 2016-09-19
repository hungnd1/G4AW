<?php

use common\models\DonationRequest;
use common\models\Village;
use kartik\form\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Campaign */

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
        'formConfig' => [
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'deviceSize' => ActiveForm::SIZE_SMALL,
        ],
        'options' => ['enctype' => 'multipart/form-data'],
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
    ]
); ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?=
        $form->field($model, 'start_date')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Enter event time ...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy hh:ii:ss',
            ]
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'end_date')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Enter event time ...'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy hh:ii:ss',
            ]
        ]);
        ?>
    </div>

</div>


<?php if ($model->isNewRecord) { ?>
    <?php if ($donation_request) { ?>
        <div class="row">
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'village_id')
                    ->dropDownList(ArrayHelper::map(Village::find()->all(), 'id', 'name'), ["disabled" => "disabled"]);
                echo $form->field($model, 'village_id')->hiddenInput()->label(false);
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'donation_request_id')
                    ->dropDownList(ArrayHelper::map(DonationRequest::find()->all(), 'id', 'title'), ["disabled" => "disabled"]);
                echo $form->field($model, 'donation_request_id')->hiddenInput()->label(false);
                ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'village_id')->widget(Select2::classname(), [
                    'data' => Village::getArrayVillageByUser(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>
    <?php } ?>
<?php
} else {
    if ($model->donation_request_id > 0) {
        ?>
        <div class="row">
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'village_id')
                    ->dropDownList(ArrayHelper::map(Village::find()->all(), 'id', 'name'), ["disabled" => "disabled"]);
                echo $form->field($model, 'village_id')->hiddenInput()->label(false);
                //
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'donation_request_id')
                    ->dropDownList(ArrayHelper::map(DonationRequest::find()->all(), 'id', 'title'), ["disabled" => "disabled"]);
                echo $form->field($model, 'donation_request_id')->hiddenInput()->label(false);
                ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'village_id')->widget(Select2::classname(), [
                    'data' => Village::getArrayVillageByUser(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>

            </div>
        </div>
    <?php } ?>
<?php } ?>


<div class="row">
    <div class="col-md-12">

        <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    </div>
</div>
<?=
$form->field($model, 'thumbnail')->widget(\kartik\widgets\FileInput::classname(), [
    'options' => [
        'multiple' => false,
        'accept' => '.png,.jpg,.jpeg,.gif'
    ],
    'pluginOptions' => [
        'showPreview' => true,
        'showUpload' => false,
        'overwriteInitial' => true,
        'initialPreview' =>
            $model->thumbnail ? [Html::img($model->getCampaignThumbnail(), ['class' => 'file-preview-image'])] : [],

    ],

]); ?>
<div class="row">

    <div class="col-md-12">
        <?= $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
            'options' => [
                'rows' => 6,
            ],
            'preset' => 'basic'
        ]) ?>
    </div>

</div>




<?php //echo $form->field($model, 'screenshots')->hiddenInput(['id' => 'screenshots_tmp'])->label(false) ?>
<?php
/*echo $form->field($model, 'album_image[]')->widget(\kartik\file\FileInput::classname(), [
    'options' => [
        'multiple' => true,
        'accept' => '.png,.jpg,.jpeg,.gif'
    ],
    'pluginOptions' => [
        'uploadUrl' => Url::to(['/campaign/upload-file']),
        'showPreview' => true,
        'showUpload' => true,
        'overwriteInitial' => false,
        'initialPreview' => isset($screen_phone_preview) ? $screen_phone_preview : [],
        'initialPreviewConfig' => isset($screen_phone) ? $screen_phone : [],
    ],
    'pluginEvents' => [
        "fileuploaded" => "function(event, data, previewId, index) {
                var response=data.response;
                console.log(response.success);
                console.log(response);
                if(response.success){
                    console.log(response.output);
                    var current_screenshots=response.output;
                    var old_value_text=$('#screenshots_tmp').val();
                    console.log('xxx'+old_value_text);
                    if(old_value_text !=null && old_value_text !='' && old_value_text !=undefined)
                    {
                        var old_value=jQuery.parseJSON(old_value_text);

                        if(jQuery.isArray(old_value)){
                            console.log(old_value);
                            old_value.push(current_screenshots);
                        }
                    }
                    else{
                        var old_value= [current_screenshots];
                    }
                    $('#screenshots_tmp').val(JSON.stringify(old_value));
                 }
             }",

    ],

]);*/
?>


<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Hủy bỏ', ['index'], ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
