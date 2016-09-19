<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 3/19/2016
 * Time: 11:32 PM
 */
use common\models\CampaignDonationItemAsm;
use common\models\Transaction;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\widgets\DateTimePicker;
use yii\bootstrap\Modal;

/** @var $model \common\models\Campaign */
/** @var $transactions */

?>
<div class="col-md-12 col-sm-12">
    <br/>
    <?php Modal::begin([
        'header' => '<h2>' . Yii::t('app', 'Thêm mới danh sách ủng hộ') . '</h2>',
        'toggleButton' => ['label' => Yii::t('app', 'Thêm mới'), 'class' => 'btn btn-success btn-ajax-modal'],
        'size' => Modal::SIZE_LARGE,
    ]);?>
    <div class="user-invoice-form">

        <?php
        $model_transaction = new \backend\models\TransactionForm();
        $model->setScenario('create');
        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'action' => ['campaign/add-transaction', 'id' => $model->id],
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]);?>

        <?=
        $form->field($model_transaction, 'donation_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Chọn thời gian ...'],
            'type' => DateTimePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy hh:ii:ss',
            ]
        ]);
        ?>

        <?= $form->field($model_transaction, 'username')->textInput() ?>
        <?= $form->field($model_transaction, 'address')->textInput() ?>
        <?php
        /** @var CampaignDonationItemAsm[] $campaign_item_asm */
        $campaign_item_asm = CampaignDonationItemAsm::find()->andWhere(['campaign_id' => $model->id])->all();
        foreach ($campaign_item_asm as $item) {
            echo Transaction::getFormDonationItem($item->donationItem);
        }
        ?>

        <?php
        echo Html::submitButton(Yii::t('app', 'Thêm mới'), ['class' => 'btn btn-success']);
        ActiveForm::end(); ?>

    </div>
    <?php Modal::end(); ?>
</div>
<div>
    <?= GridView::widget([
        'dataProvider' => $transactions,
        'columns' => [
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Thời gian',
                'format' => 'html',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\Transaction */
                    return date("d/m/Y H:i:s", $model->transaction_time);
                },
            ],

            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Cá nhân/Đơn vị ủng hộ',
                'format' => 'html',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\Transaction */
                    return $model->user ? $model->user->getName() : $model->username;
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Trạng thái',
                'format' => 'html',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\Transaction */
                    return $model->getStatusName();
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Thông tin ủng hộ',
                'format' => 'html',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\Transaction */
                    return $model->getTransactionDetail();
                },
            ],


        ],
    ]); ?>
</div>
