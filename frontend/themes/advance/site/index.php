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
    <!-- slide -->
    <div class="container slider">
        <div id="slide-main" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php if (isset($listSlide) && !empty($listSlide)) {
                    $i = 0;
                    foreach ($listSlide as $item) {
                        /** @var $item \common\models\News */
                        ?>
                        <li data-target="#slide-main" data-slide-to="<?= $i ?>"
                            class="<?= $i == 0 ? 'active' : '' ?>"></li>
                        <?php $i++;
                    }
                } ?>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <?php if (isset($listSlide) && !empty($listSlide)) {
                    $i = 0;
                    foreach ($listSlide as $item) {
                        /** @var $item \common\models\News */
                        ?>

                        <div class="item <?php if ($i == 0) { ?> active <?php } ?>">
                            <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>">
                                <img style="width: 778px;height: 300px;" src="<?= $item->getImageLink() ?>"
                                     alt="..."></a>
                            <div class="carousel-caption">
                                <div>
                                    <h3>
                                        <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><?= $item->title ?></a>
                                    </h3>
                                    <?= str_replace(mb_substr($item->short_description, 200, strlen($item->short_description), 'utf-8'), '...', $item->short_description) ?>
                                    <br>
                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>" class="bt-more-1">
                                        <?= 'Xem thêm' ?></a>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                    }
                } else { ?>
                    <div class="item active">
                        <img src="../img/banner.jpg" alt="...">
                        <div class="carousel-caption">
                            <div>
                                <h3>Dự án xây dựng cầu treo dân sinh tỉnh Hòa Bình</h3>
                                Trường học tại xã X có quy mô 2 tầng, 7 phòng, bao gồm phòng học, phòng cho giáo viên,
                                thư viện. Được sự ủng hộ và hỗ trợ của các doanh nghiệp hảo tâm...<br>
                                <a href="" class="bt-more-1">Xem thêm</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div> <!-- Carousel -->
    </div>
    <!-- end slide -->

    <!--end list CT block-->


    <!-- List NEW block-->

    <?php foreach ($listNewCategory as $item) {
        /** var $item Category  */
        ?>
        <div class="container">
            <div class="news-block cm-block">
                <h2><?= strtoupper($item->display_name) ?><a
                        href="<?= Url::toRoute(['news/index']) ?>"><span>Tất cả</span><i
                            class="fa fa-chevron-right"></i></a></h2>
                <!--                <div class="cate-news">-->
                <!--                    <ul>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển của bộ KHDT</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                            <li><a href="">Chính sách phát triển</a></li>-->
                <!--                    </ul>-->
                <!--                </div>-->
                <div class="list-item">
                    <?php
                    $listNew = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
                        ->andWhere(['category_id'=>$item->id])
                        ->orderBy(['created_at' => SORT_DESC])->limit(3)->all();
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

    <?php } ?>


    <!--end list CT block-->

    <!-- List NEW block-->

<!--    <div class="container">-->
<!--        <div class="news-block cm-block">-->
<!--            -->
<!--        </div>-->
<!--    </div>-->

    <!--end list CT block-->

    <div class="container">
        <div class="banner-block clearfix">
            <img src="../img/banners.jpg">
        </div>
    </div>

    <!--end banner block-->

    <!--partner block-->
    <div class="partner-block clearfix">
        <div class="container">
            <h2><?= UserHelper::multilanguage('Các đơn vị liên kết', 'Unit Link') ?><a
                    href="<?= Url::toRoute(['site/linked']) ?>"><span><?= UserHelper::multilanguage('Tất cả', 'All') ?></span><i
                        class="fa fa-chevron-right"></i></a></h2>
            <ul class="bxslider3">
                <?php if (isset($listUnit) && !empty($listUnit)) {
                    foreach ($listUnit as $item) {
                        /** @var $item \common\models\UnitLink */ ?>
                        <li style="width: 400px;"><a target="_blank" href="<?= $item->link ?>"><img
                                    src="<?= $item->getThumbnailLink() ?>">
                                <h3><?= UserHelper::multilanguage($item->name, $item->name) ?></h3>
                            </a></li>
                    <?php }
                } else { ?>
                    <li><a href=""><img src="../img/p1.png"></a></li>
                    <li><a href=""><img src="../img/p2.png"></a></li>
                    <li><a href=""><img src="../img/p3.png"></a></li>
                    <li><a href=""><img src="../img/p4.png"></a></li>
                    <li><a href=""><img src="../img/p5.png"></a></li>
                    <li><a href=""><img src="../img/p1.png"></a></li>
                    <li><a href=""><img src="../img/p2.png"></a></li>
                    <li><a href=""><img src="../img/p3.png"></a></li>
                    <li><a href=""><img src="../img/p4.png"></a></li>
                    <li><a href=""><img src="../img/p5.png"></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!--end partner-->
</div>
<!-- end content -->
<script>


</script>
<script type="text/javascript">
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

    function loadVillage(id, name, filter) {
        $(".dropdown-menu li").on("click", function () {
            $(".dropdown-menu li").removeClass("active");
            $(this).addClass("active");
        });

        $(".tab-key li").on("click", function () {
            $(".tab-key li").removeClass("active");
            $(this).addClass("active");
        });
        var url = '<?= Url::toRoute(['site/get-village'])?>';
        var page = parseInt($('#page').val()) + 1;
        $.ajax({
            url: url,
            data: {
                'id': id,
                'filter': filter
            },
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                document.getElementById("id").value = id;
                document.getElementById("filter").value = filter;
                $('div .listVillage').html(result);
                $('div .active-prv').html(name);
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