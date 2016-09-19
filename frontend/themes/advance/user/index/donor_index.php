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
                <?= \yii\helpers\Html::img($model->getAvatar(),['class'=>'avt-account'])?>
                <h2 class="us-name"><?= $model->fullname ?></h2>
                <span class="us-add"><?= $model->address ?></span><br>
                <span class="you-are">"Bạn là nhà hảo tâm"</span>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?= ($active == 1) ? 'active' : '' ?>"><a href="#my-cp"
                                                                                         aria-controls="my-cp"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch đã quyên góp</a></li>
                <li role="presentation" class="<?= ($active == 2) ? 'active' : '' ?>"><a href="#favorite"
                                                                                         aria-controls="favorite"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch đang theo dõi</a></li>

            </ul>
            <div class="container">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="my-cp">
                        <div class="list-item">
                            <?php
                            $myCampaigns = $model->donatedCampaign;
                            foreach ($myCampaigns as $campaign) {
                                echo $this->render('/campaign/_item', ['model' => $campaign]);
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

                </div>
            </div>
        </div>
    </div>
    <!-- end ac block-->
</div>
<!-- end common page-->

