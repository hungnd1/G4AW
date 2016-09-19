<?php

use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\DonationRequest;

/* @var $this yii\web\View */
/* @var $model common\models\DonationRequest */
/* @var $gallery common\models\RequestGallery[] */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách yêu cầu', 'url' => ['index']];
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
                <?php if ($model->status == DonationRequest::STATUS_NEW) { ?>
                    <div class="group">
                        <?= Html::a('Tạo chiến dịch', ['campaign/create', 'donation_request_id' => $model->id],
                            ['class' => 'btn btn-success']) ?>

                        <?php Modal::begin([
                            'header' => '<h2>' . Yii::t('app', 'Từ chối yêu cầu') . '</h2>',
                            'toggleButton' => ['label' => Yii::t('app', 'Từ chối yêu cầu'), 'class' => 'btn btn-danger btn-ajax-modal'],
                            'size' => Modal::SIZE_LARGE,
                        ]);?>
                        <div class="user-invoice-form">

                            <?php

                            $form = ActiveForm::begin([
                                'type' => ActiveForm::TYPE_HORIZONTAL,
                                'action' => ['donation-request/reject', 'id' => $model->id],
                                'enableAjaxValidation' => false,
                                'enableClientValidation' => true,
                            ]);

                            echo $form->field($model, 'admin_note')->textarea();

                            echo Html::submitButton(Yii::t('app', 'Từ chối'), ['class' => 'btn btn-success']);
                            ActiveForm::end(); ?>

                        </div>
                        <?php Modal::end(); ?>
                    </div>
                <?php } ?>
                <br/>

                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="<?= ($active == 1) ? 'active' : '' ?>">
                            <a href="#tab1" data-toggle="tab">
                                Thông tin chung</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="tab1">
                            <?= DetailView::widget([
                                'model' => $model,
                                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                                'attributes' => [
                                    [
                                        'attribute' => 'title',
                                        'label' => 'Tên yêu cầu',
                                        'value' => $model->title,
                                    ],
                                    [
                                        'attribute' => 'village_id',
                                        'value' => $model->village ? $model->village->name : "",
                                    ],
                                    [
                                        'attribute' => 'organization_id',
                                        'value' => $model->organization_id,
                                    ],
                                    [
                                        'attribute' => 'type',
                                        'value' => $model->getTypeName(),
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Trạng thái',
                                        'format' => 'raw',
                                        'value' => ($model->status == DonationRequest::STATUS_APPROVED) ?
                                            '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                                            '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                                    ],
                                    [
                                        'attribute' => 'expected_amount',
                                        'value' => \common\helpers\CommonUtils::formatNumber($model->current_amount),
                                    ],
                                    'short_description',
                                    'content:html',
                                    'admin_note',
//                            'donation_status',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

