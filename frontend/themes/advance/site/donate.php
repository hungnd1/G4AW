<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\Transaction;

/* @var $this yii\web\View */
/* @var $campaign common\models\Campaign */
/* @var $modal common\models\Transaction */
$this->title = 'Ủng hộ chiến dịch';

?>
    <!-- common page-->
    <div class="content-common">
        <h2 class="title-cm">ỦNG HỘ TIỀN<p>Chúng tôi hỗ trợ ủng hộ tiền qua các hình thức thẻ cào, sms, internet
                banking</p>
        </h2>
        <div class="container">
            <div class="box-u box-u-1">
                <div id="alert-status">

                </div>


                <?php $form = ActiveForm::begin([
                    'id' => 'donate-by-card-form',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'action' => ['/ajax/donate-by-card']
                ]); ?>
                <div>
                    <h4><span>1</span>Mã thẻ cào điện thoại</h4>
                    <?= $form->field($model, 'telco')->dropDownList(Transaction::listTelcoType())->label(false) ?>
                </div>
                <div>
                    <?= $form->field($model, 'scratch_card_code')->textInput(['maxlength' => true, 'placeholder' => 'Mã thẻ'])->label(false) ?>
                </div>
                <div>
                    <?= $form->field($model, 'scratch_card_serial')->textInput(['maxlength' => true, 'placeholder' => 'Serial'])->label(false) ?>
                </div>
                <?= $form->field($model, 'campaign_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'payment_type')->hiddenInput()->label(false) ?>
                <div>
                    <button type="submit" class="bt-common-1">Gửi mã thẻ</button>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
            <div class="box-u box-u-2">
                <h4><span>2</span>Qua tin nhắn SMS</h4>
                <div class="center-u">
                    <div class="in-center-u">
                        <p>Soạn tin: <span>UNGHO <?=$campaign->campaign_code?></span> gửi tới <span>8085</span></p>
                <span class="des-box">Trong đó UNGHO là mã chương trình , <?=$campaign->campaign_code?> là
                mã chiến dịch từ thiện</span>
                    </div>
                </div>
            </div>
            <div class="box-u box-u-3">
                <h4><span>3</span>Qua Internet Banking</h4>
                <div class="center-u">
                    <div class="in-center-u">
                        <p>Chúng tôi hỗ trợ tất cả các ngân hàng, bấm
                            chọn ĐĂNG KÝ CHUYỂN KHOẢN để di chuyển
                            tới trang thanh toán</p>
                    </div>
                </div>
                <div
                    class=""><?= Html::a('Chuyển khoản', ['/site/donate-by-money', 'campaign_id' => $campaign->id], ['class' => 'bt-common-1']) ?></div>
            </div>
            <div class="box-u-post">
                Bạn có thể lựa chọn chuyển tiền hoặc chuyển vật phẩm qua bưu điện theo chịa chỉ:
                <h2>Hệ thống thiện nguyện <span>VnDonor</span></h2>
                <p><b>Địa chỉ:</b> <span>Số 124, Hoàng Quốc Việt, Cầu Giấy, Hà Nội</span> <b>Điện thoại:</b> <span>+84 988 688358</span>
                    <b>Email:</b> vndonor@gmail.com</p>
            </div>
        </div>
    </div>
    <!-- end common page-->
<?php
$js = <<<JS
$(document).on("beforeSubmit", "form#donate-by-card-form", function (e) {
      e.preventDefault();
      var form = $(this);
      if (form.find('.has-error').length)
        {
            return false;
        }
        var target = document.getElementById('loading');
        var spinner = new Spinner(Home.opts).spin(target);
    $.ajax({
             data   : form.serialize(),
             url    : form.attr('action'),
            'dataType': 'json',
            'success': function (data) {
                if (data.success == true) {

                    $('#alert-status').html(data.message);
                    $('#donate-by-card-form')[0].reset();
                }else{

                $('#alert-status').html(data.message);
                }
                 spinner.stop();
            },
            'type': 'post',

        });
        return false;
});
JS;
$this->registerJs($js);
?>