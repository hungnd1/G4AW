<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 13/03/2016
 * Time: 1:45 PM
 */
use \yii\helpers\Html;
use \common\models\DonationRequest;
/** @var $model \common\models\DonationRequest */
$approveText ='Duyệt';
$approveClass='';
if($model->status == \common\models\DonationRequest::STATUS_ACTIVE){
    $approveText ='<i class="fa fa-check"></i> Đã duyệt';
    $approveClass='atv-stt';
}
?>
<div class="re-1">
    <div class="thumb-common">
        <?=Html::img('@web/img/blank.gif')?>
        <?=Html::a(Html::img($model->getThumbnailLink(),['class'=>'thumb-cm']),['/donation-request/view','id'=>$model->id])?>
    </div>
    <div class="inf-right">

        <h4><?= Html::a($model->title,['/donation-request/view','id'=>$model->id])?> </h4>
        <p><?=$model->short_description?></p>
    </div>
    <div class="action-bt">
        <?php
            if($model->status ==  DonationRequest::STATUS_NEW){
                echo Html::a('Duyệt','#',['class'=>'approval bt-common-1 btn-approve ' ,'data-id'=>$model->id]);
                echo Html::a('Từ chối','#',['class'=>'approval bt-common-1 btn-reject ' ,'data-id'=>$model->id]);
            }else if($model->status == DonationRequest::STATUS_APPROVED){
                echo Html::a('<i class="fa fa-check"></i> Đã duyệt','#',['class'=>'approval bt-common-1 atv-stt' ,'data-id'=>$model->id]);
            }
        ?>

        <?= Html::a('Tạo chiến dịch',['/campaign/create','request_id'=>$model->id ],['class'=>"creat-cp bt-common-1"])?>
    </div>
</div>
