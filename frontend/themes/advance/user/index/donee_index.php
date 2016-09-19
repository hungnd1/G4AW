<?php
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
                <?=  \yii\helpers\Html::img($model->getAvatar(),['class'=>'avt-account']) ?>
                <h2 class="us-name"><?= $model->fullname ?></h2>
                <span class="us-add"><?= $model->address ?></span><br>
                <span class="you-are">"Bạn là người cần hỗ trợ"</span>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?= ($active == 1) ? 'active' : '' ?>"><a href="#my-request"
                                                                                         aria-controls="my-cp"
                                                                                         role="tab" data-toggle="tab">Yêu cầu của tôi</a></li>
                <li role="presentation" class="<?= ($active == 2) ? 'active' : '' ?>"><a href="#my-cp"
                                                                                         aria-controls="my-cp"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch của tôi</a></li>


            </ul>
            <div class="container">
                <?= \common\widgets\Alert::widget() ?>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="my-request">
                        <?php
                        $requests = $model->getDonationRequests()->andWhere( 'status != :p_status',[':p_status'=>\common\models\DonationRequest::STATUS_DELETED])->all();
                        if (count($requests) == 0):
                            ?>
                            <div class="emp-act">
                                <h3>Bạn chưa có yêu cầu nào</h3><br>
                                <a href="<?= \yii\helpers\Url::to(['/donation-request/create'])?>" class="bt-common-1"><i class="fa fa-plus-circle"></i>Khởi tạo yêu cầu</a>
                            </div>
                        <?php else: ?>
                            <div class="creat-i"><a href="<?= \yii\helpers\Url::to(['/donation-request/create'])?>" class="bt-common-1"><i class="fa fa-plus-circle"></i>Khởi
                                    tạo yêu cầu</a></div>
                            Bạn có <?= count($requests) ?> yêu cầu trợ giúp
                        <?php endif; ?>

                        <div class="r-list">
                            <div class="re-1">
                                <?php

                                foreach ($requests as $item) {
                                    echo $this->render('/donation-request/_item', ['model' => $item]);
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane <?= ($active == 3) ? 'active' : '' ?>" id="my-cp">
                        <div class="list-item">
                            <?php
                            $myCampaigns = $model->campaigns0;
                            foreach ($myCampaigns as $campaign) {
                                echo $this->render('/campaign/_item_donee', ['model' => $campaign]);
                            }
                            ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- end ac block-->
</div>
<!-- end common page-->

