<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 09-Aug-16
 * Time: 2:07 PM
 */
use yii\helpers\Url;
/** @var $village \common\models\Village */
/** @var $model \common\models\Campaign */
?>

<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['village/view','id_village'=>$village->id]) ?>"><?= $village->name ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="top-ct-1">
                <div class="left-top-ct">
                    <div class="thumb-common">
                        <img src="../img/blank.gif">
                        <a href=""><img class="thumb-cm" src="<?= $model->getCampaignImage() ?>"><br></a>
                    </div>
                </div>
                <div class="i-f-right">
                    <h1><?= $model->name ?></h1>
                    <span class="code-cp">Dự án thuộc xã: <span><?= $village->name ?></span></span>
                    <p class="des-dt-1 des-dt-3">
                        <?= $model->short_description ?>
                    </p>
                    <a href="<?= Url::toRoute(['campaign/support','id'=>$model->id]) ?>" class="bt-common-1 bt-u-2">THAM GIA ỦNG HỘ NGAY</a>
                </div>
            </div>
            <div class="tab-ct">
                <!-- Nav tabs -->
                <div class="out-ul-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php if(isset($donationItem)) {
                            $i=0;
                            foreach($donationItem as $item){
                                $i++;
                                /** @var $item \common\models\DonationItem */
                                ?>
                                <li role="presentation" class="<?php if($item->name == $name) {?>active <?php } ?>"><a href="#t<?= $item->id ?>" aria-controls="" role="tab" data-toggle="tab"><?= $item->name ?></a></li>
                            <?php  }}?>

                    </ul>
                </div>
                <div class="tab-content">
                    <?php if(isset($listDonation)){
                        $i=0;
                        foreach($listDonation as $item){?>
                            <div role="tabpanel" class="tab-pane <?php if($item->name_donation == $name) {?> active <?php } ?>" id="t<?= $item->id ?>">
                                <div class="list-dn">
                                    <div class="block-need">
                                        <div class="f-need">
                                            <span class="pr-need2">
                                                <?php if($item->number_least > $item->expected_number){?>
                                                <span>Đã vượt  <?= \frontend\helpers\FormatNumber::formatNumber($item->number_least - $item->expected_number) ?> <?php } else{ ?>
                                                    Còn thiếu: <span><?= \frontend\helpers\FormatNumber::formatNumber($item->expected_number - $item->number_donation) ?>
                                                    <?php } ?> <?= $item->unit ?></span></span><br>
                                            <div class="bar">
                                                <div class="bar-status" style="width:<?= $item->expected_number >0  ? $item->number_donation / $item->expected_number  * 100  : 0?>%;"></div>
                                            </div>
                                            <div class="line-i">
                                                <span class="num-donor"><?= sizeof($item->user) ?> người ủng hộ</span>
                                                <span class="pr-need">Tổng: <span><?= \frontend\helpers\FormatNumber::formatNumber($item->expected_number) ?>  <?= $item->unit ?></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-dn-1">
                                        <?php foreach((object)$item->user as $user){ ?>
                                            <div class="media item-dn">
                                                <div class="media-left">
                                                    <a href="#">
                                                        <img class="media-object" src="<?= $user->avatar ?>" alt="...">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading"><?= $user->username ?><span><?= \frontend\helpers\FormatNumber::formatNumber($user->donation_number) ?>  <?= $item->unit ?></span></h4>
                                                    <span class="y-old"></span><br>
                                                    <span class="address"><?= $user->address ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->
