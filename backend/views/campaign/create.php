<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Campaign */
/* @var $donation_request_id  */

$this->title = 'Thêm mới chiến dịch';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý chiến dịch', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-gift"></i><?=$this->title?></div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                    'donation_request'=>$donation_request,
                ]) ?>
            </div>
        </div>
    </div>
</div>
