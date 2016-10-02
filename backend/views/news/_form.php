<?php

use common\models\Category;
use common\models\News;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */

// http://kcfinder.sunhater.com/install#dynamic

$videoPreview = !$model->isNewRecord && !empty($model->video_url);
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
    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>


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
    <?= $form->field($model, 'short_description_en')->textarea(['rows' => 6]) ?>
    <?php if($type == News::TYPE_VIDEO) { ?>
        <?= $form->field($model, 'video_url')->widget(\kartik\file\FileInput::classname(), [
            'pluginOptions' => [

//            'showPreview' => false,
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => 'Chọn video giới thiệu',
                'initialPreview' => $videoPreview ? [
                    "<video width='213px' height='160px' controls>
                    <source src='".Yii::getAlias('@web') . '/' . Yii::getAlias('@news_video') . "/" . $model->video_url."' type='video/mp4'>
                    <div class='file-preview-other'>
                        <span class='file-icon-4x'><i class='glyphicon glyphicon-file'></i></span>
                    </div>
                </video>"
                ] : [],
            ],
            'options' => [
                'accept' => 'video/*',
            ],
        ]);
        ?>
        <?= $form->field($model, 'source_url')->textInput(['maxlength' => true]) ?>
    <?php }?>
    <?php if($type != News::TYPE_VIDEO) { ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()
        ->andWhere(['status' => Category::STATUS_ACTIVE])->andWhere(['type'=>$type])->all(), 'id', 'display_name')) ?>
    <?php }else{ ?>
        <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()
            ->andWhere(['status' => Category::STATUS_ACTIVE])->andWhere(['type'=>$type])->all(), 'id', 'display_name'))g) ?>
    <?php }?>
    <?= $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
        'options' => [
            'rows' => 10,
        ],
        'preset' => 'basic'
    ]) ?>
    <?= $form->field($model, 'content_en')->widget(\common\widgets\CKEditor::className(), [
        'options' => [
            'rows' => 10,
        ],
        'preset' => 'basic'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
