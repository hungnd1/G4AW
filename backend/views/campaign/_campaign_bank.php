<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 3/19/2016
 * Time: 11:32 PM
 */
use common\models\Bank;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var $model \common\models\Campaign */
/** @var $model_bank \common\models\CampaignBankAsm */
/** @var $campaign_direct_address \yii\data\ActiveDataProvider */
/** @var $campaign_bank_account \yii\data\ActiveDataProvider */
$model_bank = new \common\models\CampaignBankAsm();
$campaign_id = $model->id;

$type_bank_account = \common\models\CampaignBankAsm::TYPE_BANK_ACCOUNT;
$type_direct_address = \common\models\CampaignBankAsm::TYPE_DIRECT_ADDRESS;
$urlUploadImage = \yii\helpers\Url::to(['/site/upload']);


$type = 'type';
$js = <<<JS
    $("#$type").change(function () {
        var type = $('#$type').val();
        if(type == {$type_bank_account}){
            $('#direct-type').hide();
            $('#bank-type').show();
        }else{
            $('#bank-type').hide();
            $('#direct-type').show();
        }
    });
JS;
$js_default = <<<JS
    var type = $('#$type').val();
    if(type == {$type_bank_account}){
        $('#direct-type').hide();
        $('#bank-type').show();
    }else{
        $('#bank-type').hide();
        $('#direct-type').show();
    }
JS;

$this->registerJs($js_default, \yii\web\View::POS_READY);
$this->registerJs($js, \yii\web\View::POS_READY);

?>
<div class="col-md-12 col-sm-12">
    <?php Modal::begin([
        'header' => '<h2>' . Yii::t('app', 'Thêm mới hình thức ủng hộ') . '</h2>',
        'toggleButton' => ['label' => Yii::t('app', 'Thêm mới'), 'class' => 'btn btn-success btn-ajax-modal'],
        'size' => Modal::SIZE_LARGE,
    ]);?>
    <div class="user-invoice-form">

        <?php

        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'action' => ['campaign/add-campaign-bank', 'id' => $model->id],
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]);


        echo $form->field($model_bank, 'type')->dropDownList(\common\models\CampaignBankAsm::getListType(), ['id' => 'type']);

        echo "<div id='direct-type'>";
        //        echo $form->field($model_bank, 'content')->textInput();
        echo $form->field($model_bank, 'content')->widget(\dosamigos\ckeditor\CKEditor::className(), [
            'options' => ['rows' => 4],
            'preset' => 'basic',
            'clientOptions' => [
                'disallowedContent ' => 'img',
            ],

        ]);
//                echo $form->field($model_bank, 'content')->widget(\common\widgets\CKEditor::className(), [
//                    'options' => [
//                        'rows' => 6,
//                    ],
//                    'preset' => 'basic'
//                ]);
        echo "</div>";

        echo "<div id='bank-type'>";
        echo $form->field($model_bank, 'bank_id')->dropDownList(ArrayHelper::map(Bank::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'));
        echo $form->field($model_bank, 'branch')->textInput();
        echo $form->field($model_bank, 'account_number')->textInput();
        echo $form->field($model_bank, 'account_owner')->textInput();
        echo "</div>";

        echo Html::submitButton(Yii::t('app', 'Thêm mới'), ['class' => 'btn btn-success']);
        ActiveForm::end(); ?>

    </div>
    <?php Modal::end(); ?>
</div>
<br>
<br>
<h2>Ủng hộ trực tiếp</h2>
<div>
    <?= GridView::widget([
        'dataProvider' => $campaign_direct_address,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Thông tin',
                'format' => 'html',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignBankAsm */
                    return $model->content;
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) use ($campaign_id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span> Xóa',
                            Url::toRoute(['campaign/delete-campaign-bank', 'id' => $model->id, 'campaign_id' => $campaign_id,]),
                            [
                                'title' => 'Xóa',
                                'class' => 'btn btn-default',
                                'data-confirm' => Yii::t('app', 'Bạn có chắc chắn muốn xóa thông tin này khỏi chiến dịch?'),
                            ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
<h2>Chuyển khoản</h2>
<div>
    <?= GridView::widget([
        'dataProvider' => $campaign_bank_account,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'bank_id',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignBankAsm */
                    return $model->bank ? $model->bank->name : "";
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'branch',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignBankAsm */
                    return $model->branch;
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'account_number',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignBankAsm */
                    return $model->account_number;
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'account_owner',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignBankAsm */
                    return $model->account_owner;
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) use ($campaign_id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span> Xóa',
                            Url::toRoute(['campaign/delete-campaign-bank', 'id' => $model->id, 'campaign_id' => $campaign_id,]),
                            [
                                'title' => 'Xóa',
                                'class' => 'btn btn-default',
                                'data-confirm' => Yii::t('app', 'Bạn có chắc chắn muốn xóa thông tin này khỏi chiến dịch?'),
                            ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
