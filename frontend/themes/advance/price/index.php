<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 11-Aug-16
 * Time: 10:43 AM
 */
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\widgets\DatePicker;
use yii\helpers\Url;

?>
<!-- content -->
<div class="content">
    <div class="main-cm-2">
        <div class="container">
            <div class="left-content">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href="<?= Url::toRoute(['news/index']) ?>"><?= $title ?></a>
                </div>
                <div class="m-content">
                    <div class="list-news-block">
                        <h1 class="entry-title">Giá tham khảo thị trường trên các tỉnh</h1>
<!--                        <div id="xem-gia-ca-phe"-->
<!--                             style="display:block; text-align:center; line-height:25px; margin:8px 0; border:1px solid #FFD8D9; border-radius:2px; background:#FFF6DD; font-size:16px; padding:8px;">-->
<!--                            Soạn tin nhắn <strong style="color:#0005FF">CAFE</strong> và gửi tới <strong-->
<!--                                style="color:#0005FF">(+84) 901 775 939 </strong> <br>để biết giá chính xác nhất ở khu vực của bạn-->
<!--                        </div>-->
                        <div class="view-by-day">
                            <?php $form = ActiveForm::begin([
                                'options' => ['enctype' => 'multipart/form-data'],
                                'method' => 'POST',
                                'action' => ['price/index'],
                            ]); ?>
                            <div style="width: 30%;">
                                <?=
                                $form->field($model, 'date')->widget(DatePicker::classname(), [
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'placeholder' => '',
                                        'dateFormat' => 'yyyy-MM-dd'
                                    ],
                                ])->label('Xem theo ngày')
                                ?>
                                <?= Html::submitButton('Xem', ['class' => 'btn-success', 'style' => 'width:50px']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="page-intro"> Xem <strong>giá cà phê hôm nay</strong> tại các tỉnh Tây Nguyên mới
                            nhất chính xác nhất. <strong>Gia ca phe</strong> được khảo sát tại các công ty, doanh
                            nghiệp, đại lý cà phê khu vực tỉnh <a
                                href="#lam-dong">Lâm Đồng</a>, <a href="#dak-lak">Đắk Lắk</a>, <a href="#gia-lai">Gia
                                Lai</a>, <a href="#dak-nong">Đắk Nông</a>
                        </div>
                        <h3 class="entry-title" style="color: red;padding-top: 20px;">Giá sàn (ngày <?= $date ?>)</h3>
                        <table class="quotes-table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="tt thleft">Tỉnh<br/><span>/huyện (khu vực khảo sát)</span></th>
                                <th class="gia" style="text-align:left;">Loại<br/>
                                <th class="gia" style="text-align:left;">Giá thu mua<br/><span></span>
                                <th class="gia" style="text-align:left;">Chênh lệch (24h)<br/><span></span>
                                <th class="gia" style="text-align:left;">Đại diện<br/>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($listPrice) {
                                foreach ($listPrice as $price) {
                                    ?>
                                    <tr style="">
                                        <td class="province" colspan="5" style="background-color: #4d79ff "><span
                                                style="color: white;"><?= $price['province_name'] ?></span></td>
                                    </tr>
                                    <?php foreach ($price['price'] as $item) { ?>
                                        <tr style="border-bottom:1px solid #eee;">
                                            <td class="district"><?= $item['province_name'] ?>
                                            </td>
                                            <td class="district"><?= $item['type_coffee']['name_coffee'] ?></td>
                                            <td class="district"><?= \common\helpers\CUtils::formatPrice($item['price_average']) . ' ' . $item['unit'] ?></td>
                                            <td class="district"><?= $item['exchange'] ?></td>
                                            <td class="district"><?= $item['type_coffee']['company'] ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php }
                            } ?>
                            </tbody>
                        </table>

                        <h3 class="entry-title" style="color: red;padding-top: 20px;">Giá quả tươi vối (ngày <?= $date ?>)</h3>
                        <table class="quotes-table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="tt thleft">Tỉnh<br/><span>/huyện (khu vực khảo sát)</span></th>
                                <th class="gia" style="text-align:left;">Giá thu mua<br/><span>Đơn vị: VNĐ/kg</span>
                                <th class="gia" style="text-align:left;">Chênh lệch(24h)<br/><span></span>
                                <th class="gia" style="text-align:left;">Đại diện<br/>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($listPrice1){
                            foreach ($listPrice1 as $price) {
                                ?>
                                <tr style="">
                                    <td class="province" colspan="4" style="background-color: #4d79ff "><span style="color: white;"><?= $price['province_name'] ?></span></td>
                                </tr>
                                <?php foreach ($price['price'] as $item) { ?>
                                    <tr style="border-bottom:1px solid #eee;">
                                        <td class="district"> <?= $item['province_name'] ?>
                                        </td>
                                        <td class="district"><?= \common\helpers\CUtils::formatPrice($item['price_average']) ?></td>
                                        <td class="district"><?= $item['exchange'] ?></td>
                                        <td class="district"><?= $item['type_coffee']['company'] ?></td>
                                    </tr>
                                <?php }} ?>
                            <?php } ?>
                            </tbody>
                        </table>

                        <h3 class="entry-title" style="color: red;padding-top: 20px;">Giá quả tươi chè (ngày <?= $date ?>)</h3>
                        <table class="quotes-table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="tt thleft">Tỉnh<br/><span>/huyện (khu vực khảo sát)</span></th>
                                <th class="gia" style="text-align:left;">Giá thu mua<br/><span>Đơn vị: VNĐ/kg</span>
                                <th class="gia" style="text-align:left;">Chênh lệch(24h)<br/><span></span>
                                <th class="gia" style="text-align:left;">Đại diện<br/>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($listPrice2){
                            foreach ($listPrice2 as $price) {
                                ?>
                                <tr style="">
                                    <td class="province" colspan="4" style="background-color: #4d79ff "><span style="color: white;"><?= $price['province_name'] ?></span></td>
                                </tr>
                                <?php foreach ($price['price'] as $item) { ?>
                                    <tr style="border-bottom:1px solid #eee;">
                                        <td class="district"> <?= $item['province_name'] ?>
                                        </td>
                                        <td class="district"><?= \common\helpers\CUtils::formatPrice($item['price_average']) ?></td>
                                        <td class="district"><?= $item['exchange'] ?></td>
                                        <td class="district"><?= $item['type_coffee']['company'] ?></td>
                                    </tr>
                                <?php } } ?>
                            <?php } ?>
                            </tbody>
                        </table>

                        <h3 class="entry-title" style="color: red;padding-top: 20px;">Giá nhân xô chè (ngày <?= $date ?>)</h3>
                        <table class="quotes-table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="tt thleft">Tỉnh<br/><span>/huyện (khu vực khảo sát)</span></th>
                                <th class="gia" style="text-align:left;">Giá thu mua<br/><span>Đơn vị: VNĐ/kg</span>
                                <th class="gia" style="text-align:left;">Chênh lệch(24h)<br/><span></span>
                                <th class="gia" style="text-align:left;">Đại diện<br/>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($listPrice3){
                            foreach ($listPrice3 as $price) {
                                ?>
                                <tr style="">
                                    <td class="province" colspan="4" style="background-color: #4d79ff "><span style="color: white;"><?= $price['province_name'] ?></span></td>
                                </tr>
                                <?php foreach ($price['price'] as $item) { ?>
                                    <tr style="border-bottom:1px solid #eee;">
                                        <td class="district"> <?= $item['province_name'] ?>
                                        </td>
                                        <td class="district"><?= \common\helpers\CUtils::formatPrice($item['price_average']) ?></td>
                                        <td class="district"><?= $item['exchange'] ?></td>
                                        <td class="district"><?= $item['type_coffee']['company'] ?></td>
                                    </tr>
                                <?php }} ?>
                            <?php } ?>
                            </tbody>
                        </table>

                        <h3 class="entry-title" style="color: red;padding-top: 20px;">Giá nhân xô vối (ngày <?= $date ?>)</h3>
                        <table class="quotes-table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="tt thleft">Tỉnh<br/><span>/huyện (khu vực khảo sát)</span></th>
                                <th class="gia" style="text-align:left;">Giá thu mua<br/><span>Đơn vị: VNĐ/kg</span>
                                <th class="gia" style="text-align:left;">Chênh lệch(24h)<br/><span></span>
                                <th class="gia" style="text-align:left;">Đại diện<br/>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($listPrice4) {
                            foreach ($listPrice4 as $price) {
                                ?>
                                <tr style="">
                                    <td class="province" colspan="4" style="background-color: #4d79ff "><span style="color: white;"><?= $price['province_name'] ?></span></td>
                                </tr>
                                <?php foreach ($price['price'] as $item) { ?>
                                    <tr style="border-bottom:1px solid #eee;">
                                        <td class="district"> <?= $item['province_name'] ?>
                                        </td>
                                        <td class="district"><?= \common\helpers\CUtils::formatPrice($item['price_average']) ?></td>
                                        <td class="district"><?= $item['exchange'] ?></td>
                                        <td class="district"><?= $item['type_coffee']['company'] ?></td>
                                    </tr>
                                <?php }} ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="block-related block-cm-2">
                    <h3>GAPs liên quan</h3>
                    <div class="list-related">
                        <?php if (isset($listNewRelated) && !empty($listNewRelated)) {
                            foreach ($listNewRelated as $item) { ?>
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
