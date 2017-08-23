<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 10/8/2016
 * Time: 9:46 AM
 */
use common\models\Campaign;
use common\models\DonationRequest;
use common\models\LeadDonor;
use common\models\User;
use frontend\helpers\UserHelper;
use yii\helpers\Url;

?>
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('Trang chủ', 'Home') ?></a>
            <span>/</span>
            <a href="<?= Url::toRoute(['user/my-page', 'id' => Yii::$app->user->identity->id]) ?>"><?= UserHelper::multilanguage('Cá nhân', 'Personal') ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="left-cn hidden-xs hidden-sm">
                <div class="block-cm-left top-cn-left">
                    <a href="<?= Url::toRoute(['user/update', 'id' => $model->id]) ?>" class="bt-edit"><i
                            class="fa fa-pencil"></i></a>
                    <?php $image = $model->getImageLink() ? $model->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png'; ?>
                    <img src="<?= $image ?>"><br>
                    <h4><?= $model->full_name ?></h4>
                    <p><?= $model->address ?></p>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Số điện thoại', 'Phone number') ?></span><br>
                    <span class="b-span"><?= $model->username ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span">Email</span><br>
                    <span class="b-span"><?= $model->email ?></span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Giới tính', 'Gender') ?></span><br>
                    <span class="b-span">
                        <?= \common\models\Subscriber::getGenderName($model->sex) ?>
                    </span>
                </div>
                <div class="block-cm-left">
                    <span class="t-span"><?= UserHelper::multilanguage('Tuổi', 'Old') ?></span><br>
                    <span class="b-span"><?= User::getOld($model->birthday) ?></span>
                </div>
            </div>
            <div class="right-cn">
                <div class="creat-cp">
                    <h4>Bạn có nhu cầu cần bán hoặc mua coffee?</h4>
                    <a href="<?= Url::toRoute(['exchange/index']) ?>">ĐĂNG KÝ ĐỂ BÁN HOẶC MUA COFFEE</a>
                </div>
                <div class="tab-ct">
                    <!-- Nav tabs -->
                    <div class="out-ul-tab ">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="hidden-lg hidden-md hidden-info"><a href="#t1"
                                                                                               aria-controls=""
                                                                                               role="tab"
                                                                                               data-toggle="tab">Thông
                                    tin cá nhân</i></a></li>
                            <li role="presentation" class="active"><a href="#t2" aria-controls="" role="tab"
                                                                      data-toggle="tab">Danh sách cần bán</i></a></li>
                            <li role="presentation"><a href="#t3" aria-controls="" role="tab" data-toggle="tab">Danh
                                    sách cần mua</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane hidden-lg hidden-md hidden-info " id="t1">
                            <div class="block-cm-left top-cn-left">
                                <a href="<?= Url::toRoute(['user/update', 'id' => $model->id]) ?>" class="bt-edit"><i
                                        class="fa fa-pencil"></i></a>
                                <?php $image = $model->getImageLink() ? $model->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png'; ?>
                                <img src="<?= $image ?>"><br>
                                <h4><?= $model->username ?></h4>
                                <p><?= $model->address ?></p>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Số điện thoại</span><br>
                                <span class="b-span"><?= $model->username ?></span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Email</span><br>
                                <span class="b-span"><?= $model->email ?></span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Giới tính</span><br>
                            <span class="b-span">
                                <?= \common\models\Subscriber::getGenderName($model->sex) ?>
                            </span>
                            </div>
                            <div class="block-cm-left">
                                <span class="t-span">Tuổi</span><br>
                                <span class="b-span"><?= User::getOld($model->birthday) ?></span>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="t2">
                            <div class="list-item list-tab-2">
                                <div class="list-comments">
                                    <div id="head-comment"></div>
                                    <?php if (isset($listExchangeSold) && !empty($listExchangeSold)) {
                                        foreach ($listExchangeSold as $item) {
                                            /** @var $item \common\models\Exchange */
                                            ?>
                                            <div class="comment-box-item">
                                                <img
                                                    src="<?= \common\models\Subscriber::findOne($item->subscriber_id) ? \common\models\Subscriber::findOne($item->subscriber_id)->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>"
                                                    style="width: 140px;height: 140px;margin-right: 50px;">
                                                <div class="left-comment">
                                                    <h5 class=""><?= \common\models\Subscriber::findOne($item->subscriber_id)->username ?>
                                                    </h5>
                                                    <p>Tổng sản
                                                        lượng: <?php $total = \common\models\TotalQuality::findOne($item->total_quality_id);
                                                        /** @var $total \common\models\TotalQuality */
                                                        echo $total->min_total_quality . "-" . $total->max_total_quality . " tấn"; ?>
                                                        <br>
                                                        Đã
                                                        bán: <?php $sold = \common\models\Sold::findOne($item->sold_id);
                                                        /** @var $sold \common\models\Sold */
                                                        echo $sold->min_sold . "-" . $sold->max_sold . " tấn";
                                                        ?><br>
                                                        Loại cà
                                                        phê: <?php $typeCoffee = \common\models\TypeCoffee::findOne($item->type_coffee);
                                                        /** @var $typeCoffee \common\models\TypeCoffee */
                                                        echo $typeCoffee->name;
                                                        ?><br>
                                                        Giá bán: <?= $item->price ?> VNĐ<br>
                                                        Vị
                                                        trí: <?= $item->location_name ? $item->location_name : 'Chưa xác định' ?>
                                                        <br>
                                                        Thời gian: <?= date('d/m/Y H:m:s', $item->created_at) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php }
                                    } else {
                                        echo "<span style='text-align: center'>Chưa có người bán</span>";
                                    } ?>
                                    <div id="last-comment"></div>
                                    <input type="hidden" name="page" id="page"
                                           value="<?= sizeof($listExchangeSold) - 1 ?>">
                                    <input type="hidden" name="numberCount" id="numberCount"
                                           value="<?= sizeof($listExchangeSold) ?>">
                                    <?php
                                    if (!isset($numberCheck) && $pages->totalCount > sizeof($listExchangeSold)) { ?>
                                        <div class="text-center" style="    padding-top: 20px;">
                                            <a id="more" onclick="readMore();" class="more-2">Xem thêm</a>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="t3">
                            <div class="list-item list-tab-4">
                                <div class="list-comments">
                                    <div id="head-comment"></div>
                                    <?php if (isset($listExchangeBuy) && !empty($listExchangeBuy)) {
                                        foreach ($listExchangeBuy as $item) {
                                            /** @var $item \common\models\ExchangeBuy */
                                            ?>
                                            <div class="comment-box-item">
                                                <img
                                                    src="<?= \common\models\Subscriber::findOne($item->subscriber_id) ? \common\models\Subscriber::findOne($item->subscriber_id)->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>"
                                                    style="width: 140px;height: 140px;margin-right: 50px;">
                                                <div class="left-comment">
                                                    <h5 class=""><?= \common\models\Subscriber::findOne($item->subscriber_id)->username ?>
                                                    </h5>
                                                    <p>Tổng sản
                                                        lượng: <?php $total = \common\models\TotalQuality::findOne($item->total_quantity);
                                                        /** @var $total \common\models\TotalQuality */
                                                        echo $total->min_total_quality . "-" . $total->max_total_quality . " tấn"; ?>
                                                        <br>
                                                        Loại cà
                                                        phê: <?php $typeCoffee = \common\models\TypeCoffee::findOne($item->type_coffee_id);
                                                        /** @var $typeCoffee \common\models\TypeCoffee */
                                                        echo $typeCoffee->name;
                                                        ?><br>
                                                        Giá mua: <?= $item->price_buy ?> VNĐ<br>
                                                        Vị
                                                        trí: <?= $item->location_name ? $item->location_name : 'Chưa xác định' ?>
                                                        <br>
                                                        Thời gian: <?= date('d/m/Y H:m:s', $item->created_at) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php }
                                    } else {
                                        echo "<span style='text-align: center'>Chưa có người bán</span>";
                                    } ?>
                                    <div id="last-exchange-buy"></div>
                                    <input type="hidden" name="pageBuy" id="pageBuy"
                                           value="<?= sizeof($listExchangeBuy) - 1 ?>">
                                    <input type="hidden" name="numberCountBuy" id="numberCountBuy"
                                           value="<?= sizeof($listExchangeBuy) ?>">
                                    <?php
                                    if (!isset($numberCheckBuy) && $pages_buy->totalCount > sizeof($listExchangeBuy)) { ?>
                                        <div class="text-center" style="    padding-top: 20px;">
                                            <a id="moreBuy" onclick="readMoreBuy();" class="more-2">Xem thêm </a>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function readMore() {
        var url = '<?= Url::toRoute(['user/list-exchange'])?>';
        var page = parseInt($('#page').val()) + 1;
        var numberCount = parseInt($('#numberCount').val()) + 10;
        $.ajax({
            url: url,
            data: {
                'page': page,
                'number': <?= sizeof($listExchangeSold) ?>
            },
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                if (null != result && '' != result) {
                    $(result).insertBefore('#last-comment');
                    document.getElementById("page").value = page + 9;
                    document.getElementById("numberCount").value = numberCount;
                    if (numberCount > <?= $pages->totalCount ?>) {
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

    function readMoreBuy() {
        var url = '<?= Url::toRoute(['user/list-exchange-buy'])?>';
        var page = parseInt($('#pageBuy').val()) + 1;
        var numberCount = parseInt($('#numberCountBuy').val()) + 10;
        $.ajax({
            url: url,
            data: {
                'page': page,
                'number': <?= sizeof($listExchangeBuy) ?>
            },
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                if (null != result && '' != result) {
                    $(result).insertBefore('#last-exchange-buy');
                    document.getElementById("pageBuy").value = page + 9;
                    document.getElementById("numberCountBuy").value = numberCount;
                    if (numberCount > <?= $pages_buy->totalCount ?>) {
                        $('#moreBuy').css('display', 'none');
                    }

                    $('#last-exchange-buy').html('');
                } else {
                    $('#last-exchange-buy').html('');
                }

                return;
            },
            error: function (result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                $('#last-exchange-buy').html('');
                return;
            }
        });//end jQuery.ajax
    }

</script>