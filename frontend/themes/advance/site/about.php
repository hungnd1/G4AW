<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11Aug16
 * Time: 11:36 AM
 */
use yii\helpers\Url;

/** @var $model  \common\models\Term */
?>

<!-- content -->
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= \yii\helpers\Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            Giới thiệu
        </div>
    </div>
    <div class="container slider">
        <div id="slide-main" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php if (isset($listSlide) && !empty($listSlide)) {
                    $i = 0;
                    foreach ($listSlide as $item) {
                        /** @var $item \common\models\Banner */
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
                        /** @var $item \common\models\Banner */
                        ?>

                        <div class="item <?php if ($i == 0) { ?> active <?php } ?>">
                                    <img style="width: 847px;height: 460px;" src="<?= $item->getImageLink() ?>"
                                         alt="...">
                            <div class="carousel-caption">
                                <div>
                                    <h3>Dự án dịch vụ thông tin Green Coffee</h3>
                                    Cung cấp các dịch vụ thông tin coffee. Chi tiết xem video giới thiệu tại đây.<a href="https://www.youtube.com/watch?v=yhgu77BJoAY" target="_blank">Chi tiết</a><br>
                                </div>
                            </div>
                        </div>
                        <?php $i++;
                    }
                } else { ?>
                    <div class="item active">
                        <img src="http://thongtincaphe.vn/static/news/16.59c9c42d66e891506395181.png" alt="...">
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
</div>
<!-- end content >
