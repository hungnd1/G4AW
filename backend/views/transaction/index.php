<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?= $this->title ?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">


                <div class="col-md-12 col-sm-12">
                    <br/>
                    <?php $form = \kartik\form\ActiveForm::begin([
                        'id' => 'form-search-transaction',
                        'action' => Url::to(['transaction/index']),
                        'method' => 'GET'
                    ]); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'start_date')->widget(\kartik\widgets\DatePicker::classname(), [
                                'options' => ['placeholder' => 'Ngày bắt đầu'],
                                'type' => \kartik\widgets\DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'end_date')->widget(\kartik\widgets\DatePicker::classname(), [
                                'options' => ['placeholder' => 'Ngày kết thúc'],
                                'type' => \kartik\widgets\DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'trans_type')->dropDownList(\yii\helpers\ArrayHelper::merge(['' => 'Tất cả'], Transaction::getListTransType())) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'channel')->dropDownList(\yii\helpers\ArrayHelper::merge(['' => 'Tất cả'], Transaction::getListChannelTye())) ?>
                        </div>
                    </div>
                    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

                    <?= Html::submitButton('tìm kiếm', ['class' => 'btn btn-success', 'id' => 'submit-search-transaction']) ?>


                    <?php \kartik\form\ActiveForm::end(); ?>
                </div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'title',
                            'label' => 'Yêu cầu',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                return Html::a($model->title, ['view', 'id' => $model->id], ['class' => 'label label-primary']);
                            },
                        ],

                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\DonationRequest::listStatus(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],

                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'type',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return $model->getTypeName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \common\models\DonationRequest::listType(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'current_amount',
                            'label' => 'Số tiền đạt được',
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return \common\helpers\CommonUtils::formatNumber($model->current_amount);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_at',
                            'label' => 'Ngày tạo',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return date("d/m/Y H:i:s", $model->created_at);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_by',
                            'label' => 'Người tạo',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return $model->createdBy ? $model->createdBy->getName() : "";
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'organization_id',
                            'label' => 'Tổ chức',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\DonationRequest */
                                return $model->organization ? $model->organization->getName() : "";
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \yii\helpers\ArrayHelper::map(
                                User::find()->andWhere(['type' => User::TYPE_ORGANIZATION])
                                    ->asArray()->all(), 'id', 'fullname'
                            ),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => "Tất cả"],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
