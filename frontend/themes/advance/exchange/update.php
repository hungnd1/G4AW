<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:13 PM
 */
use yii\bootstrap\ActiveForm;

/** @var $model \common\models\DonationRequest */
$urlUploadImage = \yii\helpers\Url::to(['/app/upload']);
?>
<!-- common page-->
<div class="content-common">

    <h2 class="title-cm">CẬP NHẬT YÊU CẦU </h2>
    <?=$this->render('_form',['model'=>$model])?>
</div>
<!-- end common page-->
