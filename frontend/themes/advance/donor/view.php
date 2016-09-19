<?php
use yii\helpers\Url;

/**
 * @var $model \common\models\LeadDonor
 */
/** @var \common\models\News $item */
?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['donor/view?id=' . $model->id]) ?>"><?= $model->name ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="top-ct-1">
                <div class="left-top-ct left-top-ct2">
                    <div class="thumb-common">
                        <img src="../img/blank2.gif">
                        <a href="<?= Url::toRoute(['donor/view', 'id' => $model->id]) ?>"><img class="thumb-cm"
                                                                                               src="<?= $model->getImageLink(); ?>"><br></a>
                    </div>
                </div>
                <div class="i-f-right">
                    <h1 class="cp-name"><?= $model->name ?></h1>
                    <div class="des-dt-1">
                        <p><?= $model->description ?>
                        <p class="if-leaddn">
                            <i class="fa fa-globe" aria-hidden="true"></i> <span>Website:</span> <a target="_blank"
                                                                                                    href="<?= $model->website ?>"><?= $model->website ?></a><br>

                            <i class="fa fa-envelope" aria-hidden="true"></i> <span>Email:</span> <?= $model->email ?>
                            <br>

                            <i class="fa fa-mobile" aria-hidden="true"></i>
                            <span>Số điện thoại:</span> <?= $model->phone ?><br>

                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>Địa chỉ:</span> <?= $model->address ?>
                        </p>
                        <?php if ($model->website){ ?><a href="<?= $model->website ?>" target="_blank"
                                                         class="bt-more-1">Xem thêm</a><?php } ?></p>
                    </div>
                </div>
            </div>
            <div class="tab-ct">
                <!-- Nav tabs -->
                <div class="out-ul-tab tab-cm2">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#t1" aria-controls="" role="tab"
                                                                  data-toggle="tab">Các xã bảo trợ</a></li>
                        <li role="presentation"><a href="#t2" aria-controls="" role="tab" data-toggle="tab">Bài viết về
                                chúng tôi</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="t1">
                        <?php
                        if (isset($village)) {

                            foreach ($village as $item) {
                                $image = $item->getImageLink();
                                ?>
                                <div class="x-in-list">
                                    <div class="thumb-common">
                                        <img src="../img/blank.gif">
                                        <a href="<?= Url::toRoute(['village/view?id_village=' . $item->id]) ?>"><img
                                                class="thumb-cm" src="<?= $image ?>"><br></a>
                                    </div>
                                    <h4><?= $item->name ?></h4>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="t2">
                        <div class="news-lead">
                            <?php
                            if (isset($listNews)) {
                                foreach ($listNews as $item) {
                                    $image = $item->getThumbnailLink();
                                    ?>
                                    <div class="news">
                                        <div class="thumb-common">
                                            <img src="../../../img/blank.gif">
                                            <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><img
                                                    class="thumb-cm" src="<?= $image ?>"><br></a>
                                        </div>
                                        <div class="if-cm-2">
                                            <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><h3
                                                    class="name-1"><?= $item->title ?></h3></a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <h3 class="name-1">Đang cập nhập</h3>
                                <?php
                            }
                            ?>

                        </div>
                        <div class="clearfix"></div>
                        <?php
                        $pagination = new \yii\data\Pagination(['totalCount' => $pages->totalCount, 'pageSize' => 6]);
                        echo \yii\widgets\LinkPager::widget([
                            'pagination' => $pagination,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle=\"tab\"]').on('show.bs.tab', function (e) {
            localStorage.setItem('lastTab', $(this).attr('href'));
        });
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href=\"' + lastTab + '\"]').tab('show');
        }
    });
</script>