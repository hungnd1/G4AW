<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:28 AM
 */
use common\models\Campaign;
use yii\helpers\Url;

/** @var $model \common\models\Campaign */
/** @var $village \common\models\Village */
?>

<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['campaign/view', 'id' => $model->id]) ?>"><?= $model->name ?></a>
                </div>
                <div class="m-content">
                    <h1><?= $model->name ?></h1>
                    <div class="fb-share-button" data-href="<?= $_SERVER['REQUEST_URI'] ?>"
                         data-layout="button" data-size="small" data-mobile-iframe="true">
                        <a class="fb-xfbml-parse-ignore" target="_blank"
                           href="https://www.facebook.com/sharer/sharer.php?u=<?= $_SERVER['REQUEST_URI'] ?>;src=sdkpreparse">
                            Chia sẻ</a>
                    </div><br><br>
                    <span
                        class="code-cp">Dự án thuộc xã: <span><?= $village->name ? $village->name : '' ?></span></span>
                    <p class="des-dt"><?= $model->short_description ?></p>
                    <div class="content-dt">
                        <?= preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->content) ?>

                    </div>
                    <div class="post-related">
                        <h2>Tin tức chiến dịch</h2>
                        <div>
                            <?php if (isset($newsRelated)) {
                                foreach ($newsRelated as $item) {
                                    /** @var $item \common\models\News */
                                    ?>
                                    <div class="news">
                                        <div class="thumb-common">
                                            <img src="../img/blank.gif">
                                            <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><img
                                                    class="thumb-cm" src="<?= $item->getThumbnailLink() ?>"><br></a>
                                        </div>
                                        <div class="if-cm-2">
                                            <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><h3
                                                    class="name-1"><?= $item->title ?></h3></a>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="block-need block-cm-2">
                    <span class="pr-need2">Tỉ lệ đóng góp: <span><?= $rateDonation ?>%</span></span><br>
                    <div class="bar">
                        <div class="bar-status" style="width:<?= $rateDonation ?>%;"></div>
                    </div>
                    <a href="<?= Url::toRoute(['campaign/support', 'id' => $model->id]) ?>" class="bt-common-1">THAM GIA
                        ỦNG HỘ NGAY</a>
                </div>
                <div class="block-give block-cm-2">
                    <h3>Danh mục đóng góp</h3>

                    <?php if (isset($donationItem)) {
                        $i = 0;
                        foreach ($donationItem as $item) {
                            $i++;
                            /** @var $item \common\models\DonationItem */
                            ?>
                            <div class="gv-1">
                                <span class="num-gv"><?= $i ?></span>
                                <h4><a  href="<?= Url::toRoute(['campaign/donation', 'id' => $model->id,'name'=>$item->name_donation]) ?>">Chi tiết</a></h4>
                                <h4><?= $item->name_donation ?></h4>
                                <p><?= frontend\helpers\FormatNumber::formatNumber($item->number_donation) ?>
                                    /<span><?= \frontend\helpers\FormatNumber::formatNumber($item->expected_number) ?> <?= $item->unit ?></span>
                                </p>
                            </div>
                        <?php }
                    } ?>
                </div>
                <div class="block-related block-cm-2">
                    <h3>Chiến dịch liên quan</h3>
                    <div class="list-related">
                        <?php if (isset($campaign_related)) {
                            foreach ($campaign_related as $item) {
                                /** @var $item Campaign */
                                ?>
                                <div class="l-related">
                                    <div class="thumb-common">
                                        <img class="blank-img" src="../img/blank.gif">
                                        <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><img
                                                class="thumb-cm" src="<?= $item->image ?>"></a>
                                    </div>
                                    <div class="l-i-rl">
                                        <h4>
                                            <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><?= $item->name ?></a>
                                        </h4>
                                        <p>Tỉ lệ hoàn thành: <span><?= $item->rateDonation ?>%</span></p>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                        <?php if (isset($listCampaign_village)) {
                            foreach ($listCampaign_village as $item) {
                                /** @var $item Campaign */
                                ?>
                                <div class="l-related">
                                    <div class="thumb-common">
                                        <img class="blank-img" src="../img/blank.gif">
                                        <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><img
                                                class="thumb-cm" src="<?= $item->image ?>"></a>
                                    </div>
                                    <div class="l-i-rl">
                                        <h4>
                                            <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><?= $item->name ?></a>
                                        </h4>
                                        <p>Tỉ lệ hoàn thành: <span><?= $item->rateDonation ?>%</span></p>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container">
        <div class="block-ads-cp">
            <div class="left-block-ads">
                <a href="<?= Url::toRoute(['donor/view', 'id' => $lead->id]) ?>" class="img-cp"><img class="thumb-cm" src="<?= $lead->getImageLink() ?>"></a>
                <div class="left-info-ads">
                    <h3><a href="<?= Url::toRoute(['donor/view', 'id' => $lead->id]) ?>"><?= $lead->name ?></a></h3>
                    <p class="if-leaddn">
                        <i class="fa fa-globe" aria-hidden="true"></i> <span>Website:</span> <a target="_blank"
                                                                                                href="<?= $lead->website ?>"><?= $lead->website ?></a><br>

                        <i class="fa fa-envelope" aria-hidden="true"></i> <span>Email:</span> <?= $lead->email ?>
                        <br>

                        <i class="fa fa-mobile" aria-hidden="true"></i>
                        <span>Số điện thoại:</span> <?= $lead->phone ?><br>

                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span>Địa chỉ:</span> <?= $lead->address ?>
                    </p>
                </div>
            </div>
            <div class="right-block-ads">
                <?php if ($lead->video) { ?>
                    <video width="400" controls >
                        <source src="<?= $lead->getVideoUrl() ?>" type="video/mp4">
                        <source src="<?= $lead->getVideoUrl() ?>" type="video/avi">
                    </video>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<!-- end content -->