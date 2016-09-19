<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:28 AM
 */
use common\models\LeadDonor;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/** @var $model \common\models\Village */
/** @var $listCampaign \common\models\Campaign */
/** @var $leadDonor \common\models\LeadDonor */
/** @var $this \yii\web\View */

$this->title = 'Chi tiết xã';
?>

<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['village/view', 'id_village' => $model->id]) ?>"><?= $model->name ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="top-ct-1">
                <div class="left-top-ct">
                    <div class="thumb-common">
                        <img src="../img/blank.gif">
                        <a href="<?= Url::toRoute(['village/view', 'id_village' => $model->id]) ?>"><img
                                class="thumb-cm"
                                src="<?= $model->getImageLink() ?>"><br></a>
                    </div>
                    <div class="bt-comp2">
                        <?php if (isset($leadDonor) && !empty($leadDonor)) {
                            /** @var $leadDonor \common\models\LeadDonor */
                            ?>
                            <a href="<?= Url::toRoute(['donor/view', 'id' => $leadDonor->id]) ?>" class="logo-cp"><img
                                    src="<?= $leadDonor->getImageLink() ?>"></a>
                            <h5><?= $leadDonor->name ?><br>
                                <span>Đơn vị bảo trợ</span>
                            </h5>
                        <?php } ?>
                    </div>
                </div>

                <div class="bt-comp hidden-sm hidden-xs">

                    <p>
                        <b> Tập đoàn VNPT</b><br>
                        <span>Đơn vị tài trợ CNTT</span>
                    </p>
                    <a href="<?= Url::toRoute(['donor/view', 'id' => 1]) ?>" class="logo-cp"><img
                            src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/logo-cp.png">
                    </a>
                </div>
                <div class="i-f-right">
                    <h1><?= $model->name ?></h1>
                    <div class="des-dt-1">
                        <p><span
                                class="t-des">Ngày thành lập:</span> <?php if ($model->establish_date) { ?> <?= date('d/m/Y', strtotime($model->establish_date)) ?><?php } else {
                                echo "Đang cập nhật";
                            } ?></p>
                        <p><span class="t-des">Vị trí địa lý:</span>
                        <div id="map" style="height: 200px;width:300px;"></div>
                        <br>
                        <p><span class="t-des">Bản đồ hành chính:</span><br>
                            <?php if ($model->getMapImageLink()) { ?>
                                <img width="300px" height="200px" class="bd-hc" src="<?= $model->getMapImageLink() ?>">
                            <?php } else {
                                echo "Đang cập nhật";
                            } ?>
                        </p>
                        <!--                        <p><span class="t-des">Bản đồ hành chính:</span>-->
                        <?php //if ($model->description) {
                        //                                echo $model->description;
                        //                            } else {
                        //                                echo "Đang cập nhật";
                        //                            } ?><!--</p>-->
                    </div>
                </div>
            </div>
            <div class="tab-ct">
                <!-- Nav tabs -->
                <div class="out-ul-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#t1" aria-controls="my-cp" role="tab"
                                                                  data-toggle="tab">Thông tin cơ bản</a></li>
                        <li role="presentation"><a href="#t2" aria-controls="favorite" role="tab" data-toggle="tab">Phát
                                triển cộng đồng</a></li>
                        <li role="presentation"><a href="#t3" aria-controls="request" role="tab" data-toggle="tab">Đề
                                xuất ý tưởng PTCĐ</a></li>
                        <li role="presentation"><a href="#t4" aria-controls="request" role="tab" data-toggle="tab">Mua
                                bán sản phẩm địa phương</a></li>
                        <li role="presentation"><a href="#t5" aria-controls="request" role="tab" data-toggle="tab">Những
                                tấm lòng nhân ái</a></li>
                        <li role="presentation"><a href="#t6" aria-controls="request" role="tab" data-toggle="tab">Thông
                                tin chia sẻ</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="t1">
                        <?php if ($model->file_upload) { ?>
                            <div class="o-save"><a href="<?= $model->getFileUpload() ?>" class="save-info">Tải file
                                    thông tin <i class="fa fa-download"
                                                 aria-hidden="true"></i></a>
                            </div>
                        <?php } ?>

                        <div class="out-tb-x">
                            <table class="tb-x">
                                <tr>
                                    <td>STT</td>
                                    <td>Các thông tin cơ bản</td>
                                    <td>Đơn vị tính</td>
                                    <td>Số lượng</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Diện tích đất tự nhiên</td>
                                    <td>ha</td>
                                    <td><?= $model->natural_area ? $model->natural_area : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Diện tích đất canh tác</td>
                                    <td>ha</td>
                                    <td><?= $model->arable_area ? $model->arable_area : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Ngành sản xuất chính</td>
                                    <td></td>
                                    <td><?= $model->main_industry ? $model->main_industry : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Sản phẩm chủ lực</td>
                                    <td></td>
                                    <td><?= $model->main_product ? $model->main_product : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Dân số</td>
                                    <td>người</td>
                                    <td><?= $model->population ? $model->population : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Thu nhập bình quân trên đầu người</td>
                                    <td>triệu</td>
                                    <td><?= $model->gdp ? $model->gdp : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Số hộ nghèo</td>
                                    <td>hộ</td>
                                    <td><?= $model->poor_family ? $model->poor_family : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Số hộ chưa có nhà kiên cố</td>
                                    <td>hộ</td>
                                    <td><?= $model->no_house_family ? $model->no_house_family : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Số lớp học còn thiếu</td>
                                    <td>lớp</td>
                                    <td><?= $model->missing_classes ? $model->missing_classes : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Tình hình điện chiếu sáng</td>
                                    <td></td>
                                    <td><?= $model->lighting_condition ? $model->lighting_condition : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Tình hình cấp nước sinh hoạt</td>
                                    <td></td>
                                    <td><?= $model->water_condition ? $model->water_condition : 'Đang cập nhật' ?></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Số sân chơi trẻ em, nhà văn hóa còn thiếu</td>
                                    <td></td>
                                    <td><?= $model->missing_playground ? $model->missing_playground : 'Đang cập nhật' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="t2">
                        <div class="list-item">
                            <?php if (isset($listCampaignDonor) && !empty($listCampaignDonor)) {
                                foreach ($listCampaignDonor as $item) {
                                    ?>
                                    <div class="out-card">
                                        <div class="card-item">
                                            <div class="thumb-common">
                                                <img src="../img/blank.gif">
                                                <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><img
                                                        class="thumb-cm"
                                                        src="<?= $item->thumbnail ?>"><br></a>
                                            </div>
                                            <div class="if-cm-1">
                                                <div class="top-cp">
                                                    <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>">
                                                        <h3 class="name-1"><?= $item->name ?></h3><br></a>
                                                </div>
                                                <div class="bt-cp">
                                                    <a href="<?= Url::toRoute(['donor/view', 'id' => $item->id]) ?>"
                                                       class="logo-cp"><img
                                                            src="<?= $item->image ?>"></a>
                                                    <a href="<?= Url::toRoute(['donor/view', 'id' => $item->id]) ?>">
                                                        <h4><?= $item->lead_donor_name ?></h4></a>
                                                    <span
                                                        class="add-cp"><?= LeadDonor::_substr($item->lead_donor_add, 25) ?></span>
                                                    <div class="bar">
                                                        <div class="bar-status"
                                                             style="width:<?= $item->status ?>%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="t3">
                        <div class="list-item list-tab-2">
                            <?php if (isset($newIdea) && !empty($newIdea)) {
                                foreach ($newIdea as $item) {
                                    /** @var $item \common\models\News */
                                    ?>
                                    <div class="out-card">
                                        <div class="card-item">
                                            <div class="thumb-common">
                                                <img src="../img/blank.gif">
                                                <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><img
                                                        class="thumb-cm" src="<?= $item->getThumbnailLink() ?>"><br></a>
                                            </div>
                                            <div class="if-cm-1">
                                                <div class="top-cp">
                                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>">
                                                        <h3 class="name-1"><?= $item->title ?></h3><br></a>
                                                    <p class="des-ct-2"><?= $item->short_description ?></p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="t4">
                        <div class="list-item list-tab-3">
                            <?php if (isset($newTrade) && !empty($newTrade)) {
                                foreach ($newTrade as $item) {
                                    /** var $item \common\models\News */
                                    ?>
                                    <div class="out-card">
                                        <div class="card-item">
                                            <div class="thumb-common">
                                                <img src="../img/blank.gif">
                                                <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>"><img
                                                        class="thumb-cm" src="<?= $item->getThumbnailLink() ?>"><br></a>
                                            </div>
                                            <div class="if-cm-1">
                                                <div class="top-cp">
                                                    <a href="<?= Url::toRoute(['news/detail', 'id' => $item->id]) ?>">
                                                        <h3 class="name-1"><?= $item->title ?></h3><br></a>
                                                    <p class="des-ct-2"><?= $item->short_description ?></p>
                                                </div>
                                                <div class="bt-cp">
                                                    <p class="price">Giá: <span><?= $item->price ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="t5">
                        <div class="list-item list-tab-4">
                            <?php if (isset($listCampaignKind) && !empty($listCampaignKind)) {
                                foreach ($listCampaignKind as $item) {
                                    ?>
                                    <div class="out-card">
                                        <div class="card-item">
                                            <div class="thumb-common">
                                                <img src="../img/blank.gif">
                                                <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>"><img
                                                        class="thumb-cm" src="<?= $item->thumbnail ?>"><br></a>
                                            </div>
                                            <div class="if-cm-1">
                                                <div class="top-cp">
                                                    <a href="<?= Url::toRoute(['campaign/view', 'id' => $item->id]) ?>">
                                                        <h3 class="name-1"><?= $item->name ?></h3><br></a>
                                                    <p class="des-ct-2"><?= $item->short_description ?></p>
                                                </div>
                                                <div class="bt-cp">
                                                    <a href="<?= Url::toRoute(['donor/view', 'id' => $item->id_lead_donor]) ?>"
                                                       class="logo-cp"><img
                                                            src="<?= $item->image ?>"></a>
                                                    <a href="<?= Url::toRoute(['donor/view', 'id' => $item->id_lead_donor]) ?>">
                                                        <h4><?= $item->lead_donor_name ?></h4></a>
                                                    <span
                                                        class="add-cp"><?= LeadDonor::_substr($item->lead_donor_add, 25) ?></span>
                                                    <div class="bar">
                                                        <div class="bar-status"
                                                             style="width:<?= $item->status ?>%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="t6">
                        <div class="box-comment">
                            <div class="top-box-comment">
                                <b>Bạn muốn chia sẻ?</b>
                                <?php $form = ActiveForm::begin([
                                    'id' => 'comment-form'
                                ]); ?>
                                <div>
                                    <textarea rows="4" id="comment"></textarea>
                                </div>
                                <div class="line-bottom-comment">
                                    <span><i>Nhập ý kiến của bạn</i></span>
                                    <a onclick="feedBack($(this));" class="send-comment">Gửi ý kiến</a><br><br>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                            <div class="list-comments">
                                <div id="head-comment"></div>
                                <?php if (isset($listComment) && !empty($listComment)) {
                                    foreach ($listComment as $item) {
                                        ?>
                                        <div class="comment-box-item">
                                            <img
                                                src="<?= $item->user->getAvatar() ? $item->user->getAvatar() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>">
                                            <div class="left-comment">
                                                <h5 class=""><?= $item->user->username ?> <span
                                                        class="time-up"><?= date('d/m/Y H:i:s', $item->updated_at) ?></span>
                                                </h5>

                                                <p><?= $item->content ?></p>
                                            </div>
                                        </div>
                                    <?php }
                                } else {
                                    echo "<span style='text-align: center'>Chưa có bình luận.</span>";
                                } ?>
                                <div id="last-comment"></div>
                                <input type="hidden" name="page" id="page"
                                       value="<?= sizeof($listComment) - 1 ?>">
                                <input type="hidden" name="numberCount" id="numberCount"
                                       value="<?= sizeof($listComment) ?>">
                                <?php
                                if (!isset($numberCheck) && $pages->totalCount > sizeof($listComment)) { ?>
                                    <div class="text-center" style="    padding-top: 20px;">
                                        <a id="more" onclick="readMore();" class="more-2">Xem thêm</a>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-ads-cp">
            <div class="left-block-ads">
                <a href="<?= Url::toRoute(['donor/view', 'id' => $leadDonor->id]) ?>" class="img-cp"><img
                        class="thumb-cm" src="<?= $leadDonor->getImageLink() ?>"></a>
                <div class="left-info-ads">
                    <h3>
                        <a href="<?= Url::toRoute(['donor/view', 'id' => $leadDonor->id]) ?>"><?= $leadDonor->name ?></a>
                    </h3>
                    <p class="if-leaddn">
                        <i class="fa fa-globe" aria-hidden="true"></i> <span>Website:</span> <a target="_blank"
                                                                                                href="<?= $leadDonor->website ?>"><?= $leadDonor->website ?></a><br>

                        <i class="fa fa-envelope" aria-hidden="true"></i> <span>Email:</span> <?= $leadDonor->email ?>
                        <br>

                        <i class="fa fa-mobile" aria-hidden="true"></i>
                        <span>Số điện thoại:</span> <?= $leadDonor->phone ?><br>

                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span>Địa chỉ:</span> <?= $leadDonor->address ?>
                    </p>
                </div>
            </div>
            <div class="right-block-ads">
                <?php if ($leadDonor->video) { ?>
                    <video width="400" controls>
                        <source src="<?= $leadDonor->getVideoUrl() ?>" type="video/mp4">
                        <source src="<?= $leadDonor->getVideoUrl() ?>" type="video/avi">
                    </video>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal message notice-->

<div class="modal fade" id="notice-modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top: 100px">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12"
                                                                class="glyphicon glyphicon-bell"
                                                                aria-hidden="true"></span> <b>THÔNG BÁO</b>
                </h4>
            </div>
            <div class="modal-body" id="msg3"></div>
            <div class="modal-footer">
                <button type="button" class="bg-color-1" data-dismiss="modal">
                    Để Sau
                </button>
                <button type="button" class="bg-color-2" onclick="backUrl();">Đồng
                    Ý
                </button>
                <a id="notice-a1" data-toggle="modal" data-target="#notice-modal1" data-dismiss="modal"></a>
            </div>
        </div>
    </div>
</div>

<!-- end Modal message notice -->
<!-- end common page-->
<script type="text/javascript">

    //readmore comment
    function readMore() {
        <!--$('#last-comment').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/img/loading.gif' />");-->
        var url = '<?= Url::toRoute(['site/list-comments'])?>';
        var page = parseInt($('#page').val()) + 1;
        var numberCount = parseInt($('#numberCount').val()) + 10;
        $.ajax({
            url: url,
            data: {
                'contentId': <?= $model->id?>,
                'type': 'comment',
                'page': page,
                'number': <?= sizeof($listComment) ?>
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

    function backUrl() {
        location.href = '<?= \yii\helpers\Url::toRoute(['site/login']) ?>';
    }
    function feedBack(tag) {
        var check_user = '<?= Yii::$app->user->id ? 0 : 1 ?>';
        var page = parseInt($('#page').val()) + 1;
        var numberCount = parseInt($('#numberCount').val()) + 10;
        if (check_user == 1) {
            $('#msg3').html("Quý khách cần đăng nhập để thực hiện chức năng này");
            $('#notice-a1').click();
        } else {
            var text = 'undefined' != $.trim($('#comment').val()) ? $.trim($('#comment').val()) : '';
            if (null == text || '' == text) {
                alert("Không thành công. Qúy khách vui lòng nhập lời bình.");
                $('#comment').val('');
                $('#comment').focus();
                return;
            }
            $('#head-comment').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/img/loading.gif' />");
            var url = '<?= Url::toRoute(['site/feedback'])?>';
            $.ajax({
                url: url,
                data: {
                    'contentId': <?= $model->id?>,
                    'content': text
                },
                type: "GET",
                crossDomain: true,
                dataType: "text",
                success: function (result) {
                    var rs = JSON.parse(result);
                    if (rs['success']) {
                        // var data = result['data'];
                        //$(html).insertAfter('#head-comment');
                        $('#head-comment').html('');
                        //alert(rs['message']);
                        $('#comment').val('');
                        var url = '<?= Url::toRoute(['site/list-comment'])?>';
                        $.ajax({
                            url: url,
                            data: {
                                'contentId': <?= $model->id?>,
                                'type': 1,//load lai comments
                                'page': 1,
                                'number': <?= sizeof($listComment) ?>
                            },
                            type: "GET",
                            crossDomain: true,
                            dataType: "text",
                            success: function (result) {
                                if (null != result && '' != result) {
                                    $('div .list-comments').html(result);
                                    document.getElementById("page").value = page + 9;
                                    document.getElementById("numberCount").value = numberCount;
                                    if (numberCount > <?= $pages->totalCount ?>) {
                                        $('#more').css('display', 'block');
                                    }
                                } else {
                                    $('#last-comment').html('');
                                }

                                return;
                            },
                            error: function (result) {
                                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                                return;
                            }
                        });//end jQuery.ajax
                        return;
                    } else {
                        alert(rs['message']);
                        $('#head-comment').html('');
                    }

                },
                error: function (result) {
                    alert('Không thành công. Qúy khách vui lòng thử lại sau ít phút.');
                    $('#head-comment').html('');
                    return;
                }
            });//end jQuery.ajax
        }
    }

    function initMap() {
        var mapDiv = document.getElementById('map');
        var map = new google.maps.Map(mapDiv, {
            center: {lat: <?= $model->latitude ?>, lng: <?= $model->longitude ?>},
            zoom: 8
        });

        var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
        var beachMarker = new google.maps.Marker({
            position: {lat: <?= $model->latitude ?>, lng: <?= $model->longitude ?>},
            map: map,
            icon: image
        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&callback=initMap">
</script>