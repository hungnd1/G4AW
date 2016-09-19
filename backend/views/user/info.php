<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-gift"></i><?= $this->title ?></div>
            </div>
            <div class="portlet-body form">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        ['attribute'=>'username', 'format'=>'raw', 'value'=>'<kbd>'.$model->username.'</kbd>', 'displayOnly'=>true],
                        'email:email',
                        [
                            'label' => 'Quyền người dùng',
                            'format' => 'html',
                            'value' =>  $model->getRolesName(),
                        ],
//                        'role',
                        [
                            'attribute'=>'status',
                            'label'=>'Trạng thái',
                            'format'=>'raw',
                            'value'=>($model->status ==User::STATUS_ACTIVE)  ?
                                '<span class="label label-success">'.$model->getStatusName().'</span>' :
                                '<span class="label label-danger">'.$model->getStatusName().'</span>',
                            'type'=>DetailView::INPUT_SWITCH,
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'onText' => 'Active',
                                    'offText' => 'Delete',
                                ]
                            ]
                        ],
                        [                      // the owner name of the model
                            'attribute'=>'created_at',
                            'label' => 'Ngày tham gia',
                            'value' => date('d/m/Y H:i:s',$model->created_at),
                        ],
                        [                      // the owner name of the model
                            'attribute'=>'updated_at',
                            'label' => 'Ngày thay đổi thông tin',
                            'value' => date('d/m/Y H:i:s',$model->updated_at),
                        ],

//                        'type',
//                        'service_provider_id',
//                        'content_provider_id',
//                        'parent_id',
                    ],
                ]) ?>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <?php
                            Modal::begin([
                                'header' => '<h4>Cập nhật thông tin</h4>',
                                'toggleButton' => ['label' => 'Cập nhật thông tin', 'class' => 'btn btn-success'],
                                'closeButton' => ['label' => 'Cancel']
                            ]);
                            echo $this->render('_form_owner', ['model' => $model]);
                            Modal::end();
                            ?>
                            <?php
                            Modal::begin([
                                'header' => '<h4>Đổi mật khẩu</h4>',
                                'toggleButton' => ['label' => 'Đổi mật khẩu', 'class' => 'btn btn-success'],
                                'closeButton' => ['label' => 'Cancel']
                            ]);
                            echo $this->render('_form_owner_change_password', ['model' => $model]);
                            Modal::end();
                            ?>
                            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

