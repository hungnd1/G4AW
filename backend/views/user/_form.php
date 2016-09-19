<?php

use common\models\LeadDonor;
use common\models\Village;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\models\User;
use yii\helpers\Url;
use backend\models\LeadDonorForm;
use backend\models\VillageForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
//    'method' => 'post',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 12,
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'showLabels' => true,
        'labelSpan' => 2,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]);
$formId = $form->id;
?>
<div class="form-body">
    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Tài khoản', 'maxlength' => 20]) ?>
        <?= $form->field($model, 'fullname')->textInput(['placeholder' => 'Tên', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'phone_number')->textInput(['placeholder' => 'Số điện thoại', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'address')->textInput(['placeholder' => 'Địa chỉ', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Nhập mật khẩu có độ dài  tối thiểu 6 kí tự']) ?>
        <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => 'Nhập lại mật khẩu']) ?>
        <?php if ($model->type == User::TYPE_LEAD_DONOR) { ?>
            <?=
            $form->field($model, 'lead_donor_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(LeadDonor::find()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Chọn doanh nghiệp'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Doanh nghiệp đỡ đầu (*)');
            ?>
        <?php
        }
        if ($model->type == User::TYPE_VILLAGE) {
            ?>

            <?=
            $form->field($model, 'village_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Village::findAll(['status' => Village::STATUS_ACTIVE]), 'id', 'name'),
                'options' => ['placeholder' => 'Chọn Xã'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Tên xã (*)');
            ?>
        <?php } ?>

    <?php } else { ?>
        <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
        <?= $form->field($model, 'fullname')->textInput(['placeholder' => 'Họ và tên', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'phone_number')->textInput(['placeholder' => 'Số điện thoại', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'address')->textInput(['placeholder' => 'Địa chỉ', 'maxlength' => 100]) ?>
        <!--        Nếu là chính nó thì không cho thay đổi trạng thái-->
        <?php if ($model->id != Yii::$app->user->getId()) { ?>
            <?= $form->field($model, 'status')->dropDownList(User::listStatus()) ?>
        <?php } ?>

        <?php if ($model->type == User::TYPE_LEAD_DONOR) { ?>
            <?=
            $form->field($model, 'lead_donor_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(LeadDonor::find()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Chọn doanh nghiệp'],
                'id' => 'lead_donor_id',
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Doanh nghiệp đỡ đầu (*)');
            ?>
        <?php
        }
        if ($model->type == User::TYPE_VILLAGE) {
            ?>

            <?=
            $form->field($model, 'village_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Village::findAll(['status' => Village::STATUS_ACTIVE]), 'id', 'name'),
                'options' => ['placeholder' => 'Chọn Xã'],
                'id' => 'lead_donor_id',
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Tên xã (*)');
            ?>
        <?php } ?>
    <?php } ?>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Huỷ', ['index', 'type' => $model->type], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>


