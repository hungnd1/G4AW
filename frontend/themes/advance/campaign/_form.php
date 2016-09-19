<?php
use common\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use  kartik\form\ActiveForm;
use  yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\Campaign */
/* @var $form  \kartik\form\ActiveForm */
/** @var  $requestModel \common\models\DonationRequest */
$urlUploadImage = \yii\helpers\Url::to(['/app/upload']);
$thumbnailPreview = !$model->isNewRecord && !empty($model->thumbnail);
$option=[];
if($requestModel !== null){
    $option['disabled']='disabled';
}
?>

<?= \common\widgets\Alert::widget() ?>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [ 'enctype' => 'multipart/form-data'],
]); ?>


    <div>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Tên chiến dịch') ?>
    </div>
    <div class="right-c">
        <?= $form->field($model, 'expected_amount', [
            'addon' => ['append' => ['content'=>'VND']], ])->textInput(['maxlength' => true])->label('Số tiền cần hỗ trợ') ?>
    </div>


    <div>
        <?= $form->field($model, 'started_at')->widget(\yii\jui\DatePicker::classname(), [

            'dateFormat' => 'dd-MM-yyyy',


        ]) ?>


    </div>
    <div class="right-c">
        <?= $form->field($model, 'finished_at')->widget(\yii\jui\DatePicker::classname(), [

            'dateFormat' => 'dd-MM-yyyy',


        ]) ?>
    </div>





    <?php

    $listUserReqest = \common\models\DonationRequest::find()->select(['created_by'])
        ->andWhere(['organization_id' => Yii::$app->user->id]) ;
    $donees = \common\models\User::find()
        ->select(['id', 'fullname'])
        ->andWhere(['status' => \common\models\User::STATUS_ACTIVE])
        ->andWhere(['id' => $listUserReqest])->asArray()->all();
    $dropdownData = \yii\helpers\ArrayHelper::map($donees, 'id', 'fullname');
    $requestData = \yii\helpers\ArrayHelper::map(\common\models\DonationRequest::getRequestFrom($model->created_for_user,Yii::$app->user->id,$model->isNewRecord), 'id', 'name');
    ?>
    <div>
        <?= $form->field($model, 'created_for_user')->dropDownList($dropdownData, \yii\helpers\ArrayHelper::merge(['id' => 'user-request-id', 'prompt' => 'Chọn bên yêu cầu'],$option))->label('Bên yêu cầu'); ?>
    </div>
    <div class="right-c">
        <?= $form->field($model, 'donation_request_id')->widget(\kartik\widgets\DepDrop::classname(), [
            'options' => yii\helpers\ArrayHelper::merge(['id' => 'donation_request_id'],$option),
            'data' => $requestData,
            'pluginOptions' => [
                'depends' => ['user-request-id'],
                'placeholder' => 'Chọn yêu cầu',
                'url' => \yii\helpers\Url::to(['/campaign/get-request-to'])
            ]
        ]) ?>
    </div>



<div>
    <?= $form->field($model, 'short_description')->textarea(['class' => 'textarea-1'])->label('Mô tả ngắn') ?>
</div>
<div>
    <?= $form->field($model, 'content')->widget(\dosamigos\ckeditor\CKEditor::className(), [
        'options' => ['rows' => 8],
        'preset' => 'basic',
        'clientOptions' => [
            'filebrowserUploadUrl' => $urlUploadImage
        ]
    ])->label('Chi tiết chiến dịch') ?>

</div>
<div>
    <?= $form->field($model, 'thumbnail')->widget(\kartik\file\FileInput::classname(), [
        'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="fa fa-camera"></i> ',
            'browseLabel' => 'Select Photo',
            'initialPreview' => $thumbnailPreview ? [
                Html::img(Yii::getAlias('@web') . '/' . Yii::$app->params['upload_images'] . "/" . $model->thumbnail, ['class' => 'file-preview-image',]),

            ] : [],
        ],
        'options' => ['accept' => 'image/*'],
    ]); ?>
</div>
<!-- </div> -->
<div>
    <label class="control-label" >Album ảnh</label>
    <?php

    echo \frontend\widgets\dropzone\ImageDropzone::widget(
        [
            'name' => 'file', // input name or 'model' and 'attribute'
            'url' => \yii\helpers\Url::to(['/dropzone/image/upload-product']), // upload url
            'storedFiles' => $requestModel !== null ? $requestModel->getImagesForDropzone(): $model->getImagesForDropzone(), // stores files
            'eventHandlers' => [
                'success' => 'function(file,responseText){ var response =JSON.parse(responseText); var filename =response["filename"];ImageHandler.addButtonRemove(this,file,filename,"imageAsms");}',
                'error' => 'function(file,response){}',
                'addedfile' => 'function(file){ ImageHandler.addButtonRemove(this,file,file["name"],"imageAsms");}'
            ], // dropzone event handlers
            'sortable' => true, // sortable flag
            'sortableOptions' => [], // sortable options
            'htmlOptions' => [], // container html options
            'options' => [

            ], // dropzone js options
        ]
    );

    ?>
    <?= $form->field($model, 'imageAsms')->hiddenInput(['id' => "imageAsms"])->label(false) ?>
</div>
<div>
    <?= $form->field($model,'categoryAsms')->checkboxList( ArrayHelper::map( Category::find()->andWhere(['status'=>Category::STATUS_ACTIVE])->asArray()->all(),'id','display_name'))?>
</div>
<div class="bt-form">
    <?= \yii\helpers\Html::submitButton($model->isNewRecord ? 'Tạo chiến dịch' : 'Cập nhật', ['class' => 'bt-common-1']) ?>

</div>

<?php ActiveForm::end(); ?>


