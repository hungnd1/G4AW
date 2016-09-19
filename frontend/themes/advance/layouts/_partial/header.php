<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="top-nav header">
    <div class="container">
        <a class="navbar-brand logo" href="<?= Url::toRoute(['site/index']) ?>"><img
                src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/logo.png" height="50"></a>

        <a class="ic-mn-mb hidden-mn" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </a>
        <div class="top-lead hidden-xs hidden-sm">
            <?php if (isset($leadDonor) && !empty($leadDonor)) {
                /** @var $leadDonor \common\models\LeadDonor */
                ?>
                <a href="<?= Url::toRoute(['donor/view','id'=>$leadDonor->id]) ?>" class="logo-cp"><img src="<?= $leadDonor->getImageLink() ?>"></a>
                <h5>
                    <?= $leadDonor->name ?><br>
                    <span>Đơn vị bảo trợ</span>
                </h5>
            <?php }if(!Yii::$app->request->get('id_village')) { ?>
                <a href="<?= Url::toRoute(['donor/view','id'=>1]) ?>" class="logo-cp"><img
                        src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/logo-cp.png"></a>
                <h5>
                    Tập đoàn VNPT<br>
                    <span>Đơn vị tài trợ CNTT</span>
                </h5>
            <?php } ?>
        </div>

    </div>
</div>
</div>
<div class="menu-mb hidden-mn">
    <div class="collapse" id="collapseExample">
        <div class="in-mn-mb">
            <div class="f-search">
                <?php $form = ActiveForm::begin([
                    'action' => Url::toRoute('site/search'),
                    'method' => 'GET'
                ]); ?>
                <input type="text" name="keyword" onfocus="this.select();" placeholder="Tìm kiếm xã, dự án..."
                       value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                <input type="hidden" name="search"
                       value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                <i class="fa fa-search"></i>
                <?php ActiveForm::end(); ?>
            </div>

            <ul class="menu-web">
                <li class="active"><a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a></li>
                <li><a href="<?= Url::toRoute(['news/index']) ?>">Tin tức</a></li>
                <li><a href="<?= Url::toRoute(['campaign/list-campaign']) ?>">Chương trình phát triển</a></li>
                <li><a href="<?= Url::toRoute(['site/rules']) ?>">Nội quy</a></li>
                <li><a href="http://diendan.xavungcao.vivas.vn">Diễn Đàn</a></li>
                <li><a href="<?= Url::toRoute(['site/contact']) ?>">Liên hệ</a></li>
            </ul>
            <?php
            if (Yii::$app->user->isGuest) {
                ?>
                <a href="<?= Url::toRoute(['site/login']) ?>" class="sign-in">Đăng nhập</a>
                <a href="<?= Url::toRoute(['site/signup']) ?>" class="sign-up">Đăng ký</a>
                <?php
            } else {
                ?>
                <div class="bb-login-ok">
                    <a data-toggle="collapse" href="#collapse-user" aria-expanded="false"
                       aria-controls="collapseExample">
                        Xin chào
                        <?php
                        if (empty(Yii::$app->user->identity->fullname)) {
                            echo Yii::$app->user->identity->username;
                        } else {
                            echo Yii::$app->user->identity->fullname;
                        }
                        ?>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="collapse-user">
                        <ul>
                            <li><a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>">Cá
                                    nhân</a></li>
                            <li>
                                <a href="<?= Url::toRoute(['user/change-my-password', 'id' => Yii::$app->user->identity->id]) ?>">Đổi
                                    mật khẩu</a></li>
                            <li><a href="<?= \yii\helpers\Url::to(['site/logout']) ?>" data-method="post">Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<nav class="bottom-nav hidden-xs hidden-sm">
    <div class="container">
        <ul class="menu-web">
            <li class="active"><a href="<?= Url::toRoute(['site/index']) ?>"><i class="fa fa-home hidden-md"></i>Trang chủ</a>
            </li>
            <li><a href="<?= Url::toRoute(['news/index']) ?>">Tin tức</a></li>
            <li><a href="<?= Url::toRoute(['campaign/list-campaign']) ?>">Chương trình phát triển</a></li>
            <li><a href="<?= Url::toRoute(['site/rules']) ?>">Nội quy</a></li>
            <li><a href="http://diendan.xavungcao.vivas.vn">Diễn Đàn</a></li>
            <li><a href="<?= Url::toRoute(['site/contact']) ?>">Liên hệ</a></li>
            <div class="right-nav hidden-sm hidden-xs">

                <div class="f-search">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute('site/search'),
                        'method' => 'GET'
                    ]); ?>
                    <input type="text" name="keyword" onfocus="this.select();" placeholder="Tìm kiếm xã, dự án..."
                           value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                    <input type="hidden" name="search"
                           value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                    <i class="fa fa-search"></i>
                    <?php ActiveForm::end(); ?>
                </div>
                <?php
                if (Yii::$app->user->isGuest) {
                    ?>
                    <a href="<?= Url::toRoute(['site/login']) ?>" class="sign-in">Đăng nhập</a>
                    <a href="<?= Url::toRoute(['site/signup']) ?>" class="sign-up">Đăng ký</a>
                    <?php
                } else {
                    ?>
                    <div class="hello-us dropdown hidden-sm hidden-xs">
                        <a id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Xin chào
                            <?php
                            if (empty(Yii::$app->user->identity->fullname)) {
                                echo Yii::$app->user->identity->username;
                            } else {
                                echo Yii::$app->user->identity->fullname;
                            }
                            ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>">Cá
                                    nhân</a></li>
                            <li>
                                <a href="<?= Url::toRoute(['user/change-my-password', 'id' => Yii::$app->user->identity->id]) ?>">Đổi
                                    mật khẩu</a></li>
                            <li><a href="<?= \yii\helpers\Url::to(['site/logout']) ?>" data-method="post">Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
        </ul>
    </div><!-- /.container -->
</nav><!-- /.navbar -->

<script type="text/javascript">

</script>