<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 13/03/2016
 * Time: 1:45 PM
 */
use \yii\helpers\Html;
use common\models\DonationRequest;
/** @var $model \common\models\DonationRequest */
$statusClass='';
$statusText='';
$listStatus=DonationRequest::listStatus();
$statusText=$listStatus[$model->status];
switch($model->status){
    case DonationRequest::STATUS_NEW:
        $statusClass ='stt-tt tt-2';
        break;
    case DonationRequest::STATUS_REJECTED:
        $statusClass ='stt-tt tt-3';
        break;
    case DonationRequest::STATUS_APPROVED:
        $statusClass ='stt-tt tt-1';
        break;
    case DonationRequest::STATUS_ACTIVE:
        $statusClass ='stt-tt tt-1';
        break;
}

?>
<div class="re-1">
    <div class="thumb-common">

        <?=Html::img('@web/img/blank.gif' )?>
        <?=Html::a(Html::img($model->getThumbnailLink(),['class'=>'thumb-cm']),['/donation-request/view','id'=>$model->id])?>
    </div>
    <div class="inf-right">
        <h4><?= $model->title?></h4>
        <p><?=$model->short_description?></p>
    </div>

        <?php
            switch($model->status){
                case DonationRequest::STATUS_NEW:
                    echo '<div class="action-bt">';
                    echo Html::a('<i class="fa fa-pencil-square-o"></i>Sửa',['/donation-request/update','id'=>$model->id],['class'=>'bt-common-3']);
                    echo Html::a('<i class="fa fa-trash-o"></i>Xóa',['/donation-request/delete','id'=>$model->id],['class'=>'bt-common-3','data-method'=>'POST']);
                    echo '<span class="stt-tt tt-2">Đang chờ</span>';
                    echo '</div>';
                    break;
                case DonationRequest::STATUS_APPROVED:
                    echo '<div class="action-bt disable-bt">';
                    echo Html::a('<i class="fa fa-pencil-square-o"></i>Sửa',['/donation-request/update','id'=>$model->id],['class'=>'bt-common-3']);
                    echo Html::a('<i class="fa fa-trash-o"></i>Xóa',['/donation-request/delete','id'=>$model->id],['class'=>'bt-common-3','data-method'=>'POST']);
                    echo '<span class="stt-tt tt-1">Đã duyệt</span>';
                    echo '</div>';
                    break;
                case DonationRequest::STATUS_ACTIVE:
                    echo '<div class="action-bt disable-bt">';
                    echo Html::a('<i class="fa fa-pencil-square-o"></i>Sửa',['/donation-request/update','id'=>$model->id],['class'=>'bt-common-3']);
                    echo Html::a('<i class="fa fa-trash-o"></i>Xóa',['/donation-request/delete','id'=>$model->id],['class'=>'bt-common-3','data-method'=>'POST']);
                    echo '<span class="stt-tt tt-4"><i class="fa fa-check"></i>Đã tạo chiến dịch</span>';
                    echo '</div>';
                    break;
                case DonationRequest::STATUS_REJECTED:
                    echo '<div class="action-bt">';
                    echo Html::a('<i class="fa fa-pencil-square-o"></i>Sửa',['/donation-request/update','id'=>$model->id],['class'=>'bt-common-3']);
                    echo Html::a('<i class="fa fa-trash-o"></i>Xóa',['/donation-request/delete','id'=>$model->id],['class'=>'bt-common-3','data-method'=>'POST']);
                    echo '<span class="stt-tt tt-3">Bị từ chối</span>';
                    echo '</div>';
                    break;
            }
        ?>


</div>
