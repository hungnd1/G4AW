<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Introduction */
/* @var $form yii\widgets\ActiveForm */
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
