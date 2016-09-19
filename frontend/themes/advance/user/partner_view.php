<?php
use \common\models\Campaign;
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 8:27 AM
 */
/** @var $model \common\models\User */
/** @var $this \yii\web\View */
/** @var $currentModel \common\models\User */
$this->title ='Thông tin đối tác cầu nối';
if(!Yii::$app->user->isGuest){
    $followText ='Theo dõi';
    $follow = $currentModel->getFollows()->andWhere(['user_followed_id'=>$model->id])->one();
    if($follow){
        $followText ='Đang theo dõi';
    }
}

?>
<!-- common page-->
<div class="content-common">
    <!--ac-block-->
    <div class="account-block">
        <div class="container-fluid">
            <div class="tp-account">
                <?= \yii\helpers\Html::img($model->getAvatar(), ['class' => 'avt-account']) ?>
                <h2 class="us-name"><?= $model->fullname ?></h2>
                <span class="us-add"><?= $model->address ?></span><br>
                <?php if(!Yii::$app->user->isGuest):?>
                    <?= \yii\helpers\Html::a($followText,'#',['class'=>'fl-bt','data-id'=>$model->id]);?>

                <?php endif;?>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">

                <li role="presentation" class="<?= ($active == 1) ? 'active' : '' ?>"><a href="#my-cp"
                                                                                         aria-controls="my-cp"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch đã tạo</a></li>
                <li role="presentation" class="<?= ($active == 2) ? 'active' : '' ?>"><a href="#favorite"
                                                                                         aria-controls="favorite"
                                                                                         role="tab" data-toggle="tab">Chiến
                        dịch đang theo dõi</a></li>
                <li role="presentation" class="<?= ($active == 3) ? 'active' : '' ?>"><a href="#info"
                                                                                         aria-controls="info" role="tab"
                                                                                         data-toggle="tab">Thông tin cá
                        nhân</a></li>
            </ul>
            <div class="container">
                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane <?= ($active == 1) ? 'active' : '' ?>" id="my-cp">
                        <div class="list-item">
                            <?php $myCampaigns = $model->getCampaigns()->andFilterWhere(['!=', 'status',  Campaign::STATUS_DELETED])->all(); ?>
                            <?php
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
                    <div role="tabpanel" class="tab-pane <?= ($active == 3) ? 'active' : '' ?>" id="info">
                        <div class="in-info">
                            <p>Số chiến dịch đã tạo: <span><?=$model->totalMyCampaign()?></span></p>
                            <p>Số yêu cầu: <span><?=$model->totalRequestToMe()?></span></p>
                            <p>Số chiến dịch đã hoàn thành: <span><?=$model->totalMyCampaignDone()?></span></p>
                            <p>Số chiến dịch đang hoạt động: <span><?=$model->totalMyCampaignActive()?></span></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end ac block-->
</div>
<!-- end common page-->
