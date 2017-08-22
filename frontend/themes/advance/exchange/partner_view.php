<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:28 AM
 */
use yii\helpers\Html;
/** @var $model \common\models\DonationRequest */
/** @var $currentUser \common\models\User */
/** @var $this \yii\web\View */
$approveText ='Duyệt';
$approveClass='';
if($model->status == \common\models\DonationRequest::STATUS_ACTIVE){
    $approveText ='<i class="fa fa-check"></i> Đã duyệt';
    $approveClass='atv-stt';
}
?>

<!-- common page-->
<div class="content-2">
    <div class="container">
        <div class="left-content">
            <h1><?=$model->title?></h1>

            <p class="des-dt"><?=$model->short_description?></p>
            <div class="content-dt">
               <?= $model->content?>
            </div>
        </div>
        <div class="right-content">
            <div class="block-act block-act-2">

                <?=Html::a( $approveText ,'#',['class'=>'bt-common-2 bt-ok btn-approve '.$approveClass,'data-id'=>$model->id]) ?>

                <?= Html::a('<i class="fa fa-plus-circle"></i> Tạo chiến dịch',['/campaign/create','request_id'=>$model->id ],['class'=>"bt-common-2 bt-creat"])?>

            </div>

            <div class="block-hold">
                <a href="" class="avt">
                    <?=Html::img($model->createdBy->getAvatar())?>

                    <h2 class="us-name"><?= Html::a($model->createdBy->fullname)?></h2>
                    <span class="us-add"><?=$model->createdBy->address?></span>
            </div>
            <div class="block-related">
                <h3>Các yêu cầu hỗ trợ khác</h3>
                <?php
                    $otherRequests = $currentUser->getDonationRequestsTo()->limit(10)->all();
                    /** @var \common\models\DonationRequest $item */
                foreach($otherRequests as $item):
                    $thumbnailLink =$item->getThumbnailLink();
                ?>
                        <div class="l-related">
                            <div class="thumb-common">
                                <?=Html::img('@web/img/blank.gif',['class'=>'blank-img'])?>
                                <?= $thumbnailLink!='' ? Html::a(Html::img($thumbnailLink,['class'=>'thumb-cm']),['/donation-request/view','id'=>$item->id]):''?>
                            </div>
                            <h4><?= Html::a($item->short_description,['/donation-request/view','id'=>$item->id])?></h4>
                        </div>
                <?php endforeach;?>

            </div>
        </div>
    </div>
</div>
<!-- end common page-->