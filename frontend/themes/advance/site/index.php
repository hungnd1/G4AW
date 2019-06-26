<?php

/* @var $this yii\web\View */
use common\models\News;
use frontend\helpers\UserHelper;
use yii\helpers\Url;

$this->title = 'G4WA';
/** @var \common\models\User $user */
/** @var \common\models\Category $listNewCategory */
$user = null;
if (!Yii::$app->user->isGuest) {
    $user = \common\models\User::findOne(Yii::$app->user->id);
}
?>
<div class="content">
    <div class="container">
        <?php if (UserHelper::isMobile()) { ?>
            <a href="<?= Url::toRoute(['site/index', 'lang' => 'vi']) ?>" class="sign-in"
               style="float:right;margin: 0px 15px 0px 0px;padding: 5px 9px;">VI</a>
            <a href="<?= Url::toRoute(['site/index', 'lang' => 'en']) ?>" class="sign-in"
               style="float:right;margin: 0px 15px 0px 0px;padding: 5px 7px;">EN</a>
            <a href="https://play.google.com/store/apps/details?id=vn.monkey.icco" style="float: right;margin: 0px 10px 0px 0px;" class="logo-cp"><img style="width: 100px;height: 35px;" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/google-play.png"></a>
            <a href="https://apps.apple.com/vn/app/greencoffee/id1254356638" style="float: right;" class="logo-cp"><img style="width: 100px;height: 35px;" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/app-store.png"></a>
        <?php } ?>
    </div>

    <!-- List NEW block-->

    <div class="container">
        <div class="news-block cm-block">
            <h2>Thông tin thời tiết</h2>
            <div class="google-map">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <!--end list CT block-->


    <!-- List NEW block-->

    <div class="container">
        <div class="news-block cm-block">
            <h2>Thông tin GAPs<a
                    href="<?= Url::toRoute(['news/index']) ?>"><span>Tất cả</span><i
                        class="fa fa-chevron-right"></i></a></h2>
            <div class="list-item">
                <?php
                $listNew = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
                    ->orderBy(['created_at' => SORT_DESC])->limit(6)->all();
                if (isset($listNew) && !empty($listNew)) {
                    /** @var \common\models\News $new */
                    foreach ($listNew as $new) {
                        $thumbnail = $new->getImageLink();
                        ?>
                        <div class="news">
                            <div class="thumb-common">
                                <img src="../img/blank.gif">
                                <a href="<?= Url::toRoute(['news/detail', 'id' => $new->id]) ?>"><img
                                        class="thumb-cm"
                                        src="<?= $thumbnail ?>"><br></a>
                            </div>
                            <div class="if-cm-2">
                                <a href="<?= Url::toRoute(['news/detail', 'id' => $new->id]) ?>"><h3
                                        class="name-1"><?= str_replace(mb_substr($new->title, 40, strlen($new->title), 'utf-8'), '...', $new->title) ?></h3>
                                    <br></a>
                                <span
                                    class="des-cm-1"><?= str_replace(mb_substr($new->short_description, 100, strlen($new->short_description), 'utf-8'), '...', $new->short_description) ?>
                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $new->id]) ?>" class="read-more">Đọc
                                        thêm</a></span>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>

<!---->
    <!--    <!--end list CT block-->-->
    <!---->
    <!--    <div class="container">-->
    <!--        <div class="banner-block clearfix">-->
    <!--            <img src="../img/green-coffee-vietnam-partners-logo-all.jpg">-->
    <!--        </div>-->
    <!--    </div>-->

    <!--end banner block-->

    <!--partner block-->
    <div class="partner-block clearfix">
        <div class="container">
            <h2><?= UserHelper::multilanguage('Các đơn vị liên kết', 'Unit Link') ?></h2>
            <a href=""><img src="../img/link_contact.PNG"></a></li>
        </div>
    </div>
    <!--end partner-->
</div>
<!-- end content -->
<script>


</script>
<script type="text/javascript">
    google.maps.event.addDomListener(window, "load", initMap);
    function loadMore() {
        var url = '<?= Url::toRoute(['site/get-villages'])?>';
        var page = parseInt($('#page').val()) + 1;
        var id = ($('#id').val());
        var total = parseInt(($('#total').val()));
        var filter = ($('#filter').val());
        var numberCount = parseInt($('#numberCount').val()) + 10;
        $.ajax({
            url: url,
            data: {
                'id': id,
                'page': page,
                'filter': filter
            },
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                if (null != result && '' != result) {
                    $(result).insertBefore('#last-comment');
                    document.getElementById("page").value = page + 9;
                    document.getElementById("numberCount").value = numberCount;
                    if (numberCount > total) {
                        $('#more').css('display', 'none');
                    }

                    $('#last-comment').html('');
                } else {
                    $('#last-comment').html('');
                }

                return;
            },
            error: function (result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                $('#last-comment').html('');
                return;
            }
        });//end jQuery.ajax
    }

    function loadCategoryNews(id) {
        var url = '<?= Url::toRoute(['site/get-category-news']) ?>';
        $.ajax({
            url: url,
            data: {
                'id': id
            },
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                $('div .list-item').html(result);
                return;
            },
            error: function (result) {
                alert("<?= Yii::t('app', 'fail_message') ?>");
                $('#last-comment').html('');
                return;
            }
        });//end jQuery.ajax
    }

</script>