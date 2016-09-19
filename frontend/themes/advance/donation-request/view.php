<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 10-Aug-16
 * Time: 3:25 PM
 */
/** @var $model \common\models\DonationRequest */

?>


<div class="m-content">
    <h1><?= $model->title ?></h1>
    <div class="info-rq">
        <b>Số tiền cần hỗ trợ:</b> <span class="color-1"><?= \frontend\helpers\FormatNumber::formatNumber($model->expected_amount) ?> VNĐ</span><span class="br-hide"><br></span>
        <?php if ($model->status == \common\models\DonationRequest::STATUS_NEW){ ?>
            <b>Trạng thái:</b>  <span class="color-2">Đang chờ duyệt</span><span class="br-hide"><br></span>
        <?php }else if ($model->status == \common\models\DonationRequest::STATUS_REJECTED){ ?>
        <b>Trạng thái:</b> <span class="color-3">Yêu cầu bị từ chối</span><span class="br-hide">
        <?php }else if ($model->status == \common\models\DonationRequest::STATUS_APPROVED){ ?>
            <b>Trạng thái:</b>  <span class="color-1">Đã tạo chiến dịch</span><span class="br-hide">
        <?php } ?>


    </div>
    <?php if($model->status == \common\models\DonationRequest::STATUS_REJECTED){ ?>
        <p class="alert-reject">
        <?= $model->admin_note ? $model->admin_note : "Đang cập nhật" ?>
    </p>
    <?php } ?>

    <p class="des-dt"><b>Mô tả:</b> <?= $model->short_description ? $model->short_description : "Đang cập nhật" ?></p>

    <div class="text-center">
        <img height="150px" width="250px" src="<?= $model->getThumbnailLink() ?>">
    </div>
    <div class="content-dt">
        <p><b>Lý do/Hoàn cảnh:</b></p>
        <?= $model->content?$model->content:"Đang cập nhật"?>
    </div>
    <div class="line-bt">
        <?php if ($model->status == \common\models\DonationRequest::STATUS_NEW) { ?>
        <a  onclick = "deleteDonation();"  class="bt-common-1 bt-st-2" > Xóa yêu cầu </a >
        <a href = "<?= \yii\helpers\Url::toRoute(['donation-request/update','id'=>$model->id]) ?>" class="bt-common-1" > Chỉnh sửa yêu cầu </a >
            <?php
        } ?>
    </div>
</div>
<script type="text/javascript">
    function deleteDonation() {
        if (confirm('Bạn có chắc chắn muốn xóa yêu cầu này?') == true) {
            var url = '<?= \yii\helpers\Url::toRoute(['donation-request/delete']) ?>';
            $.ajax({
                url: url,
                data: {
                    'id': <?= $model->id ?>
                },
                type: "get",
                crossDomain: true,
                dataType: "text",
                success: function (result) {
                    var rs = JSON.parse(result);
                    if (rs['success']) {
                        alert('Xóa yêu cầu thành công');
                    }
                    window.location = '<?= \yii\helpers\Url::toRoute(['user/my-page', 'id' => Yii::$app->user->id]) ?>';
                    return;
                },
                error: function (result) {
                    alert('Xóa yêu cầu không thành công');
                    return;
                }
            });//end jQuery.ajax
        }
    }
</script>