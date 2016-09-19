<?php

use common\models\Campaign;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use common\models\Village;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LeadDonor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Doanh nghiệp của tôi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$id = $_GET['id'];
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
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="<?= ($active == 1) ? 'active' : '' ?>">
                            <a href="#tab1" data-toggle="tab">
                                Thông tin chung</a>
                        </li>
                        <li class=" <?= ($active == 2) ? 'active' : '' ?>">
                            <a href="#tab2" data-toggle="tab">
                                Danh sách xã bảo trợ</a>
                        </li>
                        <li class=" <?= ($active == 3) ? 'active' : '' ?>">
                            <a href="#tab3" data-toggle="tab">
                                Danh sách chiến dịch</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="tab1">
                            <p>
                                <?= Html::a(Yii::t('app', 'cập nhật'), ['update_my_lead_donor', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            </p>

                            <?= DetailView::widget([
                                'model' => $model,
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                                'attributes' =>[
                                    [
                                        'attribute' => 'image',
                                        'format' => 'html',
                                        'value' => Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@lead_donor_image') . "/" .$model->image, ['width' => '300px']),
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'value' => $model->name,
                                    ],
                                    [
                                        'attribute' => 'address',
                                        'value' => $model->address,
                                    ],
                                    [
                                        'attribute' => 'website',
                                        'value' => $model->website,
                                    ],
                                    [
                                        'attribute' => 'phone',
                                        'value' => $model->phone,
                                    ],
                                    'email:email',
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Trạng thái',
                                        'format' => 'raw',
                                        'value' => ($model->status == \common\models\Campaign::STATUS_ACTIVE) ?
                                            '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                                            '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                                    ],
                                    'description:ntext',
                                    [
                                        'attribute' => 'created_at',
                                        'value' => $model->created_at,
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'value' => $model->updated_at,
                                    ],
                                ],
                            ]) ?>
                        </div>

                        <div class="tab-pane <?= ($active == 2) ? 'active' : '' ?>" id="tab2">

                            <?php
                            $village = new \backend\models\VillageForm();
                            $form = ActiveForm::begin([
                                'type' => ActiveForm::TYPE_HORIZONTAL,
                                'fullSpan' => 8,
                                'method' => 'post',
                                'action' => 'add_village?id='.$id
                            ])
                            ?>

                            <?=
                            $form->field($village,'village_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Village::find()->where('lead_donor_id !='.$id)->all(), 'id', 'name'),
                                'options' => ['placeholder' => 'Chọn Xã'],
                                'id' => 'lead_donor_id',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <div class="row text-center">
                                <input type="submit" value="Thêm xã" class="btn btn-success">
                                <br>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                                'columns' => [
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'image',
                                        'format' => 'html',
                                        'value' => function ($model1, $key, $index, $widget) {
                                            return Html::img(Yii::getAlias('@web') . "/" . Yii::getAlias('@village_image') . "/" .$model1->image, ['width' => '150px']);
                                        },
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'name',
                                        'format' => 'html',
                                        'value' => function ($model1, $key, $index, $widget) {
                                            return Html::a($model1->name, ['village/view', 'id' => $model1->id], ['class' => 'label label-primary']);
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template'=>'{delete}',
                                        'buttons'=>[
                                            'delete' => function ($url,$model1) {
                                                $id = $_GET['id'];
                                                return Html::a('<span class="glyphicon glyphicon-trash"></span> Bỏ xã', Url::toRoute(['lead-donor/trash_village','id'=>$id,'id_vi'=>$model1->id]), [
                                                    'title' => 'Bỏ xã',
                                                    'data-confirm' => "Bạn có chắc chắn muốn bỏ xã này khỏi danh sách được bảo trợ?",
                                                    'data-method' => 'post',
                                                ]);
                                            },
                                        ],
                                    ],
                                ],
                            ])
                            ?>


                        </div>
                        <div class="tab-pane <?= ($active == 3) ? 'active' : '' ?>" id="tab3">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider1,
                                'filterModel' => $searchModel1,
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                                'columns' => [
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'campaign_code',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            return $modelcam->campaign_code;
                                        },
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'name',
                                        'format' => 'html',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            return Html::a($modelcam->name, ['campaign/view', 'id' => $modelcam->id], ['class' => 'label label-primary']);
                                        },
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            if ($modelcam->status == Campaign::STATUS_ACTIVE) {
                                                return '<span class="label label-success">' . $modelcam->getStatusName() . '</span>';
                                            } else {
                                                return '<span class="label label-danger">' . $modelcam->getStatusName() . '</span>';
                                            }

                                        },
                                        'filter' => Campaign::listStatus(),
                                        'filterType' => GridView::FILTER_SELECT2,
                                        'filterWidgetOptions' => [
                                            'pluginOptions' => ['allowClear' => true],
                                        ],
                                        'filterInputOptions' => ['placeholder' => "Tất cả"],
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'header' => Yii::t('app', 'Thuộc xã'),
                                        'attribute' => 'village_id',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            return $modelcam->village?$modelcam->village->name:'';
                                        },
                                    ],
                                    [
                                        'class' => '\kartik\grid\DataColumn',
                                        'header' => Yii::t('app', 'Tỉ lệ đóng góp'),
                                        'format' => 'html',
                                        'value' => function ($modelcam, $key, $index, $widget) {
                                            /** @var $model Campaign */
                                            return $modelcam->getRateDonation();
                                        },
                                    ],
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>