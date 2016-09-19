<?php
use yii\helpers\Url;
/** @var  $bank \common\models\CampaignBankAsm  */
/** @var  $village \common\models\Village */
?>

<!-- content -->
<div class="content">
    <div class="container">
        <div class="cr-page-link">
            <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= Url::toRoute(['village/view','id_village'=>$village->id]) ?>"><?= $village->name ?></a>
        </div>
    </div>
    <div class="container">
        <div class="main-cm-1">
            <div class="hd-uh">
                <h1>Hướng dẫn ủng hộ</h1>
                <p><b>Để tham gia đóng góp ủng hộ cho chiến dịch bạn có thể đóng góp theo các hình thức sau:</b></p>
                <div class="box-donor">
                    <h4><span>1</span>Đóng góp trực tiếp</h4>
                    <p class="des-pt-dn">Đối với cá nhân, đơn vị muốn đóng góp tiền hoặc vật phẩm trực tiếp. Xin vui lòng gửi đóng góp tới địa chỉ </p>
                    <?php if(isset($bank)){
                        foreach($bank as $item){
                            /** @var $item \common\models\CampaignBankAsm */
                            if($item->type ==  \common\models\CampaignBankAsm::TYPE_DIRECT_ADDRESS){
                            ?>
                            <div class="box-dt-donor">
                                <?= $item->content ?>
                            </div>
                        <?php }}
                    } ?>

                </div>
                <div class="box-donor">
                    <h4><span>2</span>Chuyển khoản</h4>
                    <p class="des-pt-dn">Cá nhân, đơn vị có thể đóng góp thông qua chuyển khoản tài khoản ngân hàng. Chúng tôi nhận hỗ trợ thông qua các ngân hàng sau: </p>
                    <div class="box-dt-donor">
                        <?php if(isset($bank)){
                            foreach($bank as $item){
                                /** @var $item \common\models\CampaignBankAsm */
                                if($item->type ==  \common\models\CampaignBankAsm::TYPE_BANK_ACCOUNT){ ?>
                                    <div class="bank-if">
                                        <img class="logo-bank" src="<?= $item->bank->getBankImage() ?>">
                                        <div class="right-bank">
                                            <p>
                                                <span class="bank-name"><?= $item->bank->name ?></span>
                                                Số TK: <?= $item->account_number ?><br>
                                                Chi nhánh: <?= $item->branch ?><br>
                                                Chủ khoản: <?= $item->account_owner ?> <br>
                                            </p>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->

