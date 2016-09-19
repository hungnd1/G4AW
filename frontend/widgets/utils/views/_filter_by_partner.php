<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 18/03/2016
 * Time: 8:46 AM
 */
/** @var $model \frontend\models\FilterCampaignForm */
use \yii\bootstrap\ActiveForm;
$partnersData = \yii\helpers\ArrayHelper::map(\common\models\User::find()
    ->andWhere(['type'=>\common\models\User::TYPE_ORGANIZATION])
    ->andWhere(['status'=>\common\models\User::STATUS_ACTIVE])
    ->asArray()->all(),'id','fullname');
?>
<div class="filter-col-1">
    <h4>Đơn vị tổ chức</h4>
    <?php $form = ActiveForm::begin(['id' => 'filter-campaign-by-category-form']); ?>

    <?= $form->field($model, 'partners')->checkboxList($partnersData,['id'=>'filter-partner-id'])->label(false) ?>

    <?php ActiveForm::end(); ?>
</div>
