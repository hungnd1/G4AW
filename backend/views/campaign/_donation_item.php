<?php
/**
 * Created by PhpStorm.
 * User: Hoan
 * Date: 3/19/2016
 * Time: 11:32 PM
 */
use common\models\CampaignDonationItemAsm;
use common\models\DonationItem;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/** @var $model \common\models\Campaign */
/** @var $model_donation \common\models\CampaignDonationItemAsm */
/** @var $donation_item \common\models\CampaignDonationItemAsm[] */
$campaign_id = $model->id;
?>
<div class="col-md-12 col-sm-12">
    <br/>
    <?php Modal::begin([
        'header' => '<h2>' . Yii::t('app', 'Thêm mới danh mục ủng hộ') . '</h2>',
        'toggleButton' => ['label' => Yii::t('app', 'Thêm mới'), 'class' => 'btn btn-success btn-ajax-modal'],
        'size' => Modal::SIZE_LARGE,
    ]);?>
    <div class="user-invoice-form">

        <?php
        $model_donation = new \common\models\CampaignDonationItemAsm();

        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'action' => ['campaign/add-donation-item', 'id' => $model->id],
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]);

        /** @var CampaignDonationItemAsm[] $donation_item_asm */
        $donation_item_asm = CampaignDonationItemAsm::find()->andWhere(['campaign_id' => $model->id])->all();
        $arr_item = [];
        foreach ($donation_item_asm as $item) {
            $arr_item[] = $item->donation_item_id;
        }

        /** @var DonationItem[] $items */
        if ($arr_item) {
            $items = DonationItem::find()->andFilterWhere(['not in', 'id', $arr_item])->all();
        } else {
            $items = DonationItem::find()->all();
        }

        $arr = [];
        foreach ($items as $item) {
            $arr[$item->id] = $item->name . "(" . $item->unit . ")";
        }
        echo $form->field($model_donation, 'donation_item_id')->dropDownList($arr);
        echo $form->field($model_donation, 'expected_number')->textInput();

        echo Html::submitButton(Yii::t('app', 'Thêm mới'), ['class' => 'btn btn-success']);
        ActiveForm::end(); ?>

    </div>
    <?php Modal::end(); ?>
</div>
<div>
    <?= GridView::widget([
        'dataProvider' => $donation_item,
//        'pjax' => true,
        'columns' => [
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Tên',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignDonationItemAsm */
                    return $model->donationItem ? $model->donationItem->name : "";
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Đơn vị',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignDonationItemAsm */
                    return $model->donationItem ? $model->donationItem->unit : "";
                },
            ],

            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'expected_number',
                'refreshGrid' => true,
                'label' => 'Số lượng',
                'editableOptions' => function ($model, $key, $index) {
                    return [
                        'header' => 'số lượng',
                        'size' => 'md',
                        'displayValueConfig' => $model->expected_number,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
//                        'placement' => \kartik\popover\PopoverX::ALIGN_R
                    ];
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Đã ủng hộ',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignDonationItemAsm */
                    return $model->current_number;
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Cần thêm',
                'value' => function ($model, $key, $index, $widget) {
                    /** @var $model \common\models\CampaignDonationItemAsm */
                    return $model->expected_number - $model->current_number;
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) use ($campaign_id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span> Xóa',
                            Url::toRoute(['campaign/delete-donation', 'id' => $model->id, 'campaign_id' => $campaign_id,]),
                            [
                                'title' => 'Xóa',
                                'class' => 'btn btn-default',
                                'data-confirm' => Yii::t('app', 'Bạn có chắc chắn muốn xóa danh mục ủng hộ này khỏi chiến dịch?'),
                            ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
