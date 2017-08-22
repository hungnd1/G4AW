<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 15/03/2016
 * Time: 11:20 AM
 */
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\select2\Select2;

/** @var $model \common\models\DonationRequest */
$urlUploadImage = \yii\helpers\Url::to(['/app/upload']);
$avatarPreview = !$model->isNewRecord && !empty($model->image);
?>
<?php  if($model->isNewRecord){ ?>
    <h1>ĐĂNG KÍ NHẬN TRỢ GIÚP</h1>
<?php }else { ?>
    <h1>CẬP NHẬT YÊU CẦU NHẬN TRỢ GIÚP</h1>
<?php } ?>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
    'id' => 'gdgd'
]); ?>
<div class="list-field">
    <div class="l-f-1">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Tiêu đề (*)') ?>
    </div>
    <div class="l-f-1">
        <?= $form->field($model, 'expected_amount')->textInput(['maxlength' => true])->label('Số tiền cần hỗ trợ (VNĐ)') ?>
    </div>
    <div class="l-f-1">
        <?= $form->field($model, 'expected_items')->textInput(['maxlength' => true])->label('Vật phẩm cần hỗ trợ') ?>
    </div>
    <div class="l-f-1">
        <?=
        $form->field($model, 'village_id')->widget(Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\common\models\Village::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Chọn xã bạn sinh sống'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Xã bạn sinh sống (*)');
        ?>
    </div>
    <div class="l-f-1">
        <?php if ($model->isNewRecord) { ?>
            <?=
            $form->field($model, 'image')->widget(FileInput::classname(), [
                'pluginOptions' => [
                    'showCaption' => false,
                    'showRemove' => false,
                    'overwriteInitial' => false,
                    'showUpload' => false,
                    'browseClass' => 'btn btn-primary btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'width'=>100,
                    'browseLabel' => 'Chọn hình ảnh đại diện',
                    'initialPreview' => $avatarPreview ? [
                        \yii\helpers\Html::img(Yii::getAlias('@web').'/'.Yii::getAlias('@donation_uploads'). "/" . $model->image, ['class' => 'file-preview-image','style'=>'width: 100%;',]),

                    ] : [],
                ],
                'options' => [
                    'accept' => 'image/*',
                ],
            ])->label('Ảnh đại diện (*)');
            ?>
        <?php } else { ?>
            <?=
            $form->field($model, 'image_update')->widget(FileInput::classname(), [
                'pluginOptions' => [
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'browseClass' => 'btn btn-primary btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'browseLabel' => 'Chọn hình ảnh đại diện',
                    'initialPreview' => $avatarPreview ? [
                        \yii\helpers\Html::img(Yii::getAlias('@web').'/'.Yii::getAlias('@donation_uploads'). "/" . $model->image, ['class' => 'file-preview-image','style'=>'width: 100%;',]),

                    ] : [],
                ],
                'options' => [
                    'accept' => 'image/*',
                ],
            ]);
            ?>
        <?php } ?>
    </div>
    <div class="l-f-1">
        <?= $form->field($model, 'short_description')->textarea(['class' => 'textarea-1'])->label('Mô tả ngắn') ?>
    </div>
</div>
<div class="box-content-rq">
    <span class="head-box" style="font-weight: 700;">Lý do / Hoàn cảnh</span><br>
    <?= $form->field($model, 'content')->widget(\dosamigos\ckeditor\CKEditor::className(), [
        'options' => ['rows' => 4],
        'preset' => 'basic',
        'clientOptions' => [
            'filebrowserUploadUrl' => $urlUploadImage
        ]
    ])->label('') ?>
</div>
<div class="line-bt" style="width:100px">
    <?php  if($model->isNewRecord){ ?>
    <button class="bt-common-1">ĐĂNG KÝ</button>
    <?php }else { ?>
    <button class="bt-common-1">Cập nhật</button>
    <?php } ?>
</div>
<?php ActiveForm::end(); ?>


