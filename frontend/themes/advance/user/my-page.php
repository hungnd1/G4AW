<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 10/8/2016
 * Time: 9:46 AM
 */
use common\models\Campaign;
use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\User;
use frontend\helpers\UserHelper;
use kartik\alert\Alert;
use yii\helpers\Url;

?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('Trang chủ','Home') ?></a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>"><?= UserHelper::multilanguage('Cá nhân','Personal') ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <a href="<?= Url::toRoute(['user/update','id'=>$model->id])?>" class="bt-edit"><i class="fa fa-pencil"></i></a>
                    <?php $image = $model->getImageLink() ? $model->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png'; ?>
                    <img src="<?= $image ?>"><br>
                    <h4><?= $model->full_name ?></h4>
                    <p><?= $model->address ?></p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Số điện thoại','Phone number') ?></span><br>
                    <span class="b-span"><?= $model->username ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span"><?= $model->email ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Giới tính','Gender') ?></span><br>
                    <span class="b-span">
                        <?= \common\models\Subscriber::getGenderName($model->sex)?>
                    </span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?=  UserHelper::multilanguage('Tuổi','Old') ?></span><br>
                    <span class="b-span"><?= User::getOld($model->birthday) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
