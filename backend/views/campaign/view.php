<?php

use common\models\Campaign;
use kartik\detail\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Campaign */
/* @var $donation_item common\models\CampaignDonationItemAsm[] */
/* @var $gallery common\models\CampaignGallery[] */
/* @var $campaign_direct_address \yii\data\ActiveDataProvider */
/* @var $transactions \yii\data\ActiveDataProvider */
/* @var $campaign_bank_account \yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quản lý chiến dịch', 'url' => ['index']];
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
                <div class="group">

                    <?php
                    if ($model->status == Campaign::STATUS_NEW) {
                        echo Html::a('Bắt đầu', ['update-status', 'type' => Campaign::STATUS_ACTIVE, 'id' => $model->id],
                            ['class' => 'btn btn-success']);
                    }
                    ?>

                    <?php
                    if ($model->status == Campaign::STATUS_ACTIVE) {
                        echo Html::a('Tạm dừng', ['update-status', 'type' => Campaign::STATUS_INACTIVE, 'id' => $model->id],
                            ['class' => 'btn btn-danger']);
                    }
                    ?>
                    <?php
                    if ($model->status == Campaign::STATUS_INACTIVE) {
                        echo Html::a('Tiếp tục', ['update-status', 'type' => Campaign::STATUS_ACTIVE, 'id' => $model->id],
                            ['class' => 'btn btn-success']);
                    }
                    ?>

                    <?php
                    echo Html::a('Kết thúc', ['update-status', 'type' => Campaign::STATUS_DONE, 'id' => $model->id],
                        ['class' => 'btn btn-warning']);
                    ?>
                    <?php
                     if($model->status != Campaign::STATUS_ACTIVE) {
                         ?>
                         <?= Html::a('Xóa', ['update-status', 'type' => Campaign::STATUS_DELETED, 'id' => $model->id],
                             ['class' => 'btn btn-danger']) ?>
                         <?php
                     }
                    ?>
                </div>
                <br/>

                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="<?= ($active == 1) ? 'active' : '' ?>">
                            <a href="#tab1" data-toggle="tab">
                                Thông tin chung</a>
                        </li>
                        <li class=" <?= ($active == 2) ? 'active' : '' ?>">
                            <a href="#tab2" data-toggle="tab">
                                Danh mục cần ủng hộ</a>
                        </li>
                        <li class=" <?= ($active == 3) ? 'active' : '' ?>">
                            <a href="#tab3" data-toggle="tab">
                                Lịch sử ủng hộ</a>
                        </li>
                        <li class=" <?= ($active == 4) ? 'active' : '' ?>">
                            <a href="#tab4" data-toggle="tab">
                                Hình thức ủng hộ</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="tab1">
                            <?= Html::a('Cập nhật', ['update', 'id' => $model->id],
                                ['class' => 'btn btn-success']) ?><br>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'campaign_code',
                                        'style' => 'width: 20%',
                                        'label' => 'Mã chiến dịch',
                                        'value' => $model->campaign_code,
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'label' => 'Tên chiến dịch',
                                        'value' => $model->name,
                                    ],
                                    [
                                        'attribute' => 'started_at',
                                        'value' => date('d/m/Y H:i:s', $model->started_at),
                                    ],
                                    [
                                        'attribute' => 'finished_at',
                                        'value' => date('d/m/Y H:i:s', $model->finished_at),
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Trạng thái',
                                        'format' => 'raw',
                                        'value' => ($model->status == \common\models\Campaign::STATUS_ACTIVE) ?
                                            '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                                            '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                                    ],

                                    [
                                        'attribute' => 'village_id',
                                        'value' => $model->village ? $model->village->name : "",
                                    ],
                                    'short_description',
                                    'content:html',

                                    [
                                        'label' => 'Tỉ lệ đóng góp',
                                        'value' => $model->getRateDonation(),
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'value' => date('d/m/Y H:i:s', $model->created_at),
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'value' => date('d/m/Y H:i:s', $model->updated_at),
                                    ],
                                ],
                            ]) ?>
                        </div>

                        <div class="tab-pane <?= ($active == 2) ? 'active' : '' ?>" id="tab2">
                            <?= $this->render('_donation_item', ['model' => $model, 'donation_item' => $donation_item]) ?>
                        </div>
                        <div class="tab-pane <?= ($active == 3) ? 'active' : '' ?>" id="tab3">
                            <?= $this->render('transaction', ['model' => $model, 'transactions' => $transactions]) ?>
                        </div>
                        <div class="tab-pane <?= ($active == 4) ? 'active' : '' ?>" id="tab4">
                            <?= $this->render('_campaign_bank', ['model' => $model,
                                'campaign_direct_address' => $campaign_direct_address,
                                'campaign_bank_account' => $campaign_bank_account,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
