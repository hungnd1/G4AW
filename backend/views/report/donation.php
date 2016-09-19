<?php

use common\helpers\CommonUtils;
use common\models\Campaign;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $report \backend\models\ReportDonationForm */
/* @var $subscriber_provider_id int */
/* @var $this yii\web\View */

$this->title = 'Thống kê ủng hộ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">

                <div class="report-user-daily-index">

                    <div class="row form-group">
                        <div class="col-md-8 col-md-offset-2">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['report/donation']),
                                'method' => 'GET'
                            ]); ?>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($report, 'from_date')->widget(\kartik\widgets\DatePicker::classname(), [
                                                'options' => ['placeholder' => 'Ngày bắt đầu'],
                                                'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'dd/mm/yyyy'
                                                ]
                                            ]); ?>

                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($report, 'to_date')->widget(\kartik\widgets\DatePicker::classname(), [
                                                'options' => ['placeholder' => 'Ngày kết thúc'],
                                                'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'dd/mm/yyyy'
                                                ]
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($report, 'organization_id')->dropDownList(
                                                ArrayHelper::map(\common\models\User::find()
                                                    ->andWhere(['type' => \common\models\User::TYPE_ORGANIZATION])
                                                    ->asArray()->all(), "id", 'username'),
                                                ['prompt'=>'Chọn tổ chức cầu nối']); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($report, 'campaign_id')->widget(DepDrop::classname(), [
                                                'data' => ArrayHelper::merge([0 => 'Chọn chiến dịch...'], ArrayHelper::map(Campaign::getCampaignByCreatedBy($report->organization_id), 'id', 'name')),
                                                'pluginOptions' => [
                                                    'depends' => [Html::getInputId($report, 'organization_id')],
                                                    'placeholder' => 'Chọn chiến dịch...',
                                                    'url' => \yii\helpers\Url::to(['/report/list-campaign'])
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <?= \yii\helpers\Html::submitButton('Thống kê', ['class' => 'btn btn-primary']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                    <?php if ($report->dataProvider) { ?>
                        <?= GridView::widget([
                            'dataProvider' => $report->dataProvider,
                            'responsive' => true,
                            'pjax' => true,
                            'hover' => true,
                            'showPageSummary' => true,
                            'columns' => [
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'report_date',
                                    'label' => 'Ngày báo cáo',
                                    'width' => '150px',
                                    'value' => function ($model) {
                                        /**  @var $model \common\models\ReportDonation */
                                        return DateTime::createFromFormat("Y-m-d H:i:s", $model->report_date)->format('d-m-Y');
                                    },
                                    'pageSummary' => "Tổng số"
                                ],

                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'label' => 'Luợt ủng hộ',
                                    'value' => function ($model) {
                                        /**  @var $model \common\models\ReportDonation */
                                        return CommonUtils::formatNumber($model->donate_count);
                                    },
                                    'pageSummary' => $report->content->sum('donate_count') ? CommonUtils::formatNumber($report->content->sum('donate_count')) : 0
                                ],
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'label' => 'Tổng số tiền',
                                    'value' => function ($model) {
                                        /**  @var $model \common\models\ReportDonation */
                                        return CommonUtils::formatNumber($model->revenues);
                                    },
                                    'pageSummary' => $report->content->sum('revenues') ? CommonUtils::formatNumber($report->content->sum('revenues')) : 0
                                ],
                            ],
                            'panel' => [
                                'type' => GridView::TYPE_ACTIVE,
                            ],
                            'toolbar' => [

                                '{export}',
                            ],
                            'export' => [
                                'fontAwesome' => true,
                                'showConfirmAlert' => false,
                                'target' => GridView::TARGET_BLANK,

                            ],
                            'exportConfig' => [
                                GridView::EXCEL => ['label' => 'Excel'],
                            ],

                        ]); ?>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>