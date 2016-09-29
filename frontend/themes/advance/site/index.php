<?php

/* @var $this yii\web\View */
use common\models\Category;
use frontend\helpers\UserHelper;
use yii\helpers\Url;

$this->title = 'G4WA';
/** @var \common\models\User $user */
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
                                <img style="width: 778px;height: 300px;" src="<?= $item->getImage() ?>" alt="..."></a>
                            <div class="carousel-caption">
                                <div>
                                    <h3>
                                        <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><?= UserHelper::multilanguage($item->title,$item->title_en) ?></a>
                                    </h3>
                                    <?= str_replace(mb_substr(UserHelper::multilanguage($item->short_description,$item->short_description_en), 200, strlen(UserHelper::multilanguage($item->short_description,$item->short_description_en)), 'utf-8'), '...', UserHelper::multilanguage($item->short_description,$item->short_description_en)) ?>
                                    <br>
                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>" class="bt-more-1">
                                        <?= UserHelper::multilanguage('Xem thêm','Read more') ?></a>
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

    <div class="container">
        <div class="block-x">
            <div class="top-bl-x">
                <span class="tit-x"><?= UserHelper::multilanguage('Danh sách vùng','List area') ?></span>
            </div>
            <div class="listVillage1">
                <?php if (isset($listArea) && !empty($listArea)) {
                    /** @var \common\models\Area $item */
                    foreach ($listArea as $item) {
                        $image = $item->getThumbnailLink(); ?>

                        <div class="x-in-list">
                            <div class="thumb-common">
                                <img src="../img/blank.gif">
                                <a href="<?= Url::toRoute(['news/index','id'=>$item->id]) ?>"><img
                                        class="thumb-cm" src="<?= $image ?>"><br></a>
                            </div>
                            <h4><?= UserHelper::multilanguage($item->name,$item->name_en) ?></h4>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- abc -->
            <div class="tab-key">

            </div>
        </div>
    </div>

    <!-- List NEW block-->

    <!--end list CT block-->

    <div class="container">
        <div class="block-x">
            <div class="top-bl-x">
                <span class="tit-x"><?= UserHelper::multilanguage('Danh sách xã','List village') ?></span>
                <div class="select-prv">

                    <?= UserHelper::multilanguage('Tỉnh/Thành phố','Province') ?> <a class="active-prv" id="dLabel" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">"<?= UserHelper::multilanguage('Tất cả','All') ?>"
                        <span class="caret"></span></a>
                    <!--   <div class="block-prv"> -->
                    <ul class="block-prv dropdown-menu" aria-labelledby="dLabel">
                        <li id="all" class="active"><a onclick="loadVillage(0,'Tất cả','')"><?= UserHelper::multilanguage('Tất cả','All') ?> </a></li>
                        <?php if (isset($listProvince)) {
                            /** @var  $province \common\models\Province */
                            foreach ($listProvince as $province) { ?>
                                <li id="li_active"><a
                                        onclick="loadVillage(<?= $province->id ?>,'<?= $province->name ?>','')"><?= UserHelper::multilanguage($province->name, $province->name_en) ?></a>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                    <!--  </div> -->
                </div>
            </div>
            <div class="listVillage">
                <?php if (isset($listVillage) && !empty($listVillage)) {
                    /** @var \common\models\Village $item */
                    foreach ($listVillage as $item) {
                        $image = $item->getImageLink(); ?>

                        <div class="x-in-list">
                            <div class="thumb-common">
                                <img src="../img/blank.gif">
                                <a href="<?= \yii\helpers\Url::toRoute(['village/view', 'id' => $item->id]) ?>"><img
                                        class="thumb-cm" src="<?= $image ?>"><br></a>
                            </div>
                            <h4><?= UserHelper::multilanguage($item->name,$item->name_en) ?></h4>
                        </div>
                    <?php } ?>
                    <div id="last-comment"></div>
                    <input type="hidden" name="page" id="page"
                           value="<?= sizeof($listVillage) - 1 ?>">
                    <input type="hidden" name="numberCount" id="numberCount" value="<?= sizeof($listVillage) ?>">
                    <input type="hidden" name="total" id="total" value="<?= $pages->totalCount ?>">
                    <?php if (count($listVillage) >= 10) { ?>
                        <div class="text-center">
                            <a id="more" onclick="loadMore();" class="more-2"><?= UserHelper::multilanguage('Xem thêm xã','Read more Village') ?></a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <input type="hidden" id="id" value="">
            <input type="hidden" id="filter" value="">
            <!-- abc -->
            <div class="tab-key">
                <ul>
                    <li class="active"><a onclick="loadVillage(0,'Tất cả','')"><?= UserHelper::multilanguage('Tất cả','All') ?></a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','a')">A</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','b')">B</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','c')">C</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','d')">D</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','đ')">Đ</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','e')">E</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','f')">F</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','g')">G</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','h')">H</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','i')">I</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','j')">J</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','k')">K</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','l')">L</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','m')">M</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','n')">N</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','o')">O</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','p')">P</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','q')">Q</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','r')">R</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','s')">S</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','t')">T</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','u')">U</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','v')">V</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','x')">X</a></li>
                    <li><a onclick="loadVillage(0,'Tất cả','y')">Y</a></li>

                </ul>
            </div>
        </div>
    </div>

    <!-- List NEW block-->


    <div class="container">
        <div class="news-block cm-block">
            <h2><?= UserHelper::multilanguage('DANH MỤC TIN TỨC','Category News') ?><a href="<?= Url::toRoute(['news/index']) ?>"><span><?= UserHelper::multilanguage('Tất cả','All') ?></span><i
                        class="fa fa-chevron-right"></i></a></h2>
            <div class="cate-news">
                <ul>
                    <?php if (isset($listNewCategory) && !empty($listNewCategory)) {
                        foreach ($listNewCategory as $item) {
                            /** @var $item \common\models\Category */
                            ?>
                            <li><a onclick="loadCategoryNews(<?= $item->id ?>)"><?= UserHelper::multilanguage($item->display_name,$item->display_name_en) ?></a></li>
                        <?php }
                    } else { ?>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển của bộ KHDT</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="list-item">
                <?php if (isset($listNew) && !empty($listNew)) {
                    /** @var \common\models\News $item */
                    foreach ($listNew as $item) {
                        $thumbnail = $item->getThumbnailLink();
                        ?>
                        <div class="news">
                            <div class="thumb-common">
                                <img src="../img/blank.gif">
                                <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><img class="thumb-cm"
                                                                                                       src="<?= $thumbnail ?>"><br></a>
                            </div>
                            <div class="if-cm-2">
                                <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><h3
                                        class="name-1"><?= UserHelper::multilanguage($item->title,$item->title_en) ?></h3><br></a>
                                <span
                                    class="des-cm-1"><?= str_replace(mb_substr(UserHelper::multilanguage($item->short_description,$item->short_description_en), 100, strlen(UserHelper::multilanguage($item->short_description,$item->short_description_en)), 'utf-8'), '...', UserHelper::multilanguage($item->short_description,$item->short_description_en)) ?>
                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>" class="read-more"><?= UserHelper::multilanguage('Đọc thêm','Read more') ?></a></span>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>

    <!--end list CT block-->

    <!-- List NEW block-->

    <div class="container">
        <div class="news-block cm-block">
            <h2><?= UserHelper::multilanguage('NHÀ NÔNG NÊN BIẾT','FARMERS NEWS') ?><a href="<?= Url::toRoute(['news/index','type'=>Category::TYPE_KNOW]) ?>"><span><?= UserHelper::multilanguage('Tất cả','All') ?></span><i
                        class="fa fa-chevron-right"></i></a></h2>
            <div class="cate-news">
                <ul>
                    <?php if (isset($listKnowCategory) && !empty($listKnowCategory)) {
                        foreach ($listKnowCategory as $item) {
                            /** @var $item \common\models\Category */
                            ?>
                            <li><a onclick="loadCategoryNews(<?= $item->id ?>)"><?= UserHelper::multilanguage($item->display_name,$item->display_name_en) ?></a></li>
                        <?php }
                    } else { ?>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển của bộ KHDT</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                        <li><a href="">Chính sách phát triển</a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="list-item">
                <?php if (isset($listKnow) && !empty($listKnow)) {
                    /** @var \common\models\News $item */
                    foreach ($listKnow as $item) {
                        $thumbnail = $item->getThumbnailLink();
                        ?>
                        <div class="news">
                            <div class="thumb-common">
                                <img src="../img/blank.gif">
                                <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><img class="thumb-cm"
                                                                                                       src="<?= $thumbnail ?>"><br></a>
                            </div>
                            <div class="if-cm-2">
                                <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><h3
                                        class="name-1"><?= UserHelper::multilanguage($item->title,$item->title_en) ?></h3><br></a>
                                <span
                                    class="des-cm-1"><?= str_replace(mb_substr(UserHelper::multilanguage($item->short_description,$item->short_description_en), 100, strlen(UserHelper::multilanguage($item->short_description,$item->short_description_en)), 'utf-8'), '...', UserHelper::multilanguage($item->short_description,$item->short_description_en)) ?>
                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>" class="read-more"><?= UserHelper::multilanguage('Đọc thêm','Read
                                    more') ?></a></span>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>

    <!--end list CT block-->

    <div class="container" >
        <div class="banner-block clearfix">
            <img src="../img/banners.jpg">
        </div>
    </div>

    <!--end banner block-->

    <!--partner block-->
    <div class="partner-block clearfix">
        <div class="container">
            <h2><?= UserHelper::multilanguage('Các đơn vị liên kết','Unit Link') ?><a href="<?= Url::toRoute(['site/linked']) ?>"><span><?= UserHelper::multilanguage('Tất cả','All') ?></span><i
                        class="fa fa-chevron-right"></i></a></h2>
            <ul class="bxslider3">
                <?php if (isset($listUnit) && !empty($listUnit)) {
                    foreach ($listUnit as $item) {
                        /** @var $item \common\models\UnitLink */ ?>
                        <li style="width: 400px;"><a target="_blank" href="<?= $item->link ?>"><img
                                    src="<?= $item->getThumbnailLink() ?>">
                                <h3><?= UserHelper::multilanguage($item->name,$item->name) ?></h3>
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
        var id = ($('#id').val()) ;
        var total = parseInt(($('#total').val())) ;
        var filter = ($('#filter').val()) ;
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
                document.getElementById("id").value =id;
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