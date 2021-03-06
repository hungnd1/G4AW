<?php
/**
 * Created by PhpStorm.
 * User: Linh
 * Date: 14/03/2016
 * Time: 11:28 AM
 */
use frontend\helpers\UserHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/** @var $model \common\models\Village */
/** @var $this \yii\web\View */

$this->title = 'Chi tiết xã';
?>

<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>"><?= UserHelper::multilanguage('Trang chủ', 'Home') ?></a>
            <span>/</span>
            <a href="<?= Url::toRoute(['village/view', 'id_village' => $model->id]) ?>"><?= UserHelper::multilanguage($model->name, $model->name_en) ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="top-ct-1">
                <div class="left-top-ct">
                    <div class="thumb-common">
                        <img src="../img/blank.gif">
                        <a href="<?= Url::toRoute(['village/view', 'id' => $model->id]) ?>"><img
                                class="thumb-cm"
                                src="<?= $model->getImageLink() ?>"><br></a>
                    </div>
                </div>

                <div class="bt-comp hidden-sm hidden-xs">

                    <p>
                        <b><?= UserHelper::multilanguage('ERIPT','ERIPT') ?></b><br>
                        <span><?= UserHelper::multilanguage('Đơn vị tài trợ CNTT','Sponsors IT') ?></span>
                    </p>
                    <a href="" class="logo-cp"><img
                            src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/img/logo-cp.png">
                    </a>
                </div>
                <div class="i-f-right">
                    <h1><?= $model->name ?></h1>
                    <div class="des-dt-1">
                        <p><span class="t-des"><?= UserHelper::multilanguage('Vị trí địa lý','Geographical location') ?>:</span>
                        <div id="map" style="height: 200px;width:300px;"></div>
                        <br>
                        <p><span class="t-des"><?= UserHelper::multilanguage('Mô tả','Description') ?>: </span><?= UserHelper::multilanguage($model->description,$model->description_en) ?>
                            <br>
                    </div>
                </div>
            </div>
            <div class="tab-ct">
                <!-- Nav tabs -->
                <div class="out-ul-tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#t1" aria-controls="my-cp" role="tab"
                                                                  data-toggle="tab"><?= UserHelper::multilanguage('Thông tin cơ bản','Basic information') ?></a></li>
                        <li role="presentation"><a href="#t6" aria-controls="request" role="tab" data-toggle="tab"><?= UserHelper::multilanguage('Thông tin chia sẻ','Share information') ?></a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="t1">

                        <div class="out-tb-x">
                            <table class="tb-x">
                                <tr>
                                    <td>STT</td>
                                    <td><?= UserHelper::multilanguage('Thông tin cơ bản','Basic information') ?></td>
                                    <td><?= UserHelper::multilanguage('Đơn vị tính','Unit') ?></td>
                                    <td><?= UserHelper::multilanguage('Số luợng','Number') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="t6">
                        <div class="box-comment">
                            <div class="top-box-comment">
                                <b><?= UserHelper::multilanguage('Bạn muốn chia sẻ?','You want share?') ?></b>
                                <?php $form = ActiveForm::begin([
                                    'id' => 'comment-form'
                                ]); ?>
                                <div>
                                    <textarea rows="4" id="comment"></textarea>
                                </div>
                                <div class="line-bottom-comment">
                                    <span><i><?= UserHelper::multilanguage('Nhập ý kiến của bạn','Enter your comment') ?></i></span>
                                    <a onclick="feedBack($(this));" class="send-comment"><?= UserHelper::multilanguage('Gửi ý kiến','Send') ?></a><br><br>
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
                                                <h5 class=""><?= str_replace(substr($item->user->username,8),'***',$item->user->username) ?>  <span
                                                        class="time-up"><?= date('d/m/Y H:i:s', $item->updated_at) ?></span>
                                                </h5>

                                                <p><?= $item->content ?></p>
                                            </div>
                                        </div>
                                    <?php }
                                } else {
                                    echo "<span style='text-align: center'".UserHelper::multilanguage('Chưa có bình luận','Not found comment')."</span>";
                                } ?>
                                <div id="last-comment"></div>
                                <input type="hidden" name="page" id="page"
                                       value="<?= sizeof($listComment) - 1 ?>">
                                <input type="hidden" name="numberCount" id="numberCount"
                                       value="<?= sizeof($listComment) ?>">
                                <?php
                                if (!isset($numberCheck) && $pages->totalCount > sizeof($listComment)) { ?>
                                    <div class="text-center" style="    padding-top: 20px;">
                                        <a id="more" onclick="readMore();" class="more-2"><?= UserHelper::multilanguage('Xem thêm','Read more') ?></a>
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
                                                                aria-hidden="true"></span> <b><?= UserHelper::multilanguage('THÔNG BÁO','NOTIFICATION') ?></b>
                </h4>
            </div>
            <div class="modal-body" id="msg3"></div>
            <div class="modal-footer">
                <button type="button" class="bg-color-1" data-dismiss="modal">
                    <?= UserHelper::multilanguage('Để sau','Later') ?>
                </button>
                <button type="button" class="bg-color-2" onclick="backUrl();"><?= UserHelper::multilanguage('Đồng ý','OK') ?>
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
                'type_new':<?= \common\models\Comment::TYPE_VILLAGE ?>,
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
            $('#msg3').html("<?= UserHelper::multilanguage('Quý khách cần đăng nhập để thực hiện chức năng này','You need to log in to perform this function') ?>");
            $('#notice-a1').click();
        } else {
            var text = 'undefined' != $.trim($('#comment').val()) ? $.trim($('#comment').val()) : '';
            if (null == text || '' == text) {
                alert("<?= UserHelper::multilanguage('Không thành công. Qúy khách vui lòng nhập lời bình.','Unsuccessful. Please enter your comments.') ?>");
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
                    'content': text,
                    'type':<?= \common\models\Comment::TYPE_VILLAGE ?>
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
                                'type_new':<?= \common\models\Comment::TYPE_VILLAGE ?>,
                                'number': <?= sizeof($listComment) ?>
                            },
                            type: "GET",
                            crossDomain: true,
                            dataType: "text",
                            success: function (result) {
                                alert("<?= UserHelper::multilanguage('Bình luận của quý khách sẽ được duyệt trong thời gian sớm nhất. Cám ơn quý khách!','Your comment will be reviewed as soon as possible. Thank you!') ?>");
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