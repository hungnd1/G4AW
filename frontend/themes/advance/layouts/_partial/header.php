<?php

use common\models\News;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="top-nav header">
    <div class="container">
        <a class="navbar-brand logo" href="<?= Url::toRoute(['site/index']) ?>"><img
                src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/unnamed.png" height="60"></a>

        <a class="ic-mn-mb hidden-mn" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </a>
        <div class="top-lead hidden-xs hidden-sm">
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
                <input type="text" name="keyword" onfocus="this.select();" placeholder="Tìm kiếm ..."
                       value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                <input type="hidden" name="search"
                       value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                <i class="fa fa-search"></i>
                <?php ActiveForm::end(); ?>
            </div>

            <ul class="menu-web">
                <li class="active"><a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a></li>
                <li><a href="">Giới thiệu</a></li>
                <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_NEW]) ?>">Tin tức</a></li>
                <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_KNOW]) ?>">Nhà nông nên biết</a></li>
                <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_MARKET]) ?>">Chợ nhà nông</a></li>
                <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_HEALTH]) ?>">Sức khỏe cộng đồng</a></li>
            </ul>
        </div>
    </div>
</div>
<nav class="bottom-nav hidden-xs hidden-sm">
    <div class="container">
        <ul class="menu-web">
            <li class="active"><a href="<?= Url::toRoute(['site/index']) ?>"><i class="fa fa-home hidden-md"></i>Trang chủ</a>
            </li>
            <li><a href="">Giới thiệu</a></li>
            <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_NEW]) ?>">Tin tức</a></li>
            <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_KNOW]) ?>">Nhà nông nên biết</a></li>
            <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_MARKET]) ?>">Chợ nhà nông</a></li>
            <li><a href="<?= Url::toRoute(['news/index','type'=>News::TYPE_HEALTH]) ?>">Sức khỏe cộng đồng</a></li>
            <div class="right-nav hidden-sm hidden-xs">

                <div class="f-search">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute('site/search'),
                        'method' => 'GET'
                    ]); ?>
                    <input type="text" name="keyword" onfocus="this.select();" placeholder="Tìm kiếm..."
                           value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                    <input type="hidden" name="search"
                           value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                    <i class="fa fa-search"></i>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </ul>
    </div><!-- /.container -->
</nav><!-- /.navbar -->

<script type="text/javascript">

</script>