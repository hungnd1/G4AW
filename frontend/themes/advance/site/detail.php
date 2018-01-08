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

/** @var $model  \common\models\GapGeneral */
/** @var $weather \common\models\WeatherDetail */
$title = 'Khuyến cáo';
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
            <div class="left-content" style="width: 100%;">
                <div class="cr-page-link">
                    <a href="<?= Url::toRoute(['site/index']) ?>">Trang chủ</a>
                    <span>/</span>
                    <a href=""><?= $title ?></a>
                </div>
                <table>
                    <?php if (isset($weather_current) && $weather_current) { ?>
                        <tr style="width: 100%">
                            <td valign="top" style="padding-bottom: 50px;">
                                <div class="tinh-tp"><h3><strong>
                                            Với điều kiện thời tiết hôm nay của
                                            <?= $weather_current->station_name ?>:</strong>
                                    </h3><br>(Hôm nay, cập nhật lúc <?= date('H:i', $weather_current->timestamp) ?>)<br><b
                                        style="font-size: 25px;"><?= $weather_current->content ?></b>
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                    <tr>
                                        <td width="100"><img src="<?= $weather_current->image ?>"
                                                             alt="" width="90" height="70"><br>
                                            <b style="text-align: center;font-size: 25px;"><?= $weather_current->t_average ?></b>
                                        </td>
                                        <td width="400" style="padding-left: 30px;"><span class="nhietdo-big">
                                            Nhiệt độ: <b><?= $weather_current->tmin ?><sup>o</sup>C
                                                    - <?= $weather_current->tmax ?><sup>o</sup>C</b><br>
                                                 Nhiệt độ cảm nhận: <b><?= $weather_current->RFTMIN ?><sup>o</sup>C
                                                    - <?= $weather_current->RFTMAX ?><sup>o</sup>C</b><br>
                                                Số giờ nắng: <b><?= $weather_current->hsun ?></b><br>
                                            Tổng lượng mưa: <b><?= $weather_current->precipitation_average . ' ' . $weather_current->precipitation_unit ?></b><br>
                                                Số giờ mưa: <b><?= $weather_current->hprcp ?></b><br>
                                            Khả năng mưa: <b><?= $weather_current->PROPRCP.' % ' ?></b><br>
                                            Tốc độ gió: <b><?= $weather_current->wndspd_km_h ?></b><br>
                                            Hướng gió: <b><?= $weather_current->wnddir ?><br>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                    <div class="content-dt">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end content -->


