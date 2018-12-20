<?php

use common\models\News;
use frontend\helpers\UserHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="top-nav header">
    <div class="container">
        <a class="navbar-brand logo" style="padding: 4px 0px;" href="<?= Url::toRoute(['site/index']) ?>"><img
                src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/GREENcoffee_LOGO.png" height="70"
                style="padding-top: 10px;"></a>
        <?php if (!UserHelper::isMobile()) { ?>
            <b class="navbar-brand title-header" style=""
               href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('DỊCH VỤ THÔNG TIN GREENCOFFEE', 'Economic Research Institute of Posts and Telecommunications') ?></b>
        <?php } ?>
        <!--        <b style="font-size: 18px;margin: 0 0 0 30px;padding: 40px 0 0 0;">VIỆN KINH TẾ BƯU ĐIỆN</b>-->
        <?php if (UserHelper::isMobile()) { ?>
            <a href="<?= Url::toRoute(['site/index', 'lang' => 'vi']) ?>" class="navbar-brand logo"
               style="margin: 20px 0px 0px 0px;padding: 5px 9px;">VI</a>
            <a href="<?= Url::toRoute(['site/index', 'lang' => 'en']) ?>" class="navbar-brand logo"
               style="margin: 20px 0px 0px 0px;padding: 5px 7px;">EN</a>
        <?php } ?>
        <a class="ic-mn-mb hidden-mn" data-toggle="collapse" href="#collapseExample" aria-expanded="false"
           aria-controls="collapseExample">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </a>
        <div class="top-lead hidden-xs hidden-sm">
            <a href="<?= Url::toRoute(['site/index', 'lang' => 'vi']) ?>" class="sign-in"
               style="margin-left: 5px;    padding: 5px 9px;">VI</a>
            <a href="<?= Url::toRoute(['site/index', 'lang' => 'en']) ?>" class="sign-up"
               style="margin-left: 10px;    padding: 5px 7px;">EN</a>
            <a href="" class="logo-cp"><img
                    src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/face.jpg"></a>
            <a href="" class="logo-cp"><img
                    src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/google.png"></a>
            <a href="" class="logo-cp"><img
                    src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/twitter.jpg"></a>
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
                <?php if ($_SESSION['vi'] == 'vi') { ?>
                    <input type="text" name="keyword" onfocus="this.select();"
                           placeholder="<?= UserHelper::multilanguage('Tìm kiếm ...', 'Search ....') ?>"
                           value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                <?php } else { ?>
                    <input type="text" name="keyword" onfocus="this.select();" placeholder="Search ..."
                           value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                <?php } ?>
                <input type="hidden" name="search"
                       value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                <i class="fa fa-search"></i>
                <?php ActiveForm::end(); ?>
            </div>

            <ul class="menu-web">
                <li class="active"><a
                        href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                </li>
                <li>
                    <a href="<?= Url::toRoute(['site/about']) ?>">Giới thiệu</a>
                </li>
                <li><a href="<?= Url::toRoute(['site/weather']) ?>">Thời tiết</a></li>
                <li><a href="<?= Url::toRoute(['disease/index']) ?>">Thông tin sâu bệnh</a></li>
                <li>
                    <a href="<?= Url::toRoute(['price/index']) ?>">Thông tin thị trường</a>
                </li>
                <li>
                    <a href="<?= Url::toRoute(['user/my-page']) ?>">Giao dịch</a>
                </li>
                <li>
                    <a href="<?= Url::toRoute(['news/index']) ?>">Thông tin GAPs</a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?//= Url::toRoute(['news/index']) ?><!--">Hỏi đáp</a>-->
