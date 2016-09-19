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
use kartik\alert\Alert;
use yii\helpers\Url;

?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>">Cá nhân</a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <a href="<?= Url::toRoute(['user/update','id'=>$model->id])?>" class="bt-edit"><i class="fa fa-pencil"></i></a>
                    <?php $image = $model->getAvatar() ?>
                    <img src="<?= $image ?>"><br>
                    <h4><?= $model->fullname ?></h4>
                    <p><?= $model->address ?></p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Số điện thoại</span><br>
                    <span class="b-span"><?= $model->phone_number ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span"><?= $model->email ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Giới tính</span><br>
                    <span class="b-span">
                        <?= User::getGenderName($model->gender)?>
                    </span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Tuổi</span><br>
                    <span class="b-span"><?= User::getOld($model->birthday) ?></span>
                </div>
            </div>
            <div class="right-cn">
                <div class="creat-cp">
                    <h4>Bạn có nhu cầu cần hỗ trợ?</h4>
                    <a href="<?= Url::toRoute(['donation-request/index']) ?>">ĐĂNG KÝ NHẬN TRỢ GIÚP</a>
                </div>
                <div class="tab-ct">
                    <!-- Nav tabs -->
                    <div class="out-ul-tab ">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="hidden-lg hidden-md hidden-info"><a href="#t1" aria-controls="" role="tab" data-toggle="tab">Thông tin cá nhân</i></a></li>
                            <li role="presentation" class="active"><a href="#t2" aria-controls="" role="tab" data-toggle="tab">Yêu cầu của tôi</i></a></li>
                            <li role="presentation"><a href="#t3" aria-controls="" role="tab" data-toggle="tab">Chiến dịch của tôi</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane hidden-lg hidden-md hidden-info " id="t1">
                            <div class="block-cm-left top-cn-left">
                                <a href="<?= Url::toRoute(['user/update','id'=>$model->id])?>" class="bt-edit"><i class="fa fa-pencil"></i></a>
                                <?php $image = $model->getAvatar() ?>
                                <img src="<?= $image ?>"><br>
                                <h4><?= $model->fullname ?></h4>
                                <p><?= $model->address ?></p>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Số điện thoại</span><br>
                                <span class="b-span"><?= $model->phone_number ?></span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Email</span><br>
                                <span class="b-span"><?= $model->email ?></span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Giới tính</span><br>
                            <span class="b-span">
                                <?= User::getGenderName($model->gender)?>
                            </span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Tuổi</span><br>
                                <span class="b-span"><?= User::getOld($model->birthday) ?></span>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="t2">
                            <div class="list-item list-tab-2">
                                <?php
                                if(isset($modelDonation) && !empty($modelDonation)){
                                foreach($modelDonation as $item) {
//                                    echo "<pre>";
//                                    print_r($item);
//                                    die();
                                    ?>
                                    <div class="out-card">
                                        <div class="card-item">
                                            <div class="thumb-common">
                                                <img src="../img/blank.gif">
                                                <a href="<?= Url::toRoute(['donation-request/view','id_donate'=>$item->id]) ?>"><img class="thumb-cm"
                                                                 src="<?= $item->getThumbnailLink() ?>"><br></a>
                                            </div>
                                            <div class="if-cm-1">
                                                <div class="top-cp">
                                                    <a href="<?= Url::toRoute(['donation-request/view','id_donate'=>$item->id]) ?>"><h3
                                                            class="name-1"><?= $item->title ?></h3><br></a>
                                                    <p class="des-ct-2"><?= $item->short_description ?></p>
                                                </div>
                                            </div>
                                            <?php if ($item->status == DonationRequest::STATUS_APPROVED) { ?>
                                                <div class="status-cp st-accept">
                                                    <?= $item->getStatusName() ?>
                                                </div>
                                            <?php } else if ($item->status == DonationRequest::STATUS_REJECTED) { ?>
                                                <div class="status-cp st-cancel">
                                                    <?= $item->getStatusName()?>
                                                </div>
                                            <?php } else if ($item->status == DonationRequest::STATUS_NEW) { ?>
                                                <div class="status-cp st-pending">
                                                    <?= $item->getStatusName() ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                }
                                ?>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="t3">
                            <div class="list-item list-tab-4">
                                <?php
                                if(isset($cam) && !empty($cam)) {
                                    foreach ($cam as $item) {
                                        ?>
                                        <div class="out-card">
                                            <div class="card-item">
                                                <div class="thumb-common">
                                                    <img src="../img/blank.gif">
                                                    <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><img
                                                            class="thumb-cm"
                                                            src="<?= $item->image ?>"><br></a>
                                                </div>
                                                <div class="if-cm-1">
                                                    <div class="top-cp">
                                                        <a href="<?= Url::toRoute(['campaign/view','id'=>$item->id]) ?>"><h3
                                                                class="name-1"><?= $item->name ?></h3><br></a>
                                                        <p class="des-ct-2"><?= $item->short_description ?></p>
                                                    </div>
                                                    <div class="bt-cp">
                                                        <a href="<?= $item->leadid?Url::toRoute(['donor/view','id'=>$item->leadid]):'' ?>" class="logo-cp"><img
                                                                src="<?= $item->imagelead ? $item->imagelead : '' ?>"></a>
                                                        <a href="<?= $item->leadid ? Url::toRoute(['donor/view','id'=>$item->leadid]):'' ?>">
                                                            <h4><?= $item ? $item->leadname : '' ?></h4></a>
                                                        <span class="add-cp"><?= $item->leadaddress ? LeadDonor::_substr($item->leadaddress,25) : '' ?></span>
                                                        <div class="bar">
                                                            <div class="bar-status"
                                                                 style="width:<?= $item->status ?>%;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
