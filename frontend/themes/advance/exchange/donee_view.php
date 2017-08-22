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

            </div>

            <div class="block-hold">
                <a href="" class="avt">
                    <?=Html::img($model->createdBy->getAvatar())?>

                    <h2 class="us-name"><?= Html::a($model->createdBy->fullname)?></h2>
                    <span class="us-add"><?=$model->createdBy->address?></span>
            </div>

        </div>
    </div>
</div>
<!-- end common page-->