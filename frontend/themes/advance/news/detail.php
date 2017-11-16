<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 11:36 AM
 */
use frontend\helpers\UserHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/** @var $model  \common\models\News */
?>

<!-- content -->
<script src="<?= Yii::$app->request->baseUrl ?>/js/jwplayer/jwplayer.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ng_player.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ng_swfobject.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/js/ParsedQueryString.js"></script>
<script>jwplayer.key = "tOf3A+hW+N76uJtefCw6XMUSRejNvQozOQIaBw==";</script>
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href=""><?= $model->title ?></a>
                </div>
                <div class="m-content">
<!--                    <h1>--><?//= $model->title ?><!--</h1>-->
<!--                    <p class="des-dt">--><?//= $model->short_description ?><!--</p>-->
                    <!--                    --><?php //if($model->type == \common\models\News::TYPE_VIDEO){?>
                    <!--                    <a id="player" onclick="playVideo();"><img-->
                    <!--                            src="--><!--"-->
                    <!--                            width="100%"></a>-->
                    <!--                    <p style="text-align: center;font-size: 18px;">-->
                    <? //= UserHelper::multilanguage('Click ảnh trên để xem video','Click image to watch video') ?><!--</p>-->
                    <!--                    --><?php //} ?>
                    <div class="content-dt">
                        <?= preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $model->content) ?>
                    </div>
                    <div class="post-related">
                        <h2>Bình luận</h2>
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
                                        <img style="width: 90px;height: 70px;"
                                            src="<?= $item->user->getImageLink() ? $item->user->getImageLink() : Yii::$app->request->baseUrl . '/img/avt_df.png' ?>">
                                        <div class="left-comment">
                                            <h5 class=""><?= str_replace(substr($item->user->username, 8), '***', $item->user->username) ?>
                                                <span
                                                    class="time-up"><?= date('d/m/Y H:i:s', $item->updated_at) ?></span>
                                            </h5>

                                            <p><?= $item->content ?></p>
                                        </div>
                                    </div>
                                <?php }
                            } else {
                                echo "<span style='text-align: center'>Chưa có bình luận</span>";
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
            <div class="right-content">
                <div class="block-related block-cm-2">
                    <h3>TIN TỨC LIÊN QUAN</h3>
                    <div class="list-related">
                        <?php if (isset($otherModels) && !empty($otherModels)) {
                            foreach ($otherModels as $item) { ?>
                                <?= \frontend\widgets\NewsWidget::widget(['content' => $item]) ?>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end content -->

<!-- Modal message notice-->

<div class="modal fade" id="notice-modala" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                    Để sau
                </button>
                <button type="button" class="bg-color-2" onclick="backUrl();">Đồng ý
                </button>
                <a id="notice-a1" data-toggle="modal" data-target="#notice-modala" data-dismiss="modal"></a>
            </div>
        </div>
    </div>
</div>

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
                'type_new':<?= \common\models\Comment::TYPE_NEW ?>,
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
                    'content': text,
                    'type':<?= \common\models\Comment::TYPE_NEW ?>
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
                                'type_new':<?= \common\models\Comment::TYPE_NEW ?>,
                                'number': <?= sizeof($listComment) ?>
                            },
                            type: "GET",
                            crossDomain: true,
                            dataType: "text",
                            success: function (result) {
                                alert("Cám ơn quý khách đã phản hồi thông tin!");
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
                                alerrt(result);
                                alert("'Không thành công. Quý khách vui lòng thử lại sau ít phút.");
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
    function playVideo() {
        var url = '<?= $model->getVideoUrl() ? $model->getVideoUrl() : $model->source_url ?>';
        loadPlayer(url, '', '<?= $model->getImageLink() ?>');
//        tag.attr('data-dismiss', 'modal');
    }
</script>

<!-- end Modal message notice -->
