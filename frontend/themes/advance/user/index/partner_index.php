<?php
use \common\models\DonationRequest;
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 8:27 AM
 */
/** @var $model \common\models\User */
/** @var $this \yii\web\View */
$this->title='Trang cá nhân';
?>
<!-- common page-->
<div class="content-common">
    <!--ac-block-->
    <div class="account-block">
        <div class="container-fluid">
            <div class="tp-account">
                <?= \yii\helpers\Html::img($model->getAvatar(),['class'=>'avt-account'])?>
                <h2 class="us-name"><?= $model->fullname ?></h2>
                <span class="us-add"><?= $model->address ?></span><br>
                <span class="you-are">"Bạn là tổ chức cầu nối"</span>
            </div>
            <?php
                if($model->status == \common\models\User::STATUS_ACTIVE):
            ?>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?= ($active == 1) ? 'active' : '' ?>"><a href="#my-cp"
                                                                                         aria-controls="my-cp"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch của tôi</a></li>
                <li role="presentation" class="<?= ($active == 2) ? 'active' : '' ?>"><a href="#favorite"
                                                                                         aria-controls="favorite"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch đang theo dõi</a></li>
                <li role="presentation" class="<?= ($active == 3) ? 'active' : '' ?>"><a href="#request"
                                                                                         aria-controls="request"
                                                                                         role="tab" data-toggle="tab">Yêu
                        cầu trợ giúp</a></li>
            </ul>
            <div class="container">
                <?= \common\widgets\Alert::widget() ?>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="my-cp">
                        <?php
                        $myCampaigns = $model->getCampaigns()->andFilterWhere(['!=','status',\common\models\Campaign::STATUS_DELETED])->all();
                        if (count($myCampaigns) == 0):
                            ?>
                            <div class="emp-act">
                                <h3>Bạn chưa có chiến dịch từ thiện nào</h3><br>
                                <a href="<?= \yii\helpers\Url::to(['/campaign/create'])?>" class="bt-common-1"><i class="fa fa-plus-circle"></i>Khởi tạo chiến dịch</a>

                            </div>
                        <?php else: ?>
                            <div class="creat-i"><a href="<?= \yii\helpers\Url::to(['/campaign/create'])?>" class="bt-common-1"><i class="fa fa-plus-circle"></i>Khởi tạo chiến dịch</a></div>
                        <?php endif; ?>
                        <div class="list-item list-item-2">
                            <?php
                            foreach ($myCampaigns as $campaign) {
                                echo $this->render('/campaign/_item_partner', ['model' => $campaign]);
                            }
                            ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane <?= ($active == 2) ? 'active' : '' ?>" id="favorite">
                        <div class="list-item">
                            <?php
                            $myCampaignsFollowing = $model->campaignFollowings;
                            foreach ($myCampaignsFollowing as $campaign) {
                                echo $this->render('/campaign/_item', ['model' => $campaign]);
                            }
                            ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane <?= ($active == 3) ? 'active' : '' ?>" id="request">
                        <?php $requestsTo = $model->getDonationRequestsTo()->andWhere([ 'status'=>[DonationRequest::STATUS_NEW,DonationRequest::STATUS_APPROVED]])->all(); ?>
                        Bạn có <?= count($requestsTo) ?> yêu cầu trợ giúp
                        <div class="r-list">
                            <?php

                            foreach ($requestsTo as $request) {
                                echo $this->render('/donation-request/_item_partner', ['model' => $request]);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                elseif($model->status == \common\models\User::STATUS_WAITING):
            ?>
                    <div class="container">
                        <div class="mes-log">
                            <i class="fa fa-exclamation"></i>
                            <p>“Tài khoản của bạn đang được chờ phê duyệt bởi ban quản trị vndonor, xin vui lòng chờ chúng tôi liên lạc lại với bạn theo số điện thoại đăng ký, hoặc liên hệ ngay với chúng tôi theo địa chỉ email <span>vndonor@vivas.vn</span> hoặc theo số <b>hotline</b> <span>19001080</span>”</p><p></p></div>
                    </div>
            <?php
             endif;
            ?>
        </div>
    </div>
    <!-- end ac block-->
</div>
<!-- end common page-->