<!--                </li>-->
            </ul>

            <?php
            if (Yii::$app->user->isGuest) {
                ?>
                <a href="<?= Url::toRoute(['site/login']) ?>"
                   class="sign-in"><?= UserHelper::multilanguage('Đăng nhập', 'Sign In') ?></a>
<!--                <a href="--><?//= Url::toRoute(['site/signup']) ?><!--"-->
<!--                   class="sign-up">--><?//= UserHelper::multilanguage('Đăng ký', 'Sign Up') ?><!--</a>-->
                <?php
            } else {
                ?>
                <div class="bb-login-ok">
                    <a data-toggle="collapse" href="#collapse-user" aria-expanded="false"
                       aria-controls="collapseExample">
                        <?= UserHelper::multilanguage('Xin chào', 'Hello') ?>
                        <?= Yii::$app->user->identity->username; ?>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="collapse-user">
                        <ul>
                            <li>
                                <a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>"><?= UserHelper::multilanguage('Cá nhân', 'Personal') ?></a>
                            </li>
<!--                            <li>-->
                               <!-- <a href="<?= Url::toRoute(['user/change-my-password', 'id' => Yii::$app->user->identity->id]) ?>"><?= UserHelper::multilanguage('Đổi mật khẩu', 'Change Password') ?></a> -->
<!--                            </li>-->
                            <li><a href="<?= \yii\helpers\Url::to(['site/logout']) ?>"
                                   data-method="post"><?= UserHelper::multilanguage('Đăng xuất', 'Logout') ?></a>
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
    <div class="container" style="width: 1350px;">
        <ul class="menu-web">
            <li class="active"><a
                    href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['site/about']) ?>">Giới thiệu</a>
            </li>
            <li><a href="<?= Url::toRoute(['site/weather']) ?>">Thời tiết</a></li>
            <li><a href="<?= Url::toRoute(['disease/index']) ?>">Thông tin sâu bệnh</a></li>
            <li>
                <a href="<?= Url::toRoute(['price/index']) ?>">Thông tin thị trường</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['user/my-page']) ?>">Giao dịch</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['news/index']) ?>">Thông tin GAPs</a>
            </li>
<!--            <li>-->
<!--                <a href="--><?//= Url::toRoute(['news/index']) ?><!--">Hỏi đáp</a>-->
<!--            </li>-->
            <div class="right-nav hidden-sm hidden-xs">

                <div class="f-search">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute('site/search'),
                        'method' => 'GET'
                    ]); ?>
                    <input type="text" name="keyword" onfocus="this.select();"
                           placeholder="<?= UserHelper::multilanguage('Tìm kiếm ...', 'Search ...') ?>"
                           value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                    <input type="hidden" name="search"
                           value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                    <i class="fa fa-search"></i>
                    <?php ActiveForm::end(); ?>
                </div>
                <?php
                if (Yii::$app->user->isGuest) {
                    ?>
                    <a href="<?= Url::toRoute(['site/login']) ?>"
                       class="sign-in"><?= UserHelper::multilanguage('Đăng nhập', 'Sign In') ?></a>
<!--                    <a href="--><?//= Url::toRoute(['site/signup']) ?><!--"-->
<!--                       class="sign-up">--><?//= UserHelper::multilanguage('Đăng ký', 'Sign Up') ?><!--</a>-->
                    <?php
                } else {
                    ?>
                    <div class="hello-us dropdown hidden-sm hidden-xs">
                        <a id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= UserHelper::multilanguage('Xin chào', 'Hello') ?>
                            <?= Yii::$app->user->identity->username; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>"><?= UserHelper::multilanguage('Cá nhân', 'Persional') ?></a>
                            </li>
<!--                            <li>-->
<!--                                <a href="<?//= Url::toRoute(['user/change-my-password', 'id' => Yii::$app->user->identity->id]) ?><?//= UserHelper::multilanguage('Đổi mật khẩu', 'Change Password') ?></a>-->
<!--                            </li>-->
                            <li><a href="<?= \yii\helpers\Url::to(['site/logout']) ?>"
                                   data-method="post"><?= UserHelper::multilanguage('Đăng xuất', 'Logout') ?></a>
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